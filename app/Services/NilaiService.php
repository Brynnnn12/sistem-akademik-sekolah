<?php

namespace App\Services;

use App\Models\KomponenNilai;
use App\Models\NilaiSiswa;
use App\Models\PenugasanMengajar;
use App\Repositories\NilaiRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class NilaiService
{
    public function __construct(
        protected NilaiRepository $nilaiRepository
    ) {}
    /**
     * Get komponen nilai by penugasan mengajar
     */
    public function getKomponenNilaiByPenugasan(int $penugasanMengajarId): Collection
    {
        return $this->nilaiRepository->getKomponenNilaiByPenugasan($penugasanMengajarId);
    }

    /**
     * Create komponen nilai baru
     */
    public function createKomponenNilai(array $data): KomponenNilai
    {
        // Validasi bobot total tidak boleh lebih dari 100 per jenis
        $this->validateBobotKomponen($data['penugasan_mengajar_id'], $data['jenis'], $data['bobot']);

        return $this->nilaiRepository->storeKomponenNilai($data);
    }

    /**
     * Store nilai siswa massal
     */
    public function storeNilaiSiswaMassal(int $komponenNilaiId, array $nilaiData): void
    {
        DB::transaction(function () use ($komponenNilaiId, $nilaiData) {
            // Store nilai siswa
            $this->nilaiRepository->storeNilaiSiswaMassal($komponenNilaiId, $nilaiData);
        });
    }

    /**
     * Hitung nilai akhir otomatis berdasarkan komponen nilai
     */
    /**
     * Validasi bobot komponen
     */
    protected function validateBobotKomponen(int $penugasanMengajarId, string $jenis, int $bobotBaru): void
    {
        $totalBobot = KomponenNilai::where('penugasan_mengajar_id', $penugasanMengajarId)
            ->where('jenis', $jenis)
            ->sum('bobot');

        if ($totalBobot + $bobotBaru > 100) {
            throw new \InvalidArgumentException("Total bobot untuk jenis {$jenis} tidak boleh lebih dari 100%");
        }
    }
}
