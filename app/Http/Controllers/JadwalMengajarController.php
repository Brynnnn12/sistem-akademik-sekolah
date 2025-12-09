<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJadwalMengajarRequest;
use App\Http\Requests\UpdateJadwalMengajarRequest;
use App\Models\JadwalMengajar;
use App\Models\PenugasanMengajar;
use App\Services\JadwalMengajarService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class JadwalMengajarController extends Controller
{
    public function __construct(
        protected JadwalMengajarService $service
    ) {
        $this->authorizeResource(JadwalMengajar::class, 'jadwal_mengajar');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $hari = $request->get('hari');

        if ($search) {
            $jadwalMengajars = $this->service->search($search);
        } elseif ($hari) {
            $jadwalMengajars = $this->service->getByHari($hari);
        } else {
            $jadwalMengajars = $this->service->getAllPaginated();
        }

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return view('dashboard.jadwal-mengajar.index', compact('jadwalMengajars', 'hariList', 'search', 'hari'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $penugasanMengajars = PenugasanMengajar::with(['kelas', 'mataPelajaran', 'guru'])
            ->join('kelas', 'penugasan_mengajars.kelas_id', '=', 'kelas.id')
            ->join('mata_pelajarans', 'penugasan_mengajars.mata_pelajaran_id', '=', 'mata_pelajarans.id')
            ->orderBy('kelas.nama')
            ->orderBy('mata_pelajarans.nama')
            ->select('penugasan_mengajars.*')
            ->get();

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return view('dashboard.jadwal-mengajar.create', compact('penugasanMengajars', 'hariList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJadwalMengajarRequest $request): RedirectResponse
    {
        // Validasi konflik jadwal
        if ($this->service->validateScheduleConflict($request->validated())) {
            return back()
                ->withInput()
                ->withErrors(['jadwal' => 'Jadwal mengajar bentrok dengan jadwal lain.']);
        }

        try {
            $this->service->create($request->validated());

            return redirect()
                ->route('dashboard.jadwal-mengajar.index')
                ->with('success', 'Jadwal mengajar berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan jadwal mengajar.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(JadwalMengajar $jadwalMengajar): View
    {
        $jadwalMengajar->load(['penugasanMengajar.kelas', 'penugasanMengajar.mataPelajaran', 'penugasanMengajar.guru']);

        return view('dashboard.jadwal-mengajar.show', compact('jadwalMengajar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JadwalMengajar $jadwalMengajar): View
    {
        $jadwalMengajar->load(['penugasanMengajar.kelas', 'penugasanMengajar.mataPelajaran', 'penugasanMengajar.guru']);

        $penugasanMengajars = PenugasanMengajar::with(['kelas', 'mataPelajaran', 'guru'])
            ->join('kelas', 'penugasan_mengajars.kelas_id', '=', 'kelas.id')
            ->join('mata_pelajarans', 'penugasan_mengajars.mata_pelajaran_id', '=', 'mata_pelajarans.id')
            ->orderBy('kelas.nama')
            ->orderBy('mata_pelajarans.nama')
            ->select('penugasan_mengajars.*')
            ->get();

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return view('dashboard.jadwal-mengajar.edit', compact('jadwalMengajar', 'penugasanMengajars', 'hariList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJadwalMengajarRequest $request, JadwalMengajar $jadwalMengajar): RedirectResponse
    {
        // Validasi konflik jadwal
        if ($this->service->validateScheduleConflict($request->validated(), $jadwalMengajar->id, $jadwalMengajar)) {
            return back()
                ->withInput()
                ->withErrors(['jadwal' => 'Jadwal mengajar bentrok dengan jadwal lain.']);
        }

        try {
            $this->service->update($jadwalMengajar, $request->validated());

            return redirect()
                ->route('dashboard.jadwal-mengajar.index')
                ->with('success', 'Jadwal mengajar berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui jadwal mengajar.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JadwalMengajar $jadwalMengajar): RedirectResponse
    {
        try {
            $this->service->delete($jadwalMengajar);

            return redirect()
                ->route('dashboard.jadwal-mengajar.index')
                ->with('success', 'Jadwal mengajar berhasil dihapus.');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menghapus jadwal mengajar.']);
        }
    }
}
