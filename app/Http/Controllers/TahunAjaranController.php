<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTahunAjaranRequest;
use App\Http\Requests\UpdateTahunAjaranRequest;
use App\Models\TahunAjaran;
use App\Services\TahunAjaranService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TahunAjaranController extends Controller
{
    public function __construct(
        protected TahunAjaranService $tahunAjaranService
    ) {
        // Opsi Modern: Authorize Resource di Constructor
        // Ini otomatis mapping method controller ke policy (viewAny, create, update, dll)
        $this->authorizeResource(TahunAjaran::class, 'tahun_ajaran');
    }

    public function index(Request $request): View
    {
        // Service akan menangani logika search jika parameter ada
        $tahunAjarans = $this->tahunAjaranService->getPaginatedTahunAjaran(
            $request->input('search')
        );

        return view('dashboard.tahun-ajaran.index', compact('tahunAjarans'));
    }

    public function create(): View
    {
        return view('dashboard.tahun-ajaran.create');
    }

    public function store(StoreTahunAjaranRequest $request): RedirectResponse
    {
        $this->tahunAjaranService->createTahunAjaran($request->validated());

        return to_route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan!');
    }

    public function show(TahunAjaran $tahunAjaran): View
    {
        return view('dashboard.tahun-ajaran.show', compact('tahunAjaran'));
    }

    public function edit(TahunAjaran $tahunAjaran): View
    {
        return view('dashboard.tahun-ajaran.edit', compact('tahunAjaran'));
    }

    public function update(UpdateTahunAjaranRequest $request, TahunAjaran $tahunAjaran): RedirectResponse
    {
        $this->tahunAjaranService->updateTahunAjaran($tahunAjaran, $request->validated());

        return to_route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui!');
    }

    public function destroy(TahunAjaran $tahunAjaran): RedirectResponse
    {
        try {
            $this->tahunAjaranService->deleteTahunAjaran($tahunAjaran);

            return to_route('tahun-ajaran.index')
                ->with('success', 'Tahun ajaran berhasil dihapus!');
        } catch (\Exception $e) {
            // Kita catch di sini karena Service melempar error spesifik (misal: Sedang Aktif)
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function setActive(TahunAjaran $tahunAjaran): RedirectResponse
    {
        $this->tahunAjaranService->setActiveTahunAjaran($tahunAjaran);

        return back()->with('success', 'Tahun ajaran berhasil diaktifkan!');
    }
}
