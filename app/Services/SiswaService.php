<?php

namespace App\Services;

use App\Models\Siswa;
use App\Repositories\SiswaRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SiswaService
{
    public function __construct(
        protected SiswaRepository $repository
    ) {}

    public function getPaginated(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        return $this->repository->getPaginated($perPage, $search);
    }

    public function findById(int $id): ?Siswa
    {
        return $this->repository->findById($id);
    }

    public function findByNis(string $nis): ?Siswa
    {
        return $this->repository->findByNis($nis);
    }

    public function findByNisn(string $nisn): ?Siswa
    {
        return $this->repository->findByNisn($nisn);
    }

    public function create(array $data): Siswa
    {
        return $this->repository->create($data);
    }

    public function update(Siswa $siswa, array $data): bool
    {
        return $this->repository->update($siswa, $data);
    }

    public function delete(Siswa $siswa): bool
    {
        return $this->repository->delete($siswa);
    }

    public function getByJenisKelamin(string $jenisKelamin): Collection
    {
        return $this->repository->getByJenisKelamin($jenisKelamin);
    }

    public function searchSiswa(string $query): Collection
    {
        return Siswa::where('nama', 'like', '%' . $query . '%')
            ->orWhere('nis', 'like', '%' . $query . '%')
            ->orWhere('nisn', 'like', '%' . $query . '%')
            ->get();
    }
}
