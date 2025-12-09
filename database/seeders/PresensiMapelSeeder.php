<?php

namespace Database\Seeders;

use App\Models\PresensiMapel;
use App\Models\PenugasanMengajar;
use App\Models\KelasSiswa;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class PresensiMapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunAjaranAktif = TahunAjaran::aktif()->first();

        if (!$tahunAjaranAktif) {
            $this->command->warn('Tidak ada tahun ajaran aktif. Seeder dibatalkan.');
            return;
        }

        // Ambil beberapa penugasan mengajar
        $penugasanList = PenugasanMengajar::aktif()
            ->with(['kelas', 'mataPelajaran', 'guru'])
            ->take(3)
            ->get();

        if ($penugasanList->isEmpty()) {
            $this->command->warn('Tidak ada penugasan mengajar aktif. Seeder dibatalkan.');
            return;
        }

        foreach ($penugasanList as $penugasan) {
            // Ambil siswa di kelas tersebut
            $siswaList = KelasSiswa::where('kelas_id', $penugasan->kelas_id)
                ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                ->with('siswa')
                ->get();

            if ($siswaList->isEmpty()) {
                continue;
            }

            // Generate presensi untuk 3 hari terakhir
            for ($i = 0; $i < 3; $i++) {
                $tanggal = now()->subDays($i)->format('Y-m-d');

                foreach ($siswaList as $kelasSiswa) {
                    $statusOptions = ['H', 'H', 'H', 'H', 'S', 'I', 'A', 'B']; // Lebih banyak H
                    $status = $statusOptions[array_rand($statusOptions)];

                    $catatan = null;
                    if ($status === 'S') {
                        $catatan = 'Demam tinggi';
                    } elseif ($status === 'I') {
                        $catatan = 'Izin keperluan keluarga';
                    } elseif ($status === 'B') {
                        $catatan = 'Bolos jam pelajaran';
                    }

                    PresensiMapel::create([
                        'siswa_id' => $kelasSiswa->siswa_id,
                        'kelas_id' => $penugasan->kelas_id,
                        'mata_pelajaran_id' => $penugasan->mata_pelajaran_id,
                        'guru_id' => $penugasan->guru_id,
                        'tanggal' => $tanggal,
                        'jam_mulai' => '07:00',
                        'status' => $status,
                        'materi' => $this->generateMateri($penugasan->mataPelajaran->nama),
                        'catatan' => $catatan,
                    ]);
                }

                $this->command->info("✓ Presensi dibuat untuk {$penugasan->mataPelajaran->nama} - {$penugasan->kelas->nama_lengkap} tanggal {$tanggal}");
            }
        }

        $this->command->info('✓ PresensiMapelSeeder selesai!');
    }

    private function generateMateri(string $mapelNama): string
    {
        $materiByMapel = [
            'Matematika' => [
                'Perkalian bilangan bulat 1-10',
                'Pembagian dengan sisa',
                'Pecahan sederhana',
                'Operasi hitung campuran',
            ],
            'Bahasa Indonesia' => [
                'Membaca nyaring teks dongeng',
                'Menulis karangan sederhana',
                'Tata bahasa: Kata kerja',
                'Pantun dan syair',
            ],
            'IPA' => [
                'Bagian-bagian tumbuhan',
                'Siklus air',
                'Energi dan sumber energi',
                'Hewan dan habitatnya',
            ],
            'IPS' => [
                'Peta dan denah lingkungan',
                'Kegiatan ekonomi masyarakat',
                'Peninggalan sejarah',
                'Kenampakan alam Indonesia',
            ],
            'default' => [
                'Materi pembelajaran hari ini',
                'Latihan soal dan pembahasan',
                'Review materi minggu lalu',
                'Ulangan harian',
            ]
        ];

        $materi = $materiByMapel[$mapelNama] ?? $materiByMapel['default'];
        return $materi[array_rand($materi)];
    }
}
