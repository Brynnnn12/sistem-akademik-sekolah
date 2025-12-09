<?php

namespace App\Repositories;

use App\Models\PresensiMapel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PresensiMapelRepository
{
    public function __construct(
        private PresensiMapel $model
    ) {}

    /**
     * Ambil data presensi berdasarkan kelas, mapel, dan tanggal
     * Dikembalikan dalam bentuk Collection yang di-key dengan siswa_id
     */
    public function getByKelasMapelTanggal(int $kelasId, int $mapelId, string $tanggal): Collection
    {
        return $this->model
            ->byKelasMapelTanggal($kelasId, $mapelId, $tanggal)
            ->with(['siswa', 'kelas', 'mataPelajaran', 'guru'])
            ->get()
            ->keyBy('siswa_id');
    }

    /**
     * Simpan atau update presensi secara massal
     * Menggunakan updateOrCreate untuk mencegah duplikasi
     */
    public function storeBulk(array $data): void
    {
        DB::transaction(function () use ($data) {
            foreach ($data as $item) {
                $this->model->updateOrCreate(
                    [
                        'siswa_id' => $item['siswa_id'],
                        'mata_pelajaran_id' => $item['mata_pelajaran_id'],
                        'tanggal' => $item['tanggal'],
                        'jam_mulai' => $item['jam_mulai'] ?? null,
                    ],
                    [
                        'kelas_id' => $item['kelas_id'],
                        'guru_id' => $item['guru_id'],
                        'status' => $item['status'],
                        'materi' => $item['materi'] ?? null,
                        'catatan' => $item['catatan'] ?? null,
                    ]
                );
            }
        });
    }

    /**
     * Ambil statistik presensi siswa per mata pelajaran
     */
    public function getStatistikPresensiSiswa(int $siswaId, int $mapelId, ?string $tahunAjaranId = null): array
    {
        $query = $this->model
            ->where('siswa_id', $siswaId)
            ->where('mata_pelajaran_id', $mapelId);

        if ($tahunAjaranId) {
            // Jika ada filter tahun ajaran, tambahkan join ke kelas_siswa
            $query->whereHas('kelas.kelasSiswa', function ($q) use ($tahunAjaranId) {
                $q->where('tahun_ajaran_id', $tahunAjaranId);
            });
        }

        return [
            'total' => $query->count(),
            'hadir' => $query->clone()->where('status', 'H')->count(),
            'sakit' => $query->clone()->where('status', 'S')->count(),
            'izin' => $query->clone()->where('status', 'I')->count(),
            'alpha' => $query->clone()->where('status', 'A')->count(),
            'bolos' => $query->clone()->where('status', 'B')->count(),
        ];
    }

    /**
     * Ambil presensi per kelas dan mata pelajaran dalam rentang tanggal
     */
    public function getByKelasMapelDateRange(
        int $kelasId,
        int $mapelId,
        string $startDate,
        string $endDate
    ): Collection {
        return $this->model
            ->where('kelas_id', $kelasId)
            ->where('mata_pelajaran_id', $mapelId)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->with(['siswa', 'guru'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->get();
    }

    /**
     * Ambil riwayat jurnal mengajar guru
     */
    public function getJurnalMengajarGuru(
        int $guruId,
        ?int $kelasId = null,
        ?int $mapelId = null,
        ?string $startDate = null,
        ?string $endDate = null
    ): Collection {
        $query = $this->model
            ->where('guru_id', $guruId)
            ->whereNotNull('materi');

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        if ($mapelId) {
            $query->where('mata_pelajaran_id', $mapelId);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        return $query
            ->with(['kelas', 'mataPelajaran'])
            ->select('kelas_id', 'mata_pelajaran_id', 'tanggal', 'jam_mulai', 'materi')
            ->distinct()
            ->orderBy('tanggal', 'desc')
            ->get();
    }
}
