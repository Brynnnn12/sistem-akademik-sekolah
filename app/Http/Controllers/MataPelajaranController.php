<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Http\Requests\StoreMataPelajaranRequest;
use App\Http\Requests\UpdateMataPelajaranRequest;
use App\Services\MataPelajaranService;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function __construct(
        protected MataPelajaranService $service
    ) {
        $this->authorizeResource(MataPelajaran::class, 'mata_pelajaran');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $mataPelajarans = $this->service->getPaginated(
            $request->input('per_page', 10),
            $request->input('search')
        );

        return view('dashboard.mata-pelajaran.index', [
            'mataPelajarans' => $mataPelajarans,
            'search' => $request->input('search')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.mata-pelajaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMataPelajaranRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->route('mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MataPelajaran $mataPelajaran)
    {
        return view('dashboard.mata-pelajaran.show', compact('mataPelajaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MataPelajaran $mataPelajaran)
    {
        return view('dashboard.mata-pelajaran.edit', compact('mataPelajaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMataPelajaranRequest $request, MataPelajaran $mataPelajaran)
    {
        $this->service->update($mataPelajaran, $request->validated());

        return redirect()->route('mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MataPelajaran $mataPelajaran)
    {
        $this->service->delete($mataPelajaran);

        return redirect()->route('mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
