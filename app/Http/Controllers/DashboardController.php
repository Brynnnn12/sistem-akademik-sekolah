<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard overview.
     */
    public function index(): View
    {
        // 1. Ambil Statistik Utama
        $stats = $this->getMainStats();

        // 2. Ambil Data Grafik (Dipisah ke method private biar rapi)
        $chartSiswa = $this->getSiswaPerKelasChartData();
        $chartMapel = $this->getMapelPerKelasChartData();

        // 3. Ambil Data Tabel (Top 5 Kelas Terpadat)
        $topKelas = $this->getTopKelasData();

        return view('dashboard.main.index', compact('stats', 'chartSiswa', 'chartMapel', 'topKelas'));
    }

    private function getMainStats(): array
    {
        return [
            [
                'label' => 'Total Guru',
                'value' => User::role('Guru')->count(),
                'icon' => 'fas fa-chalkboard-teacher',
                'color' => 'blue',
            ],
            [
                'label' => 'Total Kelas',
                'value' => Kelas::count(),
                'icon' => 'fas fa-school',
                'color' => 'green',
            ],
            [
                'label' => 'Total Siswa',
                'value' => Siswa::count(),
                'icon' => 'fas fa-users',
                'color' => 'purple',
            ],
            [
                'label' => 'Mata Pelajaran',
                'value' => MataPelajaran::count(),
                'icon' => 'fas fa-book',
                'color' => 'yellow',
            ],
        ];
    }

    private function getSiswaPerKelasChartData(): array
    {
        $data = Kelas::withCount('siswas')
            ->orderBy('tingkat_kelas')
            ->orderBy('nama')
            ->get();

        return [
            'labels' => $data->pluck('nama_lengkap')->toArray(),
            'series' => $data->pluck('siswas_count')->toArray(),
        ];
    }

    private function getMapelPerKelasChartData(): array
    {
        $data = Kelas::withCount(['penugasanMengajar as mapel_count' => function ($query) {
            $query->selectRaw('count(distinct mata_pelajaran_id)');
        }])
            ->orderBy('tingkat_kelas')
            ->orderBy('nama')
            ->get();

        return [
            'labels' => $data->pluck('nama_lengkap')->toArray(),
            'series' => $data->pluck('mapel_count')->toArray(),
        ];
    }

    private function getTopKelasData()
    {
        return Kelas::withCount('siswas')
            ->with('waliKelas:id,name')
            ->orderByDesc('siswas_count')
            ->take(5)
            ->get();
    }
}
