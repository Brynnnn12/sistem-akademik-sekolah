<?php

namespace App\Services;

use App\Models\User;
use App\Models\PenugasanMengajar;
use App\Repositories\PresensiMapelRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class PresensiMapelService
{
    public function __construct(
        private PresensiMapelRepository $repository
    ) {}

    /**
     * Simpan presensi massal dengan validasi penugasan mengajar
     * 
     * @throws Exception jika guru tidak berhak mengajar di kelas tersebut
     */
    public function simpanPresensi(User $guru, array $data): void
    {
        // Validasi: Cek apakah guru berhak mengajar mata pelajaran di kelas tersebut
        $this->validatePenugasanMengajar(
            $guru->id,
            $data['kelas_id'],
            $data['mata_pelajaran_id'],
            $data['tahun_ajaran_id'] ?? null
        );

        // Siapkan data untuk bulk insert/update
        $presensiData = $this->preparePresensiData($guru, $data);

        // Simpan ke database
        $this->repository->storeBulk($presensiData);
    }

    /**
     * Validasi apakah guru ditugaskan mengajar di kelas dan mata pelajaran tertentu
     * 
     * @throws Exception jika tidak valid
     */
    private function validatePenugasanMengajar(
        int $guruId,
        int $kelasId,
        int $mataPelajaranId,
        ?int $tahunAjaranId = null
    ): void {
        $query = PenugasanMengajar::where('guru_id', $guruId)
            ->where('kelas_id', $kelasId)
            ->where('mata_pelajaran_id', $mataPelajaranId);

        if ($tahunAjaranId) {
            $query->where('tahun_ajaran_id', $tahunAjaranId);
        }

        $penugasan = $query->first();

        if (!$penugasan) {
            throw new Exception(
                'Akses Ditolak: Anda tidak ditugaskan mengajar mata pelajaran ini di kelas tersebut.'
            );
        }
    }

    /**
     * Siapkan data presensi dalam format yang siap disimpan
     */
    private function preparePresensiData(User $guru, array $data): array
    {
        $presensiData = [];
        $siswaData = $data['presensi'] ?? [];

        foreach ($siswaData as $siswaId => $detail) {
            $presensiData[] = [
                'siswa_id' => $siswaId,
                'kelas_id' => $data['kelas_id'],
                'mata_pelajaran_id' => $data['mata_pelajaran_id'],
                'guru_id' => $guru->id,
                'tanggal' => $data['tanggal'],
                'jam_mulai' => $data['jam_mulai'] ?? null,
                'status' => $detail['status'] ?? 'H',
                'materi' => $data['materi'] ?? null,
                'catatan' => $detail['catatan'] ?? null,
            ];
        }

        return $presensiData;
    }

    /**
     * Ambil data presensi yang sudah ada untuk mode edit
     */
    public function getPresensiForEdit(
        int $kelasId,
        int $mapelId,
        string $tanggal
    ): array {
        $presensi = $this->repository->getByKelasMapelTanggal($kelasId, $mapelId, $tanggal);

        return [
            'presensi' => $presensi,
            'materi' => $presensi->first()?->materi ?? '',
            'jam_mulai' => $presensi->first()?->jam_mulai ?? null,
        ];
    }

    /**
     * Ambil statistik presensi siswa
     */
    public function getStatistikPresensiSiswa(
        int $siswaId,
        int $mapelId,
        ?int $tahunAjaranId = null
    ): array {
        return $this->repository->getStatistikPresensiSiswa($siswaId, $mapelId, $tahunAjaranId);
    }

    /**
     * Ambil riwayat jurnal mengajar guru
     */
    public function getJurnalMengajarGuru(
        int $guruId,
        array $filters = []
    ): array {
        $jurnal = $this->repository->getJurnalMengajarGuru(
            $guruId,
            $filters['kelas_id'] ?? null,
            $filters['mata_pelajaran_id'] ?? null,
            $filters['start_date'] ?? null,
            $filters['end_date'] ?? null
        );

        return [
            'jurnal' => $jurnal,
            'total' => $jurnal->count(),
        ];
    }

    /**
     * Validasi data presensi sebelum disimpan
     * 
     * @throws Exception jika validasi gagal
     */
    public function validatePresensiData(array $data): void
    {
        if (empty($data['kelas_id'])) {
            throw new Exception('Kelas harus dipilih.');
        }

        if (empty($data['mata_pelajaran_id'])) {
            throw new Exception('Mata Pelajaran harus dipilih.');
        }

        if (empty($data['tanggal'])) {
            throw new Exception('Tanggal harus diisi.');
        }

        if (empty($data['presensi']) || !is_array($data['presensi'])) {
            throw new Exception('Data presensi siswa tidak valid.');
        }

        // Validasi format tanggal
        if (!strtotime($data['tanggal'])) {
            throw new Exception('Format tanggal tidak valid.');
        }

        // Validasi jam mulai jika ada
        if (!empty($data['jam_mulai']) && !preg_match('/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/', $data['jam_mulai'])) {
            throw new Exception('Format jam mulai tidak valid (gunakan format HH:MM).');
        }
    }
}
