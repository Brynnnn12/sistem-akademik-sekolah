<?php

namespace App\Repositories;

use App\Models\KelasSiswa;
use App\Models\Kelas;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class KelasSiswaRepository
{
    public function __construct(
        private KelasSiswa $model
    ) {}

    public function getAllPaginated(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        return $this->model
            ->with(['siswa', 'kelas', 'tahunAjaran'])
            ->when($search, function ($query, $search) {
                $query->whereHas('siswa', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%");
                })
                    ->orWhereHas('kelas', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getByKelas(int $kelasId, int $tahunAjaranId): Collection
    {
        return $this->model
            ->with(['siswa', 'tahunAjaran'])
            ->where('kelas_id', $kelasId)
            ->when($tahunAjaranId, function ($query, $tahunAjaranId) {
                $query->where('tahun_ajaran_id', $tahunAjaranId);
            })
            ->get();
    }

    public function getBySiswa(int $siswaId, int $tahunAjaranId): Collection
    {
        return $this->model
            ->with(['kelas', 'tahunAjaran'])
            ->where('siswa_id', $siswaId)
            ->when($tahunAjaranId, function ($query, $tahunAjaranId) {
                $query->where('tahun_ajaran_id', $tahunAjaranId);
            })
            ->get();
    }

    public function create(array $data): KelasSiswa
    {
        return $this->model->create($data);
    }

    public function findById(int $id): ?KelasSiswa
    {
        return $this->model->with(['siswa', 'kelas', 'tahunAjaran'])->find($id);
    }

    public function update(KelasSiswa $kelasSiswa, array $data): KelasSiswa
    {
        $kelasSiswa->update($data);
        return $kelasSiswa->fresh(['siswa', 'kelas', 'tahunAjaran']);
    }

    public function delete(KelasSiswa $kelasSiswa): bool
    {
        return $kelasSiswa->delete();
    }

    public function countByKelas(int $kelasId): int
    {
        return $this->model->where('kelas_id', $kelasId)->count();
    }

    public function isSiswaInKelas(int $siswaId, int $kelasId, int $tahunAjaranId): bool
    {
        return $this->model
            ->where('siswa_id', $siswaId)
            ->where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->exists();
    }

    public function getSiswaAvailableForKelas(int $kelasId, int $tahunAjaranId): Collection
    {
        // Get siswa yang belum ada di kelas manapun di tahun ajaran ini
        return \App\Models\Siswa::whereDoesntHave('kelas', function ($query) use ($tahunAjaranId) {
            $query->where('kelas_siswas.tahun_ajaran_id', $tahunAjaranId);
        })->get();
    }
}
