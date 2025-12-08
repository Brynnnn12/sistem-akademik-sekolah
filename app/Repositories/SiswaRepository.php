<?php

namespace App\Repositories;

use App\Models\Siswa;
use Illuminate\Pagination\LengthAwarePaginator;

class SiswaRepository
{
    public function getPaginated(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        return Siswa::query()
            ->search($search)
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): ?Siswa
    {
        return Siswa::find($id);
    }

    public function findByNis(string $nis): ?Siswa
    {
        return Siswa::where('nis', $nis)->first();
    }

    public function findByNisn(string $nisn): ?Siswa
    {
        return Siswa::where('nisn', $nisn)->first();
    }

    public function create(array $data): Siswa
    {
        return Siswa::create($data);
    }

    public function update(Siswa $siswa, array $data): bool
    {
        return $siswa->update($data);
    }

    public function delete(Siswa $siswa): bool
    {
        return $siswa->delete();
    }

    public function getByJenisKelamin(string $jenisKelamin): \Illuminate\Database\Eloquent\Collection
    {
        return Siswa::byJenisKelamin($jenisKelamin)->get();
    }
}
