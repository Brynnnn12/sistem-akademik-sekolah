<?php

namespace App\Repositories;

use App\Models\KomponenNilai;
use App\Models\NilaiSiswa;
use App\Models\PenugasanMengajar;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class NilaiRepository
{
    /**
     * Get komponen nilai by penugasan mengajar
     */
    public function getKomponenNilaiByPenugasan(int $penugasanMengajarId): Collection
    {
        return KomponenNilai::where('penugasan_mengajar_id', $penugasanMengajarId)
            ->withCount(['nilaiSiswas as nilai_siswas_count'])
            ->with(['nilaiSiswas' => function ($query) {
                $query->selectRaw('komponen_nilai_id, AVG(nilai) as nilai_siswas_avg')
                    ->groupBy('komponen_nilai_id');
            }])
            ->orderBy('created_at')
            ->get()
            ->map(function ($komponen) {
                $komponen->nilai_siswas_avg = $komponen->nilaiSiswas->first()?->nilai_siswas_avg ?? 0;
                return $komponen;
            });
    }

    /**
     * Get nilai siswa by komponen nilai
     */
    public function getNilaiSiswaByKomponen(int $komponenNilaiId): Collection
    {
        return NilaiSiswa::where('komponen_nilai_id', $komponenNilaiId)
            ->with('siswa')
            ->get();
    }

    /**
     * Get all nilai siswa untuk leger (per kelas, mapel, tahun ajaran)
     */
    public function getNilaiForLeger(int $kelasId, int $mataPelajaranId, int $tahunAjaranId): Collection
    {
        return NilaiSiswa::whereHas('komponen_nilai.penugasan_mengajar', function ($query) use ($kelasId, $mataPelajaranId, $tahunAjaranId) {
            $query->where('kelas_id', $kelasId)
                ->where('mata_pelajaran_id', $mataPelajaranId)
                ->where('tahun_ajaran_id', $tahunAjaranId);
        })
            ->with(['siswa', 'komponen_nilai'])
            ->get()
            ->groupBy('siswa_id');
    }

    /**
     * Store komponen nilai baru
     */
    public function storeKomponenNilai(array $data): KomponenNilai
    {
        return KomponenNilai::create($data);
    }

    /**
     * Store nilai siswa (massal)
     */
    public function storeNilaiSiswaMassal(int $komponenNilaiId, array $nilaiData): void
    {
        foreach ($nilaiData as $siswaId => $nilai) {
            if ($nilai !== null && $nilai !== '') {
                NilaiSiswa::updateOrCreate(
                    [
                        'komponen_nilai_id' => $komponenNilaiId,
                        'siswa_id' => $siswaId
                    ],
                    ['nilai' => $nilai]
                );
            }
        }
    }

    /**
     * Delete komponen nilai
     */
    public function deleteKomponenNilai(int $id): bool
    {
        $komponen = KomponenNilai::findOrFail($id);
        return $komponen->delete();
    }

    /**
     * Get penugasan mengajar untuk guru tertentu
     */
    public function getPenugasanMengajarByGuru(int $guruId)
    {
        return PenugasanMengajar::where('guru_id', $guruId)
            ->with(['kelas', 'mataPelajaran', 'tahunAjaran'])
            ->paginate(10);
    }
}
