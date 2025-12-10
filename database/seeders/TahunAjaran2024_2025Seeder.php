<?php

namespace Database\Seeders;

use App\Models\PenugasanMengajar;
use App\Models\User;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use App\Models\KomponenNilai;
use App\Models\NilaiSiswa;
use App\Models\KelasSiswa;
use App\Models\PresensiMapel;
use Illuminate\Database\Seeder;

class TahunAjaran2024_2025Seeder extends Seeder
{
    public function run(): void
    {
        // Ambil tahun ajaran 2024/2025 (kedua semester)
        $tahunAjaran2024Ganjil = TahunAjaran::where('nama', '2024/2025')
            ->where('semester', 'ganjil')
            ->first();

        $tahunAjaran2024Genap = TahunAjaran::where('nama', '2024/2025')
            ->where('semester', 'genap')
            ->first();

        if (!$tahunAjaran2024Ganjil || !$tahunAjaran2024Genap) {
            $this->command->error('Tahun ajaran 2024/2025 tidak ditemukan.');
            return;
        }

        $this->command->info('Memulai seeding data untuk tahun ajaran 2024/2025...');

        // Untuk setiap semester
        foreach ([$tahunAjaran2024Ganjil, $tahunAjaran2024Genap] as $tahunAjaran) {
            $this->command->info("Processing {$tahunAjaran->nama} {$tahunAjaran->semester}...");

            // 1. Buat penugasan mengajar untuk semua kelas dan mapel
            $this->createPenugasanMengajar($tahunAjaran);

            // 2. Buat komponen nilai untuk semua penugasan
            $this->createKomponenNilai($tahunAjaran);

            // 3. Buat nilai siswa untuk semua komponen
            $this->createNilaiSiswa($tahunAjaran);

            // 4. Buat presensi untuk semua penugasan
            $this->createPresensi($tahunAjaran);
        }

        $this->command->info('Seeding data tahun ajaran 2024/2025 selesai!');
    }

    private function createPenugasanMengajar(TahunAjaran $tahunAjaran): void
    {
        $guru = User::role('Guru')->get();
        $kelas = Kelas::all();
        $mataPelajaran = MataPelajaran::all();

        if ($guru->isEmpty() || $kelas->isEmpty() || $mataPelajaran->isEmpty()) {
            $this->command->warn('Data guru, kelas, atau mata pelajaran kosong.');
            return;
        }

        $created = 0;
        foreach ($kelas as $kelasItem) {
            foreach ($mataPelajaran as $mapel) {
                // Assign guru secara bergantian
                $guruId = $guru->get(($created % $guru->count()))->id;

                PenugasanMengajar::firstOrCreate([
                    'guru_id' => $guruId,
                    'kelas_id' => $kelasItem->id,
                    'mata_pelajaran_id' => $mapel->id,
                    'tahun_ajaran_id' => $tahunAjaran->id,
                ]);

                $created++;
            }
        }

        $this->command->info("✓ Penugasan mengajar dibuat: {$created} untuk {$tahunAjaran->nama} {$tahunAjaran->semester}");
    }

    private function createKomponenNilai(TahunAjaran $tahunAjaran): void
    {
        $penugasanList = PenugasanMengajar::where('tahun_ajaran_id', $tahunAjaran->id)->get();

        $komponenTemplates = [
            ['nama' => 'Tugas 1', 'jenis' => 'tugas', 'bobot' => 20],
            ['nama' => 'Tugas 2', 'jenis' => 'tugas', 'bobot' => 20],
            ['nama' => 'UTS', 'jenis' => 'uts', 'bobot' => 30],
            ['nama' => 'UAS', 'jenis' => 'uas', 'bobot' => 30],
        ];

        $created = 0;
        foreach ($penugasanList as $penugasan) {
            foreach ($komponenTemplates as $template) {
                KomponenNilai::firstOrCreate([
                    'penugasan_mengajar_id' => $penugasan->id,
                    'nama' => $template['nama'],
                ], [
                    'jenis' => $template['jenis'],
                    'bobot' => $template['bobot'],
                ]);

                $created++;
            }
        }

        $this->command->info("✓ Komponen nilai dibuat: {$created} untuk {$tahunAjaran->nama} {$tahunAjaran->semester}");
    }

    private function createNilaiSiswa(TahunAjaran $tahunAjaran): void
    {
        $komponenList = KomponenNilai::whereHas('penugasan_mengajar', function ($query) use ($tahunAjaran) {
            $query->where('tahun_ajaran_id', $tahunAjaran->id);
        })->get();

        $created = 0;
        foreach ($komponenList as $komponen) {
            // Ambil siswa di kelas tersebut untuk tahun ajaran ini
            $siswaList = KelasSiswa::where('kelas_id', $komponen->penugasan_mengajar->kelas_id)
                ->where('tahun_ajaran_id', $tahunAjaran->id)
                ->with('siswa')
                ->get();

            foreach ($siswaList as $kelasSiswa) {
                $nilai = $this->generateRandomNilai($komponen->jenis);

                NilaiSiswa::firstOrCreate([
                    'komponen_nilai_id' => $komponen->id,
                    'siswa_id' => $kelasSiswa->siswa_id,
                ], [
                    'nilai' => $nilai,
                ]);

                $created++;
            }
        }

        $this->command->info("✓ Nilai siswa dibuat: {$created} untuk {$tahunAjaran->nama} {$tahunAjaran->semester}");
    }

