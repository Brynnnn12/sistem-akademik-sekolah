<?php

namespace App\Services;

use App\Models\PenugasanMengajar;
use App\Models\User;
use App\Repositories\PenugasanMengajarRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PenugasanMengajarService
{
    public function __construct(
        private PenugasanMengajarRepository $penugasanMengajarRepository
    ) {}

    public function getAllPaginated(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        return $this->penugasanMengajarRepository->getAllPaginated($perPage, $search);
    }

    public function getAll(): Collection
    {
        return $this->penugasanMengajarRepository->getAll();
    }

    public function findById(int $id): ?PenugasanMengajar
    {
        return $this->penugasanMengajarRepository->findById($id);
    }

    public function create(array $data): PenugasanMengajar
    {
        return DB::transaction(function () use ($data) {
            // Check if guru has the 'Guru' role
            $guru = User::find($data['guru_id']);
            if (!$guru || !$guru->hasRole('Guru')) {
                throw ValidationException::withMessages([
                    'guru_id' => ['User yang dipilih harus memiliki peran sebagai guru.']
                ]);
            }

            // Check if assignment already exists for this guru, kelas, mata pelajaran, and tahun ajaran
            if ($this->penugasanMengajarRepository->isGuruAssignedToKelasMataPelajaran(
                $data['guru_id'],
                $data['kelas_id'],
                $data['mata_pelajaran_id'],
                $data['tahun_ajaran_id']
            )) {
                throw ValidationException::withMessages([
                    'assignment' => ['Guru ini sudah ditugaskan untuk mengajar mata pelajaran ini di kelas ini untuk tahun ajaran ini.']
                ]);
            }

            return $this->penugasanMengajarRepository->create($data);
        });
    }

    public function update(PenugasanMengajar $penugasanMengajar, array $data): PenugasanMengajar
    {
        return DB::transaction(function () use ($penugasanMengajar, $data) {
            // Check if guru has the 'guru' role
            if (isset($data['guru_id'])) {
                $guru = User::find($data['guru_id']);
                if (!$guru || !$guru->hasRole('Guru')) {
                    throw ValidationException::withMessages([
                        'guru_id' => ['User yang dipilih harus memiliki peran sebagai guru.']
                    ]);
                }
            }

            // Check if assignment already exists for this guru, kelas, mata pelajaran, and tahun ajaran
            // (excluding current assignment)
            if (isset($data['guru_id']) || isset($data['kelas_id']) || isset($data['mata_pelajaran_id']) || isset($data['tahun_ajaran_id'])) {
                $guruId = $data['guru_id'] ?? $penugasanMengajar->guru_id;
                $kelasId = $data['kelas_id'] ?? $penugasanMengajar->kelas_id;
                $mataPelajaranId = $data['mata_pelajaran_id'] ?? $penugasanMengajar->mata_pelajaran_id;
                $tahunAjaranId = $data['tahun_ajaran_id'] ?? $penugasanMengajar->tahun_ajaran_id;

                $existing = $this->penugasanMengajarRepository->isGuruAssignedToKelasMataPelajaran(
                    $guruId,
                    $kelasId,
                    $mataPelajaranId,
                    $tahunAjaranId
                );

                if (
                    $existing && $penugasanMengajar->id !== PenugasanMengajar::where('guru_id', $guruId)
                    ->where('kelas_id', $kelasId)
                    ->where('mata_pelajaran_id', $mataPelajaranId)
                    ->where('tahun_ajaran_id', $tahunAjaranId)
                    ->first()?->id
                ) {
                    throw ValidationException::withMessages([
                        'assignment' => ['Guru ini sudah ditugaskan untuk mengajar mata pelajaran ini di kelas ini untuk tahun ajaran ini.']
                    ]);
                }
            }

            return $this->penugasanMengajarRepository->update($penugasanMengajar, $data);
        });
    }

    public function delete(PenugasanMengajar $penugasanMengajar): bool
    {
        return $this->penugasanMengajarRepository->delete($penugasanMengajar);
    }

    public function forceDelete(PenugasanMengajar $penugasanMengajar): bool
    {
        return $this->penugasanMengajarRepository->forceDelete($penugasanMengajar);
    }

    public function restore(PenugasanMengajar $penugasanMengajar): bool
    {
        return $this->penugasanMengajarRepository->restore($penugasanMengajar);
    }

    public function getByGuru(int $guruId): Collection
    {
        return $this->penugasanMengajarRepository->getByGuru($guruId);
    }

    public function getByKelas(int $kelasId): Collection
    {
        return $this->penugasanMengajarRepository->getByKelas($kelasId);
    }

    public function getByMataPelajaran(int $mataPelajaranId): Collection
    {
        return $this->penugasanMengajarRepository->getByMataPelajaran($mataPelajaranId);
    }

    public function getByTahunAjaran(int $tahunAjaranId): Collection
    {
        return $this->penugasanMengajarRepository->getByTahunAjaran($tahunAjaranId);
    }

    public function getAktif(): Collection
    {
        return $this->penugasanMengajarRepository->getAktif();
    }

    public function getAvailableGuru(): Collection
    {
        return User::role('Guru')->get();
    }

    public function getStatistics(): array
    {
        $totalPenugasan = $this->penugasanMengajarRepository->count();
        $totalAktif = $this->penugasanMengajarRepository->countAktif();

        return [
            'total_penugasan' => $totalPenugasan,
            'total_aktif' => $totalAktif,
        ];
    }
}
