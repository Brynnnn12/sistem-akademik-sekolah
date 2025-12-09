<?php

namespace App\Repositories;

use App\Models\NilaiAkhir;
use App\Contracts\NilaiAkhirRepositoryInterface;

class NilaiAkhirRepository implements NilaiAkhirRepositoryInterface
{
    protected $model;

    public function __construct(NilaiAkhir $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->with(['siswa', 'mataPelajaran', 'tahunAjaran'])->get();
    }

    public function findById($id)
    {
        return $this->model->with(['siswa', 'mataPelajaran', 'tahunAjaran'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $nilaiAkhir = $this->findById($id);
        $nilaiAkhir->update($data);
        return $nilaiAkhir;
    }

    public function delete($id)
    {
        return $this->findById($id)->delete();
    }

    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    public function getByMataPelajaran($mataPelajaranId, $tahunAjaranId)
    {
        return $this->model->with(['siswa'])
            ->join('siswas', 'nilai_akhirs.siswa_id', '=', 'siswas.id')
            ->where('mata_pelajaran_id', $mataPelajaranId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->orderBy('siswas.nama', 'asc')
            ->select('nilai_akhirs.*')
            ->get();
    }

    public function getBySiswa($siswaId, $tahunAjaranId)
    {
        return $this->model->with(['mataPelajaran'])
            ->where('siswa_id', $siswaId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->get();
    }

    public function getByKelas($kelasId, $tahunAjaranId)
    {
        return $this->model->with(['siswa', 'mataPelajaran'])
            ->whereHas('siswa.kelas', function ($query) use ($kelasId, $tahunAjaranId) {
                $query->where('kelas_id', $kelasId)
                    ->where('tahun_ajaran_id', $tahunAjaranId);
            })
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->get();
    }
}
