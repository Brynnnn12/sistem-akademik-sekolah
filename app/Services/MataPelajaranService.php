<?php

namespace App\Services;

use App\Models\MataPelajaran;
use App\Repositories\MataPelajaranRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MataPelajaranService
{
    public function __construct(
        protected MataPelajaranRepository $repository
    ) {}

    public function getPaginated(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        return $this->repository->getPaginated($perPage, $search);
    }

    public function create(array $data): MataPelajaran
    {
        return $this->repository->create($data);
    }

    public function update(MataPelajaran $mataPelajaran, array $data): bool
    {
        return $this->repository->update($mataPelajaran, $data);
    }

    public function delete(MataPelajaran $mataPelajaran): bool
    {
        return $this->repository->delete($mataPelajaran);
    }
}
