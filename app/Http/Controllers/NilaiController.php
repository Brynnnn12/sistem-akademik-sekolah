<?php

namespace App\Http\Controllers;

use App\Models\KomponenNilai;
use App\Models\PenugasanMengajar;
use App\Repositories\NilaiRepository;
use App\Services\NilaiService;
use App\Http\Requests\StoreKomponenNilaiRequest;
use App\Http\Requests\StoreNilaiSiswaRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function __construct(
        protected NilaiService $nilaiService,
        protected NilaiRepository $nilaiRepository
    ) {
        // Authorization ditangani secara manual di setiap method
    }

    /**
     * Display a listing of mata pelajaran yang diajar guru.
     */
    public function index(): View
    {
        $user = Auth::user();

        if ($user->hasRole('Admin')) {
            // Admin lihat semua penugasan
            $penugasans = PenugasanMengajar::with(['mataPelajaran', 'kelas', 'tahunAjaran'])
                ->paginate(10);
        } else {
            // Guru lihat penugasan yang diajar
            $penugasans = PenugasanMengajar::where('guru_id', $user->id)
                ->with(['mataPelajaran', 'kelas', 'tahunAjaran'])
                ->paginate(10);
        }

        return view('dashboard.nilai.index', compact('penugasans'));
    }

    /**
     * Show the form for creating a new komponen nilai.
     */
    public function create(Request $request): View
    {
        $penugasanId = $request->query('penugasan_id');
        $penugasan = PenugasanMengajar::with(['mataPelajaran', 'kelas', 'tahunAjaran'])
            ->findOrFail($penugasanId);

        // Check if user can create komponen nilai for this penugasan
        if (!Auth::user()->hasRole('Admin') && $penugasan->guru_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('dashboard.nilai.create', compact('penugasan'));
    }

    /**
     * Store a newly created komponen nilai.
     */
    public function store(StoreKomponenNilaiRequest $request): RedirectResponse
    {
        $penugasan = PenugasanMengajar::findOrFail($request->penugasan_mengajar_id);

        // Check if user can create komponen nilai for this penugasan
        if (!Auth::user()->hasRole('Admin') && $penugasan->guru_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        try {
            $komponenNilai = $this->nilaiService->createKomponenNilai($request->validated());

            return redirect()->route('nilai.show', $komponenNilai->id)
                ->with('success', 'Komponen nilai berhasil dibuat.');
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['bobot' => $e->getMessage()]);
        }
    }

    /**
     * Display komponen nilai dan form input nilai siswa.
     */
    public function show(int $komponenNilaiId): View
    {
        $komponenNilai = KomponenNilai::with([
            'penugasan_mengajar.mataPelajaran',
            'penugasan_mengajar.kelas',
            'penugasan_mengajar.tahunAjaran',
            'penugasan_mengajar.kelas.siswas'
        ])->findOrFail($komponenNilaiId);

        // Check if user can view this komponen
        if (!Auth::user()->hasRole('Admin') && $komponenNilai->penugasan_mengajar->guru_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Get nilai siswa yang sudah ada
        $nilaiSiswas = $this->nilaiRepository->getNilaiSiswaByKomponen($komponenNilaiId)
            ->keyBy('siswa_id');

        // Get siswa di kelas ini untuk tahun ajaran yang sama dengan komponen nilai
        $tahunAjaranId = $komponenNilai->penugasan_mengajar->tahun_ajaran_id;
        $siswas = $komponenNilai->penugasan_mengajar->kelas->siswas()
            ->wherePivot('tahun_ajaran_id', $tahunAjaranId)
            ->get();

        return view('dashboard.nilai.manage', compact('komponenNilai', 'nilaiSiswas', 'siswas'));
    }

    /**
     * Display daftar komponen nilai untuk penugasan tertentu.
     */
    public function showByPenugasan(int $penugasanId): View
    {
        $penugasan = PenugasanMengajar::with([
            'mataPelajaran',
            'kelas',
            'tahunAjaran',
            'guru'
        ])->findOrFail($penugasanId);

        // Check if user can view komponen for this penugasan
        if (!Auth::user()->hasRole('Admin') && $penugasan->guru_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Get komponen nilai untuk penugasan ini
        $komponenNilais = $this->nilaiRepository->getKomponenNilaiByPenugasan($penugasanId);

        return view('dashboard.nilai.show', compact('penugasan', 'komponenNilais'));
    }

    /**
     * Show the form for editing komponen nilai.
     */
    public function edit(int $komponenNilaiId): View
    {
        $komponenNilai = KomponenNilai::with('penugasan_mengajar')->findOrFail($komponenNilaiId);

        // Check if user can edit this komponen
        if (!Auth::user()->hasRole('Admin') && $komponenNilai->penugasan_mengajar->guru_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('dashboard.nilai.edit', compact('komponenNilai'));
    }

    /**
     * Update komponen nilai.
     */
    public function update(StoreKomponenNilaiRequest $request, int $komponenNilaiId): RedirectResponse
    {
        $komponenNilai = KomponenNilai::findOrFail($komponenNilaiId);

        // Check if user can update this komponen
        if (!Auth::user()->hasRole('Admin') && $komponenNilai->penugasan_mengajar->guru_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        try {
            $komponenNilai->update($request->validated());

            return redirect()->route('nilai.show', $komponenNilaiId)
                ->with('success', 'Komponen nilai berhasil diperbarui.');
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['bobot' => $e->getMessage()]);
        }
    }

    /**
     * Store nilai siswa massal.
     */
    public function storeNilaiSiswa(StoreNilaiSiswaRequest $request, int $komponenNilaiId): RedirectResponse
    {
        $komponenNilai = KomponenNilai::findOrFail($komponenNilaiId);

        // Check if user can manage nilai for this komponen
        if (!Auth::user()->hasRole('Admin') && $komponenNilai->penugasan_mengajar->guru_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        try {
            $this->nilaiService->storeNilaiSiswaMassal($komponenNilaiId, $request->validated()['nilai']);

            return back()->with('success', 'Nilai siswa berhasil disimpan dan nilai akhir diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove komponen nilai.
     */
    public function destroy(int $komponenNilaiId): RedirectResponse
    {
        $komponenNilai = KomponenNilai::findOrFail($komponenNilaiId);

        // Check if user can delete this komponen
        if (!Auth::user()->hasRole('Admin') && $komponenNilai->penugasan_mengajar->guru_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $komponenNilai->delete();

        return redirect()->route('nilai.index')
            ->with('success', 'Komponen nilai berhasil dihapus.');
    }
}
