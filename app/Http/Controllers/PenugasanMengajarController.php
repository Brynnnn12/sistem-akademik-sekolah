<?php

namespace App\Http\Controllers;

use App\Models\PenugasanMengajar;
use App\Services\PenugasanMengajarService;
use App\Http\Requests\StorePenugasanMengajarRequest;
use App\Http\Requests\UpdatePenugasanMengajarRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PenugasanMengajarController extends Controller
{
    public function __construct(
        private PenugasanMengajarService $penugasanMengajarService
    ) {
        $this->authorizeResource(PenugasanMengajar::class, 'penugasan_mengajar');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $penugasanMengajars = $this->penugasanMengajarService->getAllPaginated(
            perPage: 15,
            search: request('search')
        );

        $statistics = $this->penugasanMengajarService->getStatistics();

        return view('dashboard.penugasan-mengajar.index', compact('penugasanMengajars', 'statistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $availableGuru = $this->penugasanMengajarService->getAvailableGuru();

        return view('dashboard.penugasan-mengajar.create', compact('availableGuru'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePenugasanMengajarRequest $request): RedirectResponse
    {
        $this->penugasanMengajarService->create($request->validated());

        return redirect()->route('penugasan-mengajar.index')
            ->with('success', 'Penugasan mengajar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PenugasanMengajar $penugasanMengajar): View
    {

        return view('dashboard.penugasan-mengajar.show', compact('penugasanMengajar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PenugasanMengajar $penugasanMengajar): View
    {

        $availableGuru = $this->penugasanMengajarService->getAvailableGuru();

        return view('dashboard.penugasan-mengajar.edit', compact('penugasanMengajar', 'availableGuru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePenugasanMengajarRequest $request, PenugasanMengajar $penugasanMengajar): RedirectResponse
    {
        $this->penugasanMengajarService->update($penugasanMengajar, $request->validated());

        return redirect()->route('penugasan-mengajar.index')
            ->with('success', 'Penugasan mengajar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PenugasanMengajar $penugasanMengajar): RedirectResponse
    {
        $this->penugasanMengajarService->delete($penugasanMengajar);

        return redirect()->route('penugasan-mengajar.index')
            ->with('success', 'Penugasan mengajar berhasil dihapus.');
    }
}
