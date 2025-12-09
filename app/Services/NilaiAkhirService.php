<?php

namespace App\Services;

use App\Models\NilaiAkhir;
use App\Models\NilaiSiswa;
use App\Models\KomponenNilai;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use App\Models\KelasSiswa;
use App\Repositories\NilaiAkhirRepository;
use Illuminate\Support\Facades\DB;

class NilaiAkhirService
{
    protected $nilaiAkhirRepository;

    public function __construct(NilaiAkhirRepository $nilaiAkhirRepository)
    {
        $this->nilaiAkhirRepository = $nilaiAkhirRepository;
    }

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
}
