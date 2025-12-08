<?php

namespace App\Services;

use App\Models\Kelas;
use App\Repositories\KelasRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class KelasService
{
    public function __construct(
        private KelasRepository $kelasRepository
    ) {}

    public function getAllPaginated(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        return $this->kelasRepository->getAllPaginated($perPage, $search);
    }

    public function getAll(): Collection
    {
        return $this->kelasRepository->getAll();
    }

    public function findById(int $id): ?Kelas
    {
        return $this->kelasRepository->findById($id);
    }

    public function create(array $data): Kelas
    {
        return DB::transaction(function () use ($data) {
            // Check if wali kelas is already assigned to another class
            if (isset($data['wali_kelas_id'])) {
                $existingKelas = Kelas::where('wali_kelas_id', $data['wali_kelas_id'])
                    ->whereNull('deleted_at')
                    ->first();

                if ($existingKelas) {
                    throw ValidationException::withMessages([
                        'wali_kelas_id' => ['Guru ini sudah menjadi wali kelas di kelas lain.']
                    ]);
                }
            }

            return $this->kelasRepository->create($data);
        });
    }

    public function update(Kelas $kelas, array $data): Kelas
    {
        return DB::transaction(function () use ($kelas, $data) {
            // Check if wali kelas is already assigned to another class (excluding current class)
            if (isset($data['wali_kelas_id']) && $data['wali_kelas_id'] !== $kelas->wali_kelas_id) {
                $existingKelas = Kelas::where('wali_kelas_id', $data['wali_kelas_id'])
                    ->where('id', '!=', $kelas->id)
                    ->whereNull('deleted_at')
                    ->first();

                if ($existingKelas) {
                    throw ValidationException::withMessages([
                        'wali_kelas_id' => ['Guru ini sudah menjadi wali kelas di kelas lain.']
                    ]);
                }
            }

            $this->kelasRepository->update($kelas, $data);

            return $kelas->fresh(['waliKelas', 'siswas']);
        });
    }

    public function delete(Kelas $kelas): bool
    {
        return DB::transaction(function () use ($kelas) {
            // Check if class has students
            if ($kelas->siswas()->count() > 0) {
                throw ValidationException::withMessages([
                    'kelas' => ['Tidak dapat menghapus kelas yang masih memiliki siswa.']
                ]);
            }

            return $this->kelasRepository->delete($kelas);
        });
    }

    public function forceDelete(Kelas $kelas): bool
    {
        return $this->kelasRepository->forceDelete($kelas);
    }

    public function restore(Kelas $kelas): bool
    {
        return $this->kelasRepository->restore($kelas);
    }

    public function getByTingkat(int $tingkat): Collection
    {
        return $this->kelasRepository->getByTingkat($tingkat);
    }

    public function getByWaliKelas(int $waliKelasId): Collection
    {
        return $this->kelasRepository->getByWaliKelas($waliKelasId);
    }

    public function getAvailableWaliKelas(): Collection
    {
        return $this->kelasRepository->getAvailableWaliKelas();
    }

    public function getStatistics(): array
    {
        $totalKelas = $this->kelasRepository->count();
        $kelasByTingkat = $this->kelasRepository->countByTingkat();

        return [
            'total_kelas' => $totalKelas,
            'kelas_by_tingkat' => $kelasByTingkat,
        ];
    }
}
