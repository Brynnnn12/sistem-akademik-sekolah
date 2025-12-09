<x-layout.dashboard title="Rekap Nilai Akhir - Wali Kelas">
    <!-- Flash Messages -->
    <x-ui.flash-messages />

    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Rekap Nilai Akhir Kelas</h3>
            <p class="text-sm text-gray-600 mt-1">{{ $waliKelas->nama }} - {{ $waliKelas->nama_lengkap }}</p>
        </x-slot>

        <!-- Filter Form -->
        <div class="mb-6">
            <form method="GET" action="{{ route('nilai-akhir.rekap-wali-kelas') }}" class="flex gap-4">
                <div>
                    <label for="tahun_ajaran_id" class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajaran</label>
                    <select id="tahun_ajaran_id" name="tahun_ajaran_id"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Pilih Tahun Ajaran</option>
                        @foreach ($tahunAjarans as $ta)
                            <option value="{{ $ta->id }}" {{ $tahunAjaranId == $ta->id ? 'selected' : '' }}>
                                {{ $ta->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-filter mr-2"></i>Tampilkan
                    </button>
                </div>
            </form>
        </div>

        @if ($tahunAjaran)
            <!-- Info Section -->
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-500 rounded-lg">
                            <i class="fas fa-graduation-cap text-white"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-blue-900">Rapor Akhir Kelas {{ $waliKelas->nama }}
                            </h4>
                            <p class="text-sm text-blue-700">Tahun Ajaran: {{ $tahunAjaran->nama }}</p>
                            <p class="text-sm text-blue-600">Total Siswa: {{ count($rekapData) }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <button onclick="window.print()"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            <i class="fas fa-print mr-2"></i>Cetak Rapor
                        </button>
                    </div>
                </div>
            </div>

            <!-- Rekap Table -->
            <x-ui.table :headers="['No', 'NIS', 'Nama Siswa', 'Detail Nilai', 'Rata-rata', 'Grade', 'Status']" striped hover>
                @forelse($rekapData as $index => $data)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $data['siswa']->nis ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $data['siswa']->nama }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs">
                                @if ($data['nilai_akhirs']->count() > 0)
                                    @foreach ($data['nilai_akhirs'] as $nilai)
                                        <div class="flex justify-between py-1">
                                            <span class="text-gray-600">{{ $nilai->mataPelajaran->nama }}:</span>
                                            <span class="font-medium">{{ number_format($nilai->nilai_akhir, 2) }}
                                                ({{ $nilai->grade }})</span>
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-gray-500 italic">Belum ada nilai</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="text-sm font-bold text-gray-900">{{ number_format($data['rata_rata'], 2) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $gradeColor = match ($data['grade_akhir']) {
                                    'A' => 'bg-green-100 text-green-800',
                                    'B' => 'bg-blue-100 text-blue-800',
                                    'C' => 'bg-yellow-100 text-yellow-800',
                                    'D' => 'bg-orange-100 text-orange-800',
                                    'E' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $gradeColor }}">
                                {{ $data['grade_akhir'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($data['status'] === 'Lulus')
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Lulus
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Tidak Lulus
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users text-4xl text-gray-300 mb-2"></i>
                                <p>Tidak ada data siswa di kelas ini</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-ui.table>

            @if (count($rekapData) > 0)
                <!-- Statistics -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-500 rounded-lg">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-blue-600">Total Siswa</p>
                                <p class="text-2xl font-bold text-blue-900">{{ count($rekapData) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-500 rounded-lg">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-600">Lulus</p>
                                <p class="text-2xl font-bold text-green-900">
                                    {{ collect($rekapData)->where('status', 'Lulus')->count() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-red-500 rounded-lg">
                                <i class="fas fa-times-circle text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-red-600">Tidak Lulus</p>
                                <p class="text-2xl font-bold text-red-900">
                                    {{ collect($rekapData)->where('status', 'Tidak Lulus')->count() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-500 rounded-lg">
                                <i class="fas fa-chart-line text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-purple-600">Rata-rata Kelas</p>
                                <p class="text-2xl font-bold text-purple-900">
                                    {{ number_format(collect($rekapData)->avg('rata_rata'), 2) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-500 rounded-lg">
                                <i class="fas fa-trophy text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-yellow-600">Grade Terbanyak</p>
                                <p class="text-2xl font-bold text-yellow-900">
                                    {{ collect($rekapData)->groupBy('grade_akhir')->sortByDesc(function ($group) {return $group->count();})->keys()->first() ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <!-- No Selection Message -->
            <div class="text-center py-12">
                <i class="fas fa-calendar-alt text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Pilih Tahun Ajaran</h3>
                <p class="text-gray-500">Silakan pilih tahun ajaran untuk melihat rapor akhir siswa</p>
            </div>
        @endif
    </x-ui.card>

    @push('styles')
        <style>
            @media print {
                .no-print {
                    display: none !important;
                }

                body {
                    background: white !important;
                }

                .card {
                    box-shadow: none !important;
                    border: 1px solid #e5e7eb !important;
                }
            }
        </style>
    @endpush
</x-layout.dashboard>
