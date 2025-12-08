<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Services\SiswaService;
use App\Http\Requests\StoreSiswaRequest;
use App\Http\Requests\UpdateSiswaRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SiswaController extends Controller
{
    protected SiswaService $siswaService;

    public function __construct(SiswaService $siswaService)
    {
        $this->authorizeResource(Siswa::class, 'siswa');
        $this->siswaService = $siswaService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $siswas = $this->siswaService->getPaginated(10, request()->query('search'));

        return view('dashboard.siswa.index', compact('siswas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('dashboard.siswa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSiswaRequest $request): RedirectResponse
    {
        $this->siswaService->create($request->validated());

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa): View
    {
        return view('dashboard.siswa.show', compact('siswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa): View
    {
        return view('dashboard.siswa.edit', compact('siswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSiswaRequest $request, Siswa $siswa): RedirectResponse
    {
        $this->siswaService->update($siswa, $request->validated());

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa): RedirectResponse
    {
        $this->siswaService->delete($siswa);

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil dihapus.');
    }
}
