<?php

namespace App\Services;

use App\Models\JadwalMengajar;
use App\Repositories\JadwalMengajarRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class JadwalMengajarService
{
    public function __construct(
        protected JadwalMengajarRepository $repository
    ) {}

    /**
     * Get all jadwal mengajar with pagination
     */
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getAllPaginated($perPage);
    }

    /**
     * Find jadwal mengajar by ID
     */
    public function findById(int $id): ?JadwalMengajar
    {
        return $this->repository->findById($id);
    }

    /**
     * Create new jadwal mengajar
     */
    public function create(array $data): JadwalMengajar
    {
        DB::beginTransaction();
        try {
            $jadwalMengajar = $this->repository->create($data);
            DB::commit();
            return $jadwalMengajar;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update jadwal mengajar
     */
    public function update(JadwalMengajar $jadwalMengajar, array $data): bool
    {
        DB::beginTransaction();
        try {
            $result = $this->repository->update($jadwalMengajar, $data);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete jadwal mengajar
     */
    public function delete(JadwalMengajar $jadwalMengajar): bool
    {
        DB::beginTransaction();
        try {
            $result = $this->repository->delete($jadwalMengajar);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get jadwal by hari
     */
    public function getByHari(string $hari): Collection
    {
        return $this->repository->getByHari($hari);
    }

    /**
     * Get jadwal hari ini
     */
    public function getToday(): Collection
    {
        return $this->repository->getToday();
    }

    /**
     * Get jadwal by penugasan mengajar
     */
    public function getByPenugasanMengajar(int $penugasanMengajarId): Collection
    {
        return $this->repository->getByPenugasanMengajar($penugasanMengajarId);
    }

    /**
     * Search jadwal mengajar
     */
    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->search($query, $perPage);
    }

    /**
     * Validasi jadwal tidak bentrok
     */
    public function validateScheduleConflict(array $data, ?int $excludeId = null, ?JadwalMengajar $existingJadwal = null): bool
    {
        // Untuk update, jika field tidak ada dalam data, gunakan dari existing jadwal
        $hari = $data['hari'] ?? ($existingJadwal ? $existingJadwal->hari : null);
        $jamMulai = $data['jam_mulai'] ?? ($existingJadwal ? $existingJadwal->jam_mulai : null);
        $jamSelesai = $data['jam_selesai'] ?? ($existingJadwal ? $existingJadwal->jam_selesai : null);
        $penugasanMengajarId = $data['penugasan_mengajar_id'] ?? ($existingJadwal ? $existingJadwal->penugasan_mengajar_id : null);

        if (!$hari || !$jamMulai || !$jamSelesai || !$penugasanMengajarId) {
            return false; // Tidak ada konflik jika data tidak lengkap
        }

        $query = JadwalMengajar::where('hari', $hari)
            ->where('penugasan_mengajar_id', $penugasanMengajarId)
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->whereBetween('jam_mulai', [$jamMulai, $jamSelesai])
                    ->orWhereBetween('jam_selesai', [$jamMulai, $jamSelesai])
                    ->orWhere(function ($q2) use ($jamMulai, $jamSelesai) {
                        $q2->where('jam_mulai', '<=', $jamMulai)
                            ->where('jam_selesai', '>=', $jamSelesai);
                    });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
