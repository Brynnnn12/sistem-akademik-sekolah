<?php

namespace App\Repositories;

use App\Models\TahunAjaran;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TahunAjaranRepository
{
    /**
     * Mengambil data dengan paginasi dan opsi pencarian.
     * Menggunakan 'when' agar kode lebih clean.
     */
    public function getPaginated(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return TahunAjaran::query()
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%");
            })
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function create(array $data): TahunAjaran
    {
        return TahunAjaran::create($data);
    }

    public function update(TahunAjaran $tahunAjaran, array $data): TahunAjaran
    {
        $tahunAjaran->update($data);
        return $tahunAjaran->refresh();
    }

    public function delete(TahunAjaran $tahunAjaran): bool
    {
        return (bool) $tahunAjaran->delete();
    }

    /**
     * Menonaktifkan semua tahun ajaran yang sedang aktif.
     */
    public function resetActiveStatus(): void
    {
        // Update massal lebih efisien daripada looping
        TahunAjaran::where('aktif', true)->update(['aktif' => false]);
    }

    public function isExistActive(): bool
    {
        return TahunAjaran::where('aktif', true)->exists();
    }
}
