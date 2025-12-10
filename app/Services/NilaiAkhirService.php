<?php

namespace App\Services;

use App\Models\NilaiAkhir;
use App\Models\NilaiSiswa;
use App\Models\KomponenNilai;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use App\Models\KelasSiswa;
use App\Models\PresensiMapel;
use App\Repositories\NilaiAkhirRepository;
use Illuminate\Support\Facades\DB;

class NilaiAkhirService
{
    public function __construct(
        protected NilaiAkhirRepository $nilaiAkhirRepository
    ) {}
    /**
     * Generate nilai akhir untuk semua siswa dalam mata pelajaran tertentu
     */
    public function generateNilaiAkhir(MataPelajaran $mataPelajaran, TahunAjaran $tahunAjaran)
    {
        // Ambil semua siswa yang mengambil mata pelajaran ini
        $siswaIds = Siswa::whereHas('kelas', function ($query) use ($tahunAjaran) {
            $query->where('tahun_ajaran_id', $tahunAjaran->id);
        })->pluck('id');

        $results = [];

        foreach ($siswaIds as $siswaId) {
            $nilaiAkhir = $this->calculateNilaiAkhirForSiswa($siswaId, $mataPelajaran->id, $tahunAjaran->id);

            if ($nilaiAkhir !== null) {
                // Simpan atau update nilai akhir
                $this->nilaiAkhirRepository->updateOrCreate([
                    'siswa_id' => $siswaId,
                    'mata_pelajaran_id' => $mataPelajaran->id,
                    'tahun_ajaran_id' => $tahunAjaran->id,
                ], [
                    'nilai_akhir' => $nilaiAkhir,
                    'grade' => $this->convertToGrade($nilaiAkhir),
                ]);

                $results[] = [
                    'siswa_id' => $siswaId,
                    'nilai_akhir' => $nilaiAkhir,
                    'grade' => $this->convertToGrade($nilaiAkhir),
                ];
            }
        }

        return $results;
    }

