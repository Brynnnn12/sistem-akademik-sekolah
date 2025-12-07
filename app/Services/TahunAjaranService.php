<?php

namespace App\Services;

use App\Models\TahunAjaran;
use App\Repositories\TahunAjaranRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TahunAjaranService
{
    public function __construct(
        protected TahunAjaranRepository $repository
    ) {}

    public function getPaginatedTahunAjaran(?string $search): LengthAwarePaginator
    {
        return $this->repository->getPaginated($search);
    }

    public function createTahunAjaran(array $data): TahunAjaran
    {
        return DB::transaction(function () use ($data) {
            // Logic: Jika user mencentang 'aktif', reset yang lain dulu
            if (!empty($data['aktif'])) {
                $this->repository->resetActiveStatus();
            }

            return $this->repository->create($data);
        });
    }

    public function updateTahunAjaran(TahunAjaran $tahunAjaran, array $data): TahunAjaran
    {
        return DB::transaction(function () use ($tahunAjaran, $data) {
            // Logic: Jika data diubah jadi aktif, reset yang lain
            // Tapi jika dia sudah aktif dan tetap aktif, tidak perlu reset ulang
            if (!empty($data['aktif']) && !$tahunAjaran->aktif) {
                $this->repository->resetActiveStatus();
            }

            return $this->repository->update($tahunAjaran, $data);
        });
    }

    public function setActiveTahunAjaran(TahunAjaran $tahunAjaran): TahunAjaran
    {
        return DB::transaction(function () use ($tahunAjaran) {
            $this->repository->resetActiveStatus();

            return $this->repository->update($tahunAjaran, ['aktif' => true]);
        });
    }

    public function deleteTahunAjaran(TahunAjaran $tahunAjaran): bool
    {
        if ($tahunAjaran->aktif) {
            throw new \Exception('Tidak dapat menghapus Tahun Ajaran yang sedang Aktif. Harap aktifkan tahun ajaran lain terlebih dahulu.');
        }

        return $this->repository->delete($tahunAjaran);
    }
}
