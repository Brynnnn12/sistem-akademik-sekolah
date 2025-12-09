<?php

namespace App\Services;

use App\Models\KelasSiswa;
use App\Repositories\KelasSiswaRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RombelService
{
    public function __construct(
        private KelasSiswaRepository $kelasSiswaRepository
    ) {}

    public function assignSiswaToKelas(int $siswaId, int $kelasId, int $tahunAjaranId): KelasSiswa
    {
        return DB::transaction(function () use ($siswaId, $kelasId, $tahunAjaranId) {
            // Validasi: Siswa sudah ada di kelas lain di tahun yang sama?
            if ($this->kelasSiswaRepository->isSiswaInKelas($siswaId, $kelasId, $tahunAjaranId)) {
                throw ValidationException::withMessages([
                    'siswa_id' => ['Siswa sudah terdaftar di kelas ini untuk tahun ajaran yang dipilih.']
                ]);
            }

            // Validasi: Siswa sudah ada di kelas manapun di tahun yang sama?
            $existing = KelasSiswa::where('siswa_id', $siswaId)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->first();

            if ($existing) {
                throw ValidationException::withMessages([
                    'siswa_id' => ['Siswa sudah terdaftar di kelas lain untuk tahun ajaran yang dipilih.']
                ]);
            }

            return $this->kelasSiswaRepository->create([
                'siswa_id' => $siswaId,
                'kelas_id' => $kelasId,
                'tahun_ajaran_id' => $tahunAjaranId
            ]);
        });
    }

    public function removeSiswaFromKelas(int $kelasSiswaId): bool
    {
        $kelasSiswa = $this->kelasSiswaRepository->findById($kelasSiswaId);

        if (!$kelasSiswa) {
            throw ValidationException::withMessages([
                'kelas_siswa' => ['Data siswa di kelas tidak ditemukan.']
            ]);
        }

        return $this->kelasSiswaRepository->delete($kelasSiswa);
    }

    public function kenaikanKelas(int $siswaId, int $kelasBaruId, int $tahunAjaranId): KelasSiswa
    {
        return DB::transaction(function () use ($siswaId, $kelasBaruId, $tahunAjaranId) {
            // Hapus dari kelas lama di tahun ajaran ini
            KelasSiswa::where('siswa_id', $siswaId)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->delete();

            // Assign ke kelas baru
            return $this->assignSiswaToKelas($siswaId, $kelasBaruId, $tahunAjaranId);
        });
    }

    public function mutasiKelas(int $siswaId, int $kelasBaruId, int $tahunAjaranId): KelasSiswa
    {
        // Sama dengan kenaikan kelas, tapi untuk perpindahan antar kelas
        return $this->kenaikanKelas($siswaId, $kelasBaruId, $tahunAjaranId);
    }

    public function getSiswaByKelas(int $kelasId, int $tahunAjaranId)
    {
        return $this->kelasSiswaRepository->getByKelas($kelasId, $tahunAjaranId);
    }

    public function getKelasBySiswa(int $siswaId, int $tahunAjaranId)
    {
        return $this->kelasSiswaRepository->getBySiswa($siswaId, $tahunAjaranId);
    }

    public function getSiswaAvailableForKelas(int $kelasId, int $tahunAjaranId)
    {
        return $this->kelasSiswaRepository->getSiswaAvailableForKelas($kelasId, $tahunAjaranId);
    }

    /**
     * Bulk assign multiple siswa to kelas.
     */
    public function bulkAssignSiswaToKelas(array $siswaIds, int $kelasId, int $tahunAjaranId): array
    {
        return DB::transaction(function () use ($siswaIds, $kelasId, $tahunAjaranId) {
            $results = [
                'success' => [],
                'errors' => []
            ];

            foreach ($siswaIds as $siswaId) {
                try {
                    $kelasSiswa = $this->assignSiswaToKelas($siswaId, $kelasId, $tahunAjaranId);
                    $results['success'][] = $kelasSiswa;
                } catch (\Exception $e) {
                    $results['errors'][] = [
                        'siswa_id' => $siswaId,
                        'message' => $e->getMessage()
                    ];
                }
            }

            return $results;
        });
    }
}
