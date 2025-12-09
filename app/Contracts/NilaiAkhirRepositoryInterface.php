<?php

namespace App\Contracts;

interface NilaiAkhirRepositoryInterface
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function updateOrCreate(array $attributes, array $values = []);
    public function getByMataPelajaran($mataPelajaranId, $tahunAjaranId);
    public function getBySiswa($siswaId, $tahunAjaranId);
    public function getByKelas($kelasId, $tahunAjaranId);
}