    private function createPresensi(TahunAjaran $tahunAjaran): void
    {
        $penugasanList = PenugasanMengajar::where('tahun_ajaran_id', $tahunAjaran->id)->get();

        $created = 0;

        // Setiap semester memiliki 15 pertemuan
        $jumlahPertemuan = 15;

        foreach ($penugasanList as $penugasan) {
            // Ambil siswa di kelas tersebut
            $siswaList = KelasSiswa::where('kelas_id', $penugasan->kelas_id)
                ->where('tahun_ajaran_id', $tahunAjaran->id)
                ->with('siswa')
                ->get();

            // Buat 15 pertemuan untuk semester ini
            for ($pertemuan = 1; $pertemuan <= $jumlahPertemuan; $pertemuan++) {
                // Generate tanggal untuk setiap pertemuan (setiap minggu sekali)
                $tanggal = $this->getTanggalPertemuan($tahunAjaran, $pertemuan);

                foreach ($siswaList as $kelasSiswa) {
                    $status = $this->generateRandomStatus();

                    PresensiMapel::firstOrCreate([
                        'siswa_id' => $kelasSiswa->siswa_id,
                        'mata_pelajaran_id' => $penugasan->mata_pelajaran_id,
                        'tanggal' => $tanggal,
                        'jam_mulai' => '07:00',
                    ], [
                        'kelas_id' => $penugasan->kelas_id,
                        'guru_id' => $penugasan->guru_id,
                        'status' => $status,
                        'materi' => $this->generateMateri($penugasan->mataPelajaran->nama),
                        'catatan' => $this->generateCatatan($status),
                        'pertemuan_ke' => $pertemuan,
                    ]);

                    $created++;
                }
            }
        }

        $this->command->info("✓ Presensi dibuat: {$created} untuk {$tahunAjaran->nama} {$tahunAjaran->semester} ({$jumlahPertemuan} pertemuan per mata pelajaran)");
    }

    private function getTanggalPertemuan(TahunAjaran $tahunAjaran, int $pertemuan): string
    {
        // Tentukan tanggal mulai berdasarkan semester
        if ($tahunAjaran->semester === 'ganjil') {
            // Semester ganjil: mulai Juli 2024
            $tanggalMulai = \Carbon\Carbon::create(2024, 7, 1);
        } else {
            // Semester genap: mulai Januari 2025
            $tanggalMulai = \Carbon\Carbon::create(2025, 1, 6);
        }

        // Setiap pertemuan setiap minggu (7 hari sekali)
        $tanggal = $tanggalMulai->copy()->addWeeks($pertemuan - 1);

        // Pastikan tidak melewati akhir semester
        if ($tahunAjaran->semester === 'ganjil') {
            $tanggalAkhir = \Carbon\Carbon::create(2024, 12, 20);
        } else {
            $tanggalAkhir = \Carbon\Carbon::create(2025, 6, 15);
        }

        if ($tanggal->greaterThan($tanggalAkhir)) {
            $tanggal = $tanggalAkhir;
        }

        return $tanggal->format('Y-m-d');
    }

    private function generateRandomNilai(string $jenis): int
    {
        // Distribusi nilai berdasarkan jenis komponen
        if ($jenis === 'uas' || $jenis === 'uts') {
            // UAS/UTS: 60-100, rata-rata 75
            return rand(60, 100);
        } else {
            // Tugas: 70-100, rata-rata 85
            return rand(70, 100);
        }
    }

    private function generateRandomStatus(): string
    {
        $statuses = ['H', 'H', 'H', 'H', 'H', 'H', 'H', 'H', 'S', 'I', 'A'];
        return $statuses[array_rand($statuses)];
    }

    private function generateMateri(string $mapelNama): string
    {
        $materiByMapel = [
            'Matematika' => ['Aljabar', 'Geometri', 'Statistika', 'Trigonometri'],
            'Bahasa Indonesia' => ['Tata Bahasa', 'Sastra', 'Menulis', 'Membaca'],
            'IPA' => ['Fisika', 'Kimia', 'Biologi', 'Astronomi'],
            'IPS' => ['Sejarah', 'Geografi', 'Ekonomi', 'Sosiologi'],
            'Bahasa Inggris' => ['Grammar', 'Vocabulary', 'Speaking', 'Writing'],
            'PKN' => ['Pancasila', 'Demokrasi', 'Hukum', 'Warga Negara'],
            'Agama' => ['Akhlak', 'Ibadah', 'Kisah Nabi', 'Hadits'],
            'Seni Budaya' => ['Musik', 'Tari', 'Seni Rupa', 'Teater'],
            'Penjas' => ['Atletik', 'Permainan', 'Kesehatan', 'Kebugaran'],
            'TIK' => ['Komputer', 'Internet', 'Software', 'Hardware'],
        ];

        $materi = $materiByMapel[$mapelNama] ?? ['Materi Pembelajaran'];
        return $materi[array_rand($materi)];
    }

    private function generateCatatan(string $status): ?string
    {
        $catatanByStatus = [
            'S' => ['Demam', 'Sakit kepala', 'Maag', 'Flu'],
            'I' => ['Izin keluarga', 'Acara sekolah', 'Keperluan mendadak'],
            'A' => ['Bolos', 'Tidak ada keterangan', 'Alasan pribadi'],
        ];

        if (isset($catatanByStatus[$status])) {
            return $catatanByStatus[$status][array_rand($catatanByStatus[$status])];
        }

        return null;
    }
}
