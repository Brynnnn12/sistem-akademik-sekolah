<?php

namespace App\Repositories;

use App\Models\Kelas;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class KelasRepository
{
    public function __construct(
        private Kelas $model
    ) {}

    public function getAllPaginated(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->with(['waliKelas', 'siswas']);

        if ($search) {
            $query->search($search);
        }

        return $query->orderBy('tingkat_kelas')
            ->orderBy('nama')
            ->paginate($perPage);
    }

    public function getAll(): Collection
    {
        return $this->model->with(['waliKelas', 'siswas'])
            ->orderBy('tingkat_kelas')
            ->orderBy('nama')
            ->get();
    }

    public function findById(int $id): ?Kelas
    {
        return $this->model->with(['waliKelas', 'siswas'])->find($id);
    }

    public function findByIdOrFail(int $id): Kelas
    {
        return $this->model->with(['waliKelas', 'siswas'])->findOrFail($id);
    }

    public function create(array $data): Kelas
    {
        return $this->model->create($data);
    }

    public function update(Kelas $kelas, array $data): bool
    {
        return $kelas->update($data);
    }

    public function delete(Kelas $kelas): bool
    {
        return $kelas->delete();
    }

    public function forceDelete(Kelas $kelas): bool
    {
        return $kelas->forceDelete();
    }

    public function restore(Kelas $kelas): bool
    {
        return $kelas->restore();
    }

    public function getByTingkat(int $tingkat): Collection
    {
        return $this->model->byTingkat($tingkat)
            ->with(['waliKelas', 'siswas'])
            ->orderBy('nama')
            ->get();
    }

    public function getByWaliKelas(int $waliKelasId): Collection
    {
        return $this->model->byWaliKelas($waliKelasId)
            ->with(['waliKelas', 'siswas'])
            ->orderBy('tingkat_kelas')
            ->orderBy('nama')
            ->get();
    }

    public function getAvailableWaliKelas(): Collection
    {
        return \App\Models\User::role('Guru')
            ->with(['kelasWali' => function ($query) {
                $query->select('id', 'nama', 'tingkat_kelas', 'wali_kelas_id');
            }])
            ->orderBy('name')
            ->get();
    }

    public function count(): int
    {
        return $this->model->count();
    }

    public function countByTingkat(): array
    {
        return $this->model->selectRaw('tingkat_kelas, COUNT(*) as total')
            ->groupBy('tingkat_kelas')
            ->orderBy('tingkat_kelas')
            ->pluck('total', 'tingkat_kelas')
            ->toArray();
    }
}
