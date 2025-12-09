<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Services\PromotionService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PromotionController extends Controller
{
    protected PromotionService $promotionService;

    public function __construct(PromotionService $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    /**
     * Show the promotion form.
     */
    public function promotionForm(): View
    {
        $tahunAjarans = TahunAjaran::all();
        $kelas = Kelas::all();

        return view('dashboard.promotion.form', compact('tahunAjarans', 'kelas'));
    }

    /**
     * Get students for promotion based on source class and year.
     */
    public function getStudentsForPromotion(Request $request)
    {
        $request->validate([
            'source_tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'source_kelas_id' => 'required|exists:kelas,id',
        ]);

        $siswa = Siswa::whereHas('kelas', function ($query) use ($request) {
            $query->where('kelas.id', $request->source_kelas_id)
                ->where('kelas_siswas.tahun_ajaran_id', $request->source_tahun_ajaran_id);
        })->get();

        return response()->json($siswa);
    }

    /**
     * Process student promotion.
     */
    public function promote(Request $request): RedirectResponse
    {
        $request->validate([
            'siswa_ids' => 'required|array',
            'siswa_ids.*' => 'exists:siswas,id',
            'target_tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'source_kelas_id' => 'required|exists:kelas,id',
            'source_tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'target_kelas_id' => 'nullable|exists:kelas,id',
        ]);

        $sourceKelas = Kelas::find($request->source_kelas_id);
        $targetKelas = $request->target_kelas_id ? Kelas::find($request->target_kelas_id) : null;

        // If not graduation (grade 6), validate that target class grade is exactly source grade + 1
        if ($sourceKelas->tingkat_kelas !== 6) {
            if (!$targetKelas) {
                return redirect()->back()->with('error', 'Kelas tujuan harus dipilih untuk kenaikan kelas.');
            }
            if ($targetKelas->tingkat_kelas !== $sourceKelas->tingkat_kelas + 1) {
                return redirect()->back()->with('error', 'Kelas tujuan harus memiliki tingkat yang sesuai dengan kelas asal + 1.');
            }
        } elseif ($targetKelas) {
            // If it's graduation, target class should not be provided
            return redirect()->back()->with('error', 'Untuk kelulusan, kelas tujuan tidak boleh dipilih.');
        }

        // If graduation (grade 6), call graduate method
        if ($sourceKelas->tingkat_kelas === 6) {
            $this->promotionService->graduateStudents($request->siswa_ids);
            return redirect()->back()->with('success', 'Siswa berhasil diluluskan.');
        }

        // For regular promotion
        $this->promotionService->promoteStudents(
            $request->siswa_ids,
            $request->target_kelas_id,
            $request->target_tahun_ajaran_id
        );

        return redirect()->back()->with('success', 'Siswa berhasil dinaikkan kelas.');
    }

    /**
     * Show the graduation form.
     */
    public function graduationForm(): View
    {
        $tahunAjarans = TahunAjaran::all();
        $kelas = Kelas::where('tingkat_kelas', 6)->get();

        return view('dashboard.graduation.form', compact('tahunAjarans', 'kelas'));
    }

    /**
     * Get students for graduation based on final class.
     */
    public function getStudentsForGraduation(Request $request)
    {
        $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $siswa = Siswa::whereHas('kelas', function ($query) use ($request) {
            $query->where('kelas.id', $request->kelas_id)
                ->where('kelas_siswas.tahun_ajaran_id', $request->tahun_ajaran_id);
        })->where('status', 'aktif')->get();

        return response()->json($siswa);
    }

    /**
     * Process student graduation.
     */
    public function graduate(Request $request): RedirectResponse
    {
        $request->validate([
            'siswa_ids' => 'required|array',
            'siswa_ids.*' => 'exists:siswas,id',
        ]);

        $this->promotionService->graduateStudents($request->siswa_ids);

        return redirect()->back()->with('success', 'Siswa berhasil diluluskan.');
    }
}
