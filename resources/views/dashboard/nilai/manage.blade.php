<x-layout.dashboard title="Kelola Nilai Siswa">
    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Kelola Nilai Siswa</h3>
            <div class="flex space-x-2">
                <x-ui.button variant="outline" icon="fas fa-edit"
                    onclick="location.href='{{ route('nilai.edit', $komponenNilai->id) }}'">
                    Edit Komponen
                </x-ui.button>
                <x-ui.button variant="outline" icon="fas fa-arrow-left"
                    onclick="location.href='{{ route('nilai.index') }}'">
                    Kembali
                </x-ui.button>
            </div>
        </x-slot>

        <!-- Info Komponen -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h4 class="text-sm font-semibold text-blue-800 mb-3">Detail Komponen Nilai</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="font-medium text-blue-700">Nama Komponen:</span>
                    <span class="text-blue-900 block">{{ $komponenNilai->nama }}</span>
                </div>
                <div>
                    <span class="font-medium text-blue-700">Jenis:</span>
                    <span class="text-blue-900 block">{{ ucfirst($komponenNilai->jenis) }}</span>
                </div>
                <div>
                    <span class="font-medium text-blue-700">Bobot:</span>
                    <span class="text-blue-900 block">{{ $komponenNilai->bobot }}%</span>
                </div>
                <div>
                    <span class="font-medium text-blue-700">Mata Pelajaran:</span>
                    <span
                        class="text-blue-900 block">{{ $komponenNilai->penugasan_mengajar->mataPelajaran->nama }}</span>
                </div>
            </div>
        </div>

        <!-- Form Input Nilai -->
        <form action="{{ route('nilai.store-nilai-siswa', $komponenNilai->id) }}" method="POST" id="nilaiForm">
            @csrf
            <input type="hidden" name="komponen_nilai_id" value="{{ $komponenNilai->id }}">

            <x-ui.table :headers="['No', 'NIS', 'Nama Siswa', 'Nilai (0-100)', 'Status']" striped hover>
                @forelse($siswas as $index => $siswa)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $siswa->nis }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $siswa->nama }}</div>
                            <div class="text-sm text-gray-500">{{ $siswa->nisn ? 'NISN: ' . $siswa->nisn : '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="number" name="nilai[{{ $siswa->id }}]"
                                value="{{ $nilaiSiswas->where('siswa_id', $siswa->id)->first()?->nilai }}"
                                min="0" max="100" step="0.1"
                                class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                                placeholder="0-100">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $nilai = $nilaiSiswas->where('siswa_id', $siswa->id)->first()?->nilai;
                            @endphp
                            @if ($nilai !== null)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Sudah Dinilai
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-clock mr-1"></i>Belum Dinilai
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500 text-sm">Tidak ada siswa di kelas ini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-ui.table>

            <!-- Submit Buttons -->
            <div class="flex justify-between items-center mt-6 pt-6 border-t">
                <div class="text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    Kosongkan nilai untuk siswa yang belum/tidak mengikuti penilaian ini.
                </div>
                <div class="flex space-x-3">
                    <x-ui.button variant="outline" onclick="location.href='{{ route('nilai.index') }}'">
                        Batal
                    </x-ui.button>
                    <x-ui.button type="submit" variant="primary" icon="fas fa-save">
                        Simpan Nilai & Hitung Akhir
                    </x-ui.button>
                </div>
            </div>
        </form>

        <!-- Statistics -->
        @if ($siswas->count() > 0)
            <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-500 rounded-lg">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-600">Sudah Dinilai</p>
                            <p class="text-2xl font-bold text-green-900">{{ $nilaiSiswas->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-500 rounded-lg">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-yellow-600">Belum Dinilai</p>
                            <p class="text-2xl font-bold text-yellow-900">
                                {{ $siswas->count() - $nilaiSiswas->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-500 rounded-lg">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-blue-600">Total Siswa</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $siswas->count() }}</p>
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
                                {{ $nilaiSiswas->count() > 0 ? round($nilaiSiswas->avg('nilai'), 1) : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </x-ui.card>
</x-layout.dashboard>