    /**
     * Hitung nilai akhir untuk satu siswa
     */
    private function calculateNilaiAkhirForSiswa($siswaId, $mataPelajaranId, $tahunAjaranId)
    {
        // Ambil kelas siswa untuk tahun ajaran ini
        $kelasSiswa = KelasSiswa::where('siswa_id', $siswaId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->first();

        if (!$kelasSiswa) {
            return null; // Siswa tidak terdaftar di kelas untuk tahun ini
        }

        // Ambil semua komponen nilai untuk mata pelajaran dan kelas ini
        $komponenNilai = KomponenNilai::whereHas('penugasan_mengajar', function ($query) use ($mataPelajaranId, $tahunAjaranId, $kelasSiswa) {
            $query->where('mata_pelajaran_id', $mataPelajaranId)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('kelas_id', $kelasSiswa->kelas_id);
        })->get();

        if ($komponenNilai->isEmpty()) {
            return null; // Tidak ada komponen nilai
        }

        $totalWeightedScore = 0;
        $totalWeight = 0;

        foreach ($komponenNilai as $komponen) {
            // Ambil nilai siswa untuk komponen ini
            $nilaiSiswa = NilaiSiswa::where('siswa_id', $siswaId)
                ->where('komponen_nilai_id', $komponen->id)
                ->first();

            if ($nilaiSiswa && $nilaiSiswa->nilai !== null) {
                $totalWeightedScore += ($nilaiSiswa->nilai * $komponen->bobot) / 100;
                $totalWeight += $komponen->bobot;
            }
        }

        // Jika total bobot tidak mencapai 100%, sesuaikan perhitungan
        if ($totalWeight == 0) {
            return null; // Tidak ada nilai yang valid
        }

        // Hitung rata-rata tertimbang
        $nilaiAkhir = ($totalWeightedScore / $totalWeight) * 100;

        return round($nilaiAkhir, 2);
    }

    /**
     * Konversi nilai numerik ke grade huruf
     */
    private function convertToGrade($nilai)
    {
        if ($nilai >= 90) return 'A';
        if ($nilai >= 80) return 'B';
        if ($nilai >= 70) return 'C';
        if ($nilai >= 60) return 'D';
        return 'E';
    }

    /**
     * Get nilai akhir untuk mata pelajaran tertentu
     */
    public function getNilaiAkhirByMataPelajaran($mataPelajaranId, $tahunAjaranId)
    {
        return $this->nilaiAkhirRepository->getByMataPelajaran($mataPelajaranId, $tahunAjaranId);
    }

    /**
     * Get rekap nilai akhir untuk kelas tertentu - rapor akhir semua siswa
     */
    public function getRekapNilaiAkhirKelas($kelasId, $tahunAjaranId)
    {
        // Ambil semua siswa di kelas ini
        $siswaIds = Siswa::whereHas('kelas', function ($query) use ($tahunAjaranId, $kelasId) {
            $query->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('kelas_id', $kelasId);
        })->pluck('id');

        $rekapData = [];

        foreach ($siswaIds as $siswaId) {
            $siswa = Siswa::with(['kelas' => function ($query) use ($tahunAjaranId, $kelasId) {
                $query->where('tahun_ajaran_id', $tahunAjaranId)
                    ->where('kelas_id', $kelasId);
            }])->find($siswaId);

            // Ambil semua nilai akhir siswa untuk semua mata pelajaran
            $nilaiAkhirs = $this->nilaiAkhirRepository->getBySiswa($siswaId, $tahunAjaranId);

            // Hitung rata-rata nilai akhir
            $totalNilai = $nilaiAkhirs->sum('nilai_akhir');
            $jumlahMapel = $nilaiAkhirs->count();
            $rataRata = $jumlahMapel > 0 ? round($totalNilai / $jumlahMapel, 2) : 0;

            $rekapData[] = [
                'siswa' => $siswa,
                'nilai_akhirs' => $nilaiAkhirs,
                'rata_rata' => $rataRata,
                'grade_akhir' => $this->convertToGrade($rataRata),
                'jumlah_mapel' => $jumlahMapel,
                'status' => $rataRata >= 75 ? 'Lulus' : 'Tidak Lulus'
            ];
        }

        // Sort by nama siswa
        usort($rekapData, function ($a, $b) {
            return strcmp($a['siswa']->nama, $b['siswa']->nama);
        });

        return $rekapData;
    }

    /**
     * Get rekap rapor wali kelas - mencakup presensi semua mapel + nilai tugas
     */
    public function getRekapRaporWaliKelas($kelasId, $tahunAjaranId)
    {
        // Ambil semua siswa di kelas ini
        $siswaIds = Siswa::whereHas('kelas', function ($query) use ($tahunAjaranId, $kelasId) {
            $query->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('kelas_id', $kelasId);
        })->pluck('id');

        $rekapData = [];

        foreach ($siswaIds as $siswaId) {
            $siswa = Siswa::with(['kelas' => function ($query) use ($tahunAjaranId, $kelasId) {
                $query->where('tahun_ajaran_id', $tahunAjaranId)
                    ->where('kelas_id', $kelasId);
            }])->find($siswaId);

            // 1. Hitung presensi dari semua mata pelajaran
            $presensiStats = $this->calculatePresensiSiswa($siswaId, $kelasId, $tahunAjaranId);

            // 2. Hitung nilai tugas/komponen dari semua mata pelajaran
            $nilaiTugasStats = $this->calculateNilaiTugasSiswa($siswaId, $kelasId, $tahunAjaranId);

            // 3. Hitung nilai akhir rapor (kombinasi presensi + tugas)
            $nilaiRapor = $this->calculateNilaiRaporAkhir($presensiStats, $nilaiTugasStats);

            $rekapData[] = [
                'siswa' => $siswa,
                'presensi' => $presensiStats,
                'nilai_tugas' => $nilaiTugasStats,
                'nilai_rapor' => $nilaiRapor,
                'grade_akhir' => $this->convertToGrade($nilaiRapor),
                'status' => $nilaiRapor >= 75 ? 'Lulus' : 'Tidak Lulus'
            ];
        }

        // Sort by nama siswa
        usort($rekapData, function ($a, $b) {
            return strcmp($a['siswa']->nama, $b['siswa']->nama);
        });

        return $rekapData;
    }

    /**
     * Hitung statistik presensi siswa dari semua mata pelajaran
     */
    private function calculatePresensiSiswa($siswaId, $kelasId, $tahunAjaranId)
    {
        // Ambil semua presensi siswa di kelas ini untuk tahun ajaran ini
        $presensis = PresensiMapel::where('siswa_id', $siswaId)
            ->where('kelas_id', $kelasId)
            ->whereHas('mataPelajaran', function ($query) use ($tahunAjaranId) {
                $query->whereHas('penugasanMengajar', function ($subQuery) use ($tahunAjaranId) {
                    $subQuery->where('tahun_ajaran_id', $tahunAjaranId);
                });
            })
            ->get();

        $total = $presensis->count();
        $hadir = $presensis->where('status', 'H')->count();
        $sakit = $presensis->where('status', 'S')->count();
        $izin = $presensis->where('status', 'I')->count();
        $alpha = $presensis->where('status', 'A')->count();

        // Hitung persentase kehadiran
        $persentaseKehadiran = $total > 0 ? round(($hadir / $total) * 100, 2) : 0;

        // Konversi persentase ke nilai (0-100)
        $nilaiPresensi = $persentaseKehadiran;

        return [
            'total_pertemuan' => $total,
            'hadir' => $hadir,
            'sakit' => $sakit,
            'izin' => $izin,
            'alpha' => $alpha,
            'persentase_kehadiran' => $persentaseKehadiran,
            'nilai_presensi' => $nilaiPresensi
        ];
    }

    /**
     * Hitung statistik nilai tugas siswa dari semua mata pelajaran
     */
    private function calculateNilaiTugasSiswa($siswaId, $kelasId, $tahunAjaranId)
    {
        // Ambil semua komponen nilai (tugas, UTS, UAS) untuk siswa di kelas ini
        $komponenNilais = KomponenNilai::whereHas('penugasan_mengajar', function ($query) use ($kelasId, $tahunAjaranId) {
            $query->where('kelas_id', $kelasId)
                ->where('tahun_ajaran_id', $tahunAjaranId);
        })->with(['nilaiSiswas' => function ($query) use ($siswaId) {
            $query->where('siswa_id', $siswaId);
        }])->get();

        $totalNilai = 0;
        $totalBobot = 0;
        $detailNilai = [];

        foreach ($komponenNilais as $komponen) {
            $nilaiSiswa = $komponen->nilaiSiswas->first();

            if ($nilaiSiswa && $nilaiSiswa->nilai !== null) {
                $nilaiWeighted = ($nilaiSiswa->nilai * $komponen->bobot) / 100;
                $totalNilai += $nilaiWeighted;
                $totalBobot += $komponen->bobot;

                $detailNilai[] = [
                    'mata_pelajaran' => $komponen->penugasan_mengajar->mataPelajaran->nama,
                    'komponen' => $komponen->nama,
                    'nilai' => $nilaiSiswa->nilai,
                    'bobot' => $komponen->bobot,
                    'nilai_weighted' => round($nilaiWeighted, 2)
                ];
            }
        }

        $rataRataTugas = $totalBobot > 0 ? round(($totalNilai / $totalBobot) * 100, 2) : 0;

        return [
            'detail_nilai' => $detailNilai,
            'total_komponen' => count($detailNilai),
            'rata_rata_tugas' => $rataRataTugas
        ];
    }

    /**
     * Hitung nilai rapor akhir (kombinasi presensi + tugas)
     * Bobot: Presensi 30%, Tugas 70%
     */
    private function calculateNilaiRaporAkhir($presensiStats, $nilaiTugasStats)
    {
        $bobotPresensi = 30; // 30%
        $bobotTugas = 70;    // 70%

        $nilaiPresensi = $presensiStats['nilai_presensi'];
        $nilaiTugas = $nilaiTugasStats['rata_rata_tugas'];

        $nilaiAkhir = (($nilaiPresensi * $bobotPresensi) + ($nilaiTugas * $bobotTugas)) / 100;

        return round($nilaiAkhir, 2);
    }
}
