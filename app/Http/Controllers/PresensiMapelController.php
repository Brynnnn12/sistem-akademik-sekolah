<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use App\Models\PenugasanMengajar;
use App\Models\JadwalMengajar;
use App\Repositories\KelasSiswaRepository;
use App\Services\PresensiMapelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;

class PresensiMapelController extends Controller
{
    public function __construct(
        private PresensiMapelService $service,
        private KelasSiswaRepository $kelasSiswaRepository
    ) {
        // Apply authorization middleware
        $this->middleware('auth');
        $this->middleware('can:viewAny,App\Models\PresensiMapel')->only(['index', 'jurnal']);
        $this->middleware('can:create,App\Models\PresensiMapel')->only(['create', 'store']);
    }

    /**
     * Tampilkan halaman pemilihan kelas dan mata pelajaran
     */
    public function index()
    {
        $guru = Auth::user();
        $hariIni = JadwalMengajar::getNamaHariIndonesia();
        $tanggalHariIni = now()->format('Y-m-d');

        // Ambil jadwal mengajar hari ini
        $jadwalHariIni = JadwalMengajar::whereHas('penugasanMengajar', function ($query) use ($guru) {
            $query->where('guru_id', $guru->id)->aktif();
        })
            ->with(['penugasanMengajar.kelas', 'penugasanMengajar.mataPelajaran', 'penugasanMengajar.tahunAjaran'])
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai')
            ->get();

        // Ambil semua penugasan untuk fallback (jika tidak ada jadwal)
        $penugasanList = PenugasanMengajar::where('guru_id', $guru->id)
            ->aktif()
            ->with(['kelas', 'mataPelajaran', 'tahunAjaran'])
            ->get();

        return view('dashboard.presensi-mapel.index', [
            'jadwalHariIni' => $jadwalHariIni,
            'penugasanList' => $penugasanList,
            'hariIni' => $hariIni,
            'tanggalHariIni' => $tanggalHariIni,
        ]);
    }

    /**
     * Tampilkan form input presensi untuk kelas dan mata pelajaran tertentu
     */
    public function create(Request $request)
    {
        $guru = Auth::user();

        // Jika ada jadwal_id, ambil dari jadwal
        if ($request->has('jadwal_id')) {
            $jadwal = JadwalMengajar::with('penugasanMengajar')->findOrFail($request->jadwal_id);
            $penugasan = $jadwal->penugasanMengajar;

            // Validasi bahwa jadwal ini milik guru yang login
            if ($penugasan->guru_id !== $guru->id) {
                return redirect()->route('presensi-mapel.index')
                    ->with('error', 'Anda tidak memiliki akses ke jadwal ini.');
            }

            $kelasId = $penugasan->kelas_id;
            $mapelId = $penugasan->mata_pelajaran_id;
            $tanggal = now()->format('Y-m-d');
            $jamMulai = $jadwal->jam_mulai->format('H:i');
        } else {
            // Fallback: manual input
            $request->validate([
                'kelas_id' => 'required|exists:kelas,id',
                'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
                'tanggal' => 'required|date',
                'jam_mulai' => 'nullable|date_format:H:i',
            ]);

            $kelasId = $request->kelas_id;
            $mapelId = $request->mata_pelajaran_id;
            $tanggal = $request->tanggal;
            $jamMulai = $request->jam_mulai;

            // Validasi: Cek apakah guru berhak mengajar
            $tahunAjaranAktif = TahunAjaran::aktif()->first();

            $penugasan = PenugasanMengajar::where('guru_id', $guru->id)
                ->where('kelas_id', $kelasId)
                ->where('mata_pelajaran_id', $mapelId)
                ->where('tahun_ajaran_id', $tahunAjaranAktif?->id)
                ->first();

            if (!$penugasan) {
                return redirect()->route('presensi-mapel.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengisi presensi mata pelajaran ini di kelas tersebut.');
            }
        }

        $tahunAjaranAktif = TahunAjaran::aktif()->first();

        // Ambil daftar siswa di kelas tersebut untuk tahun ajaran aktif
        $siswaList = $this->kelasSiswaRepository->getByKelas($kelasId, $tahunAjaranAktif?->id);

        if ($siswaList->isEmpty()) {
            return redirect()->route('presensi-mapel.index')
                ->with('error', 'Tidak ada siswa di kelas ini untuk tahun ajaran aktif.');
        }

        // Cek apakah sudah ada data presensi (untuk mode edit)
        $presensiData = $this->service->getPresensiForEdit($kelasId, $mapelId, $tanggal);

        return view('dashboard.presensi-mapel.create', [
            'kelas' => $penugasan->kelas,
            'mataPelajaran' => $penugasan->mataPelajaran,
            'tahunAjaran' => $tahunAjaranAktif,
            'tanggal' => $tanggal,
            'jamMulai' => $jamMulai,
            'siswaList' => $siswaList,
            'presensiExisting' => $presensiData['presensi'],
            'materiExisting' => $presensiData['materi'],
        ]);
    }

    /**
     * Simpan data presensi massal
     */
    public function store(Request $request)
    {
        $guru = Auth::user();

        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'nullable|date_format:H:i',
            'materi' => 'nullable|string|max:1000',
            'presensi' => 'required|array',
            'presensi.*.status' => 'required|in:H,S,I,A,B',
            'presensi.*.catatan' => 'nullable|string|max:255',
        ], [
            'presensi.required' => 'Data presensi siswa harus diisi.',
            'presensi.*.status.required' => 'Status kehadiran siswa harus dipilih.',
            'presensi.*.status.in' => 'Status kehadiran tidak valid.',
            'materi.max' => 'Materi pembelajaran maksimal 1000 karakter.',
        ]);

        try {
            // Validasi data
            $this->service->validatePresensiData($request->all());

            // Simpan presensi
            $this->service->simpanPresensi($guru, $request->all());

            return redirect()->route('presensi-mapel.index')
                ->with('success', 'Presensi berhasil disimpan!');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Tampilkan riwayat jurnal mengajar guru
     */
    public function jurnal(Request $request)
    {
        $guru = Auth::user();

        $filters = [
            'kelas_id' => $request->kelas_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];

        $result = $this->service->getJurnalMengajarGuru($guru->id, $filters);

        // Ambil data untuk filter dropdown
        $kelasList = PenugasanMengajar::where('guru_id', $guru->id)
            ->with('kelas')
            ->get()
            ->pluck('kelas')
            ->unique('id');

        $mapelList = PenugasanMengajar::where('guru_id', $guru->id)
            ->with('mataPelajaran')
            ->get()
            ->pluck('mataPelajaran')
            ->unique('id');

        return view('dashboard.presensi-mapel.jurnal', [
            'jurnal' => $result['jurnal'],
            'total' => $result['total'],
            'kelasList' => $kelasList,
            'mapelList' => $mapelList,
            'filters' => $filters,
        ]);
    }
}
