<x-layout.dashboard title="Dashboard Overview">
    {{-- Load ApexCharts dari CDN --}}
    @push('styles')
        <style>
            .apexcharts-canvas {
                margin: 0 auto;
            }
        </style>
    @endpush

    <div class="space-y-6">

        {{-- 1. STATS CARDS (DRY Principle) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($stats as $stat)
                <div
                    class="bg-white rounded-xl shadow-sm p-6 flex items-center transition-transform hover:-translate-y-1 duration-300 border border-gray-100">
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-{{ $stat['color'] }}-100 rounded-lg flex items-center justify-center text-{{ $stat['color'] }}-600 mr-4">
                        <i class="{{ $stat['icon'] }} text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ $stat['label'] }}</h3>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stat['value']) }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- 2. CHARTS SECTION --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Chart 1: Siswa per Kelas --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Distribusi Siswa</h3>
                    <span class="text-xs font-medium bg-blue-100 text-blue-800 px-2 py-1 rounded">Real-time</span>
                </div>
                <div id="chartSiswa" class="w-full min-h-[350px]"></div>
            </div>

            {{-- Chart 2: Mapel per Kelas --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Beban Mapel Kelas</h3>
                    <i class="fas fa-chart-pie text-gray-400"></i>
                </div>
                <div id="chartMapel" class="w-full min-h-[350px] flex justify-center"></div>
            </div>
        </div>

        {{-- 3. TABEL KELAS TERPADAT --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Top 5 Kelas Terpadat</h3>
                <a href="{{ route('kelas.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Lihat
                    Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kelas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Wali Kelas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tingkat</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Siswa</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topKelas as $kelas)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-8 w-8 rounded bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                                            {{ substr($kelas->nama, 0, 2) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $kelas->nama_lengkap }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $kelas->waliKelas->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Tingkat {{ $kelas->tingkat_kelas }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-800">
                                    {{ $kelas->siswas_count }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">
                                    Belum ada data kelas yang tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            // 1. CHART SISWA (Bar Chart Modern)
            const optionsSiswa = {
                series: [{
                    name: 'Jumlah Siswa',
                    data: @json($chartSiswa['series'])
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: false
                    },
                    fontFamily: 'inherit'
                },
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        columnWidth: '50%',
                        distributed: true,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: @json($chartSiswa['labels']),
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                },
                yaxis: {
                    title: {
                        text: 'Siswa'
                    }
                },
                fill: {
                    opacity: 1
                },
                colors: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444'],
                tooltip: {
                    y: {
                        formatter: (val) => val + " Siswa"
                    }
                },
                legend: {
                    show: false
                }
            };

            new ApexCharts(document.querySelector("#chartSiswa"), optionsSiswa).render();

            // 2. CHART MAPEL (Donut Chart Modern)
            const optionsMapel = {
                series: @json($chartMapel['series']),
                labels: @json($chartMapel['labels']),
                chart: {
                    type: 'donut',
                    height: 350,
                    fontFamily: 'inherit'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total Mapel',
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                colors: ['#3b82f6', '#6366f1', '#8b5cf6', '#d946ef', '#f43f5e', '#f97316'],
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 300
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            new ApexCharts(document.querySelector("#chartMapel"), optionsMapel).render();
        </script>
    @endpush
</x-layout.dashboard>
