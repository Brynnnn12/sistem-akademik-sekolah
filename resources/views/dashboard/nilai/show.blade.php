<x-layout.dashboard title="Komponen Nilai - {{ $penugasan->mataPelajaran->nama }}">
    <!-- Flash Messages -->
    <x-ui.flash-messages />

    <x-ui.card>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Komponen Nilai</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $penugasan->mataPelajaran->nama }} - {{ $penugasan->kelas->nama }}
                        ({{ $penugasan->tahunAjaran->nama }})
                    </p>
                </div>
                @if (auth()->user()->hasRole(['Guru', 'Admin']))
                    <x-ui.button variant="primary" icon="fas fa-plus"
                        onclick="location.href='{{ route('nilai.create', ['penugasan_id' => $penugasan->id]) }}'">
                        Tambah Komponen
                    </x-ui.button>
                @endif
            </div>
        </x-slot>

        <!-- Statistics -->
        @if ($komponenNilais->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-500 rounded-lg">
                            <i class="fas fa-list text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-blue-600">Total Komponen</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $komponenNilais->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-500 rounded-lg">
                            <i class="fas fa-percentage text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-600">Total Bobot</p>
                            <p class="text-2xl font-bold text-green-900">{{ $komponenNilais->sum('bobot') }}%</p>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-500 rounded-lg">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-purple-600">Siswa Dinilai</p>
                            <p class="text-2xl font-bold text-purple-900">
                                {{ $komponenNilais->where('nilai_siswas_count', '>', 0)->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-orange-500 rounded-lg">
                            <i class="fas fa-chart-line text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-orange-600">Rata-rata Kelas</p>
                            <p class="text-2xl font-bold text-orange-900">
                                @if ($komponenNilais->where('nilai_siswas_count', '>', 0)->count() > 0)
                                    {{ number_format($komponenNilais->avg('nilai_siswas_avg'), 1) }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Komponen Nilai Table -->
        <x-ui.table :headers="['Komponen', 'Bobot', 'Jenis', 'Status', 'Aksi']" striped hover>
            @forelse($komponenNilais as $komponen)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $komponen->nama }}</div>
                        <div class="text-sm text-gray-500">{{ $komponen->deskripsi }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $komponen->bobot }}%
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if ($komponen->jenis === 'UH') bg-green-100 text-green-800
                            @elseif($komponen->jenis === 'UTS') bg-yellow-100 text-yellow-800
                            @elseif($komponen->jenis === 'UAS') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $komponen->jenis }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $nilaiCount = $komponen->nilai_siswas_count ?? 0;
                            $siswaCount = $penugasan->kelas
                                ->siswas()
                                ->wherePivot('tahun_ajaran_id', $penugasan->tahun_ajaran_id)
                                ->count();
                        @endphp
                        @if ($nilaiCount == 0)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-clock mr-1"></i>Belum Dinilai
                            </span>
                        @elseif ($nilaiCount < $siswaCount)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Sebagian
                                ({{ $nilaiCount }}/{{ $siswaCount }})
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Lengkap
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <x-ui.button size="xs" variant="outline" icon="fas fa-eye"
                            onclick="location.href='{{ route('nilai.show', $komponen->id) }}'">
                            Kelola Nilai
                        </x-ui.button>

                        @if (auth()->user()->hasRole(['Guru', 'Admin']))
                            <x-ui.button size="xs" variant="outline" icon="fas fa-edit"
                                onclick="location.href='{{ route('nilai.edit', $komponen->id) }}'">
                                Edit
                            </x-ui.button>

                            <x-ui.button size="xs" variant="danger" icon="fas fa-trash"
                                onclick="if(confirm('Apakah Anda yakin ingin menghapus komponen nilai ini?')) location.href='{{ route('nilai.destroy', $komponen->id) }}'">
                                Hapus
                            </x-ui.button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-list text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-sm">Belum ada komponen nilai untuk mata pelajaran ini.</p>
                            @if (auth()->user()->hasRole(['Guru', 'Admin']))
                                <p class="text-gray-400 text-xs mt-1">Klik tombol "Tambah Komponen" untuk membuat
                                    komponen nilai baru.</p>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-ui.table>

        <!-- Back Button -->
        <div class="mt-6 flex justify-start">
            <x-ui.button variant="outline" onclick="location.href='{{ route('nilai.index') }}'">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Penugasan
            </x-ui.button>
        </div>
    </x-ui.card>
</x-layout.dashboard>
