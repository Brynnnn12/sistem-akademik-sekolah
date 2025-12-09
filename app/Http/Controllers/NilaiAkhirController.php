<?php

namespace App\Http\Controllers;

use App\Models\NilaiAkhir;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use App\Services\NilaiAkhirService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NilaiAkhirController extends Controller
{
    protected $nilaiAkhirService;

    public function __construct(NilaiAkhirService $nilaiAkhirService)
    {
        $this->nilaiAkhirService = $nilaiAkhirService;
    }

    /**
     * Display a listing of nilai akhir (leger) untuk mata pelajaran tertentu.
     */
    public function index(Request $request): View
    {
        $mataPelajaranId = $request->get('mata_pelajaran_id');
        $tahunAjaranId = $request->get('tahun_ajaran_id');

        // Default ke tahun ajaran aktif jika tidak dispecify
        if (!$tahunAjaranId) {
            $tahunAjaranAktif = TahunAjaran::where('aktif', true)->first();
            $tahunAjaranId = $tahunAjaranAktif ? $tahunAjaranAktif->id : null;
        }

        $nilaiAkhirs = [];
        $mataPelajaran = null;
        $tahunAjaran = null;

        if ($mataPelajaranId && $tahunAjaranId) {
            $mataPelajaran = MataPelajaran::find($mataPelajaranId);
            $tahunAjaran = TahunAjaran::find($tahunAjaranId);
            $nilaiAkhirs = $this->nilaiAkhirService->getNilaiAkhirByMataPelajaran($mataPelajaranId, $tahunAjaranId);
        }

        // Ambil data untuk dropdown
        $mataPelajarans = MataPelajaran::all();
        $tahunAjarans = TahunAjaran::all();

        return view('nilai-akhir.index', compact(
            'nilaiAkhirs',
            'mataPelajaran',
            'tahunAjaran',
            'mataPelajarans',
            'tahunAjarans',
            'mataPelajaranId',
            'tahunAjaranId'
        ));
    }

    /**
     * Generate nilai akhir untuk mata pelajaran tertentu.
     */
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ]);

        $mataPelajaran = MataPelajaran::findOrFail($request->mata_pelajaran_id);
        $tahunAjaran = TahunAjaran::findOrFail($request->tahun_ajaran_id);

        try {
            $results = $this->nilaiAkhirService->generateNilaiAkhir($mataPelajaran, $tahunAjaran);

            return response()->json([
                'success' => true,
                'message' => 'Nilai akhir berhasil di-generate',
                'data' => $results,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Rekap nilai akhir untuk wali kelas - menampilkan rapor akhir semua siswa
     */
    public function rekapWaliKelas(Request $request): View
    {
        $this->authorize('rekapWaliKelas', NilaiAkhir::class);

        $tahunAjaranId = $request->get('tahun_ajaran_id');

        // Default ke tahun ajaran aktif jika tidak dispecify
        if (!$tahunAjaranId) {
            $tahunAjaranAktif = TahunAjaran::where('aktif', true)->first();
            $tahunAjaranId = $tahunAjaranAktif ? $tahunAjaranAktif->id : null;
        }

        // Cari kelas yang diwalikan oleh user saat ini
        $waliKelas = Kelas::where('wali_kelas_id', Auth::id())->first();

        if (!$waliKelas) {
            abort(403, 'Anda tidak memiliki akses sebagai wali kelas.');
        }

        $tahunAjaran = TahunAjaran::find($tahunAjaranId);
        $rekapData = [];

        if ($tahunAjaran) {
            $rekapData = $this->nilaiAkhirService->getRekapNilaiAkhirKelas($waliKelas->id, $tahunAjaran->id);
        }

        // Ambil data untuk dropdown
        $tahunAjarans = TahunAjaran::all();

        return view('nilai-akhir.rekap-wali-kelas', compact(
            'rekapData',
            'waliKelas',
            'tahunAjaran',
            'tahunAjarans',
            'tahunAjaranId'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404); // Tidak digunakan untuk sistem otomatis
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(StoreNilaiAkhirRequest $request)
    // {
    //     abort(404); // Tidak digunakan untuk sistem otomatis
    // }

    /**
     * Display the specified resource.
     */
    public function show(NilaiAkhir $nilaiAkhir)
    {
        abort(404); // Tidak digunakan untuk sistem otomatis
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NilaiAkhir $nilaiAkhir)
    {
        abort(404); // Tidak digunakan untuk sistem otomatis
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateNilaiAkhirRequest $request, NilaiAkhir $nilaiAkhir)
    // {
    //     abort(404); // Tidak digunakan untuk sistem otomatis
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NilaiAkhir $nilaiAkhir)
    {
        abort(404); // Tidak digunakan untuk sistem otomatis
    }
}
