<?php

namespace App\Repositories;

use App\Models\PenugasanMengajar;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PenugasanMengajarRepository
{
    public function __construct(
        private PenugasanMengajar $model
    ) {}

    public function getAllPaginated(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->with(['guru', 'kelas', 'mataPelajaran', 'tahunAjaran', 'jadwalMengajars']);

        if ($search) {
            $query->search($search);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getAll(): Collection
    {
        return $this->model->with(['guru', 'kelas', 'mataPelajaran', 'tahunAjaran', 'jadwalMengajars'])->get();
    }

    public function findById(int $id): ?PenugasanMengajar
    {
        return $this->model->with(['guru', 'kelas', 'mataPelajaran', 'tahunAjaran'])->find($id);
    }

    public function create(array $data): PenugasanMengajar
    {
        return $this->model->create($data);
    }

    public function update(PenugasanMengajar $penugasanMengajar, array $data): PenugasanMengajar
    {
        $penugasanMengajar->update($data);
        return $penugasanMengajar->fresh(['guru', 'kelas', 'mataPelajaran', 'tahunAjaran']);
    }

    public function delete(PenugasanMengajar $penugasanMengajar): bool
    {
        return $penugasanMengajar->delete();
    }

    public function forceDelete(PenugasanMengajar $penugasanMengajar): bool
    {
        return $penugasanMengajar->forceDelete();
    }

    public function restore(PenugasanMengajar $penugasanMengajar): bool
    {
        return $penugasanMengajar->restore();
    }

    public function getByGuru(int $guruId): Collection
    {
        return $this->model->with(['guru', 'kelas', 'mataPelajaran', 'tahunAjaran'])
            ->byGuru($guruId)
            ->get();
    }

    public function getByKelas(int $kelasId): Collection
    {
        return $this->model->with(['guru', 'kelas', 'mataPelajaran', 'tahunAjaran'])
            ->byKelas($kelasId)
            ->get();
    }

    public function getByMataPelajaran(int $mataPelajaranId): Collection
    {
        return $this->model->with(['guru', 'kelas', 'mataPelajaran', 'tahunAjaran'])
            ->byMataPelajaran($mataPelajaranId)
            ->get();
    }

    public function getByTahunAjaran(int $tahunAjaranId): Collection
    {
        return $this->model->with(['guru', 'kelas', 'mataPelajaran', 'tahunAjaran'])
            ->byTahunAjaran($tahunAjaranId)
            ->get();
    }

    public function getAktif(): Collection
    {
        return $this->model->with(['guru', 'kelas', 'mataPelajaran', 'tahunAjaran'])
            ->aktif()
            ->get();
    }

    public function count(): int
    {
        return $this->model->count();
    }

    public function countAktif(): int
    {
        return $this->model->aktif()->count();
    }

    public function isGuruAssignedToKelasMataPelajaran(int $guruId, int $kelasId, int $mataPelajaranId, int $tahunAjaranId): bool
    {
        return $this->model->where('guru_id', $guruId)
            ->where('kelas_id', $kelasId)
            ->where('mata_pelajaran_id', $mataPelajaranId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->exists();
    }

    public function getAvailableGuruForKelasMataPelajaran(int $kelasId, int $mataPelajaranId, int $tahunAjaranId): Collection
    {
        return $this->model->with('guru')
            ->where('kelas_id', $kelasId)
            ->where('mata_pelajaran_id', $mataPelajaranId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->get()
            ->pluck('guru');
    }
}
