<?php

namespace App\Repositories;

use App\Models\MataPelajaran;
use Illuminate\Pagination\LengthAwarePaginator;

class MataPelajaranRepository
{
    public function getPaginated(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        return MataPelajaran::query()
            ->search($search) // Menggunakan Scope di Model (DRY)
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): ?MataPelajaran
    {
        return MataPelajaran::find($id);
    }

    public function create(array $data): MataPelajaran
    {
        return MataPelajaran::create($data);
    }

    public function update(MataPelajaran $mataPelajaran, array $data): bool
    {
        return $mataPelajaran->update($data);
    }

    public function delete(MataPelajaran $mataPelajaran): bool
    {
        return $mataPelajaran->delete();
    }
}
