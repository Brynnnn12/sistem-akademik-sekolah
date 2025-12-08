<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Services\KelasService;
use App\Services\RombelService;
use App\Http\Requests\StoreKelasRequest;
use App\Http\Requests\UpdateKelasRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KelasController extends Controller
{
    public function __construct(
        private KelasService $kelasService,
        private RombelService $rombelService
    ) {
        $this->authorizeResource(Kelas::class, 'kelas');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {

        $kelas = $this->kelasService->getAllPaginated(
            perPage: 15,
            search: request('search')
        );

        $statistics = $this->kelasService->getStatistics();

        return view('dashboard.kelas.index', compact('kelas', 'statistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {

        $availableWaliKelas = $this->kelasService->getAvailableWaliKelas();

        return view('dashboard.kelas.create', compact('availableWaliKelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKelasRequest $request): RedirectResponse
    {
        try {
            $kelas = $this->kelasService->create($request->validated());

            return redirect()
                ->route('kelas.index')
                ->with('success', 'Kelas berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e; // Re-throw validation exceptions to be handled by Laravel
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $kelas): View
    {
        $kelas->load(['waliKelas', 'siswas']);

        // Get current active tahun ajaran
        $tahunAjaranAktif = \App\Models\TahunAjaran::aktif()->first();

        // Get siswa yang sudah ada di kelas ini
        $siswaDiKelas = $this->rombelService->getSiswaByKelas($kelas->id, $tahunAjaranAktif?->id);

        // Get siswa yang tersedia untuk ditambahkan
        $siswaTersedia = $tahunAjaranAktif
            ? $this->rombelService->getSiswaAvailableForKelas($kelas->id, $tahunAjaranAktif->id)
            : collect();

        return view('dashboard.kelas.show', compact('kelas', 'tahunAjaranAktif', 'siswaDiKelas', 'siswaTersedia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kelas): View
    {

        $availableWaliKelas = $this->kelasService->getAvailableWaliKelas();

        // Include current wali kelas in the list
        if ($kelas->waliKelas && !$availableWaliKelas->contains($kelas->waliKelas)) {
            $availableWaliKelas->push($kelas->waliKelas);
        }

        return view('dashboard.kelas.edit', compact('kelas', 'availableWaliKelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKelasRequest $request, Kelas $kelas): RedirectResponse
    {
        try {
            $this->kelasService->update($kelas, $request->validated());

            return redirect()
                ->route('kelas.index')
                ->with('success', 'Kelas berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e; // Re-throw validation exceptions to be handled by Laravel
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $kelas): RedirectResponse
    {

        try {
            $this->kelasService->delete($kelas);

            return redirect()
                ->route('kelas.index')
                ->with('success', 'Kelas berhasil dihapus.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e; // Re-throw validation exceptions to be handled by Laravel
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Add siswa to kelas.
     */
    public function addSiswa(Kelas $kelas, \Illuminate\Http\Request $request): RedirectResponse
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ]);

        try {
            $this->rombelService->assignSiswaToKelas(
                $request->siswa_id,
                $kelas->id,
                $request->tahun_ajaran_id
            );

            return redirect()
                ->back()
                ->with('success', 'Siswa berhasil ditambahkan ke kelas.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove siswa from kelas.
     */
    public function removeSiswa(Kelas $kelas, int $kelasSiswaId): RedirectResponse
    {
        try {
            $this->rombelService->removeSiswaFromKelas($kelasSiswaId);

            return redirect()
                ->back()
                ->with('success', 'Siswa berhasil dihapus dari kelas.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
