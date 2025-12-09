<?php

namespace App\Repositories;

use App\Models\JadwalMengajar;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class JadwalMengajarRepository
{
    public function __construct(
        protected JadwalMengajar $model
    ) {}

    /**
     * Get all jadwal mengajar with pagination
     */
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['penugasanMengajar.kelas', 'penugasanMengajar.mataPelajaran', 'penugasanMengajar.guru'])
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->paginate($perPage);
    }

    /**
     * Find jadwal mengajar by ID
     */
    public function findById(int $id): ?JadwalMengajar
    {
        return $this->model->with(['penugasanMengajar.kelas', 'penugasanMengajar.mataPelajaran', 'penugasanMengajar.guru'])
            ->find($id);
    }

    /**
     * Create new jadwal mengajar
     */
    public function create(array $data): JadwalMengajar
    {
        return $this->model->create($data);
    }

    /**
     * Update jadwal mengajar
     */
    public function update(JadwalMengajar $jadwalMengajar, array $data): bool
    {
        return $jadwalMengajar->update($data);
    }

    /**
     * Delete jadwal mengajar
     */
    public function delete(JadwalMengajar $jadwalMengajar): bool
    {
        return $jadwalMengajar->delete();
    }

    /**
     * Get jadwal by hari
     */
    public function getByHari(string $hari): Collection
    {
        return $this->model->with(['penugasanMengajar.kelas', 'penugasanMengajar.mataPelajaran', 'penugasanMengajar.guru'])
            ->byHari($hari)
            ->orderBy('jam_mulai')
            ->get();
    }

    /**
     * Get jadwal hari ini
     */
    public function getToday(): Collection
    {
        return $this->model->with(['penugasanMengajar.kelas', 'penugasanMengajar.mataPelajaran', 'penugasanMengajar.guru'])
            ->today()
            ->orderBy('jam_mulai')
            ->get();
    }

    /**
     * Get jadwal by penugasan mengajar
     */
    public function getByPenugasanMengajar(int $penugasanMengajarId): Collection
    {
        return $this->model->where('penugasan_mengajar_id', $penugasanMengajarId)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();
    }

    /**
     * Search jadwal mengajar
     */
    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['penugasanMengajar.kelas', 'penugasanMengajar.mataPelajaran', 'penugasanMengajar.guru'])
            ->whereHas('penugasanMengajar.kelas', function ($q) use ($query) {
                $q->where('nama_kelas', 'like', "%{$query}%");
            })
            ->orWhereHas('penugasanMengajar.mataPelajaran', function ($q) use ($query) {
                $q->where('nama_pelajaran', 'like', "%{$query}%");
            })
            ->orWhereHas('penugasanMengajar.guru', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->paginate($perPage);
    }
}
