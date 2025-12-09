<x-layout.dashboard title="Hasil Kenaikan Kelas & Kelulusan">
    <!-- Flash Messages -->
    <x-ui.flash-messages />

    <!-- Filter Tahun Ajaran -->
    <x-ui.card class="mb-6">
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Filter Tahun Ajaran</h3>
        </x-slot>

        <form method="GET" action="{{ route('promotion.results') }}" class="flex gap-4 items-end">
            <div class="flex-1">
                <label for="tahun_ajaran_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Tahun Ajaran
                </label>
                <x-form.select name="tahun_ajaran_id" placeholder="Pilih Tahun Ajaran">
                    <option value="">Pilih Tahun Ajaran</option>
                    @foreach ($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" {{ $tahunAjaran?->id == $ta->id ? 'selected' : '' }}>
                            {{ $ta->nama }}
                        </option>
                    @endforeach>
                </x-form.select>
            </div>
            <x-ui.button type="submit" variant="primary">
                <i class="fas fa-search mr-2"></i>Filter
            </x-ui.button>
        </form>
    </x-ui.card>

    @if ($tahunAjaran)
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Hasil Kenaikan Kelas & Kelulusan</h2>
            <p class="text-gray-600">Tahun Ajaran: <span class="font-semibold">{{ $tahunAjaran->nama }}</span></p>
        </div>

        <!-- Siswa yang Naik Kelas -->
        @if ($promotedByClass->isNotEmpty())
            <x-ui.card class="mb-6">
                <x-slot name="header">
                    <div class="flex items-center">
                        <i class="fas fa-arrow-up text-green-500 mr-2"></i>
                        <h3 class="text-lg font-semibold text-gray-800">Siswa yang Naik Kelas</h3>
                    </div>
                </x-slot>

                <div class="space-y-6">
                    @foreach ($promotedByClass as $kelasNama => $siswaList)
                        <div class="border rounded-lg p-4">
                            <h4 class="text-md font-semibold text-gray-800 mb-3 bg-green-50 p-2 rounded">
                                Kelas {{ $kelasNama }}
                            </h4>
                            <x-ui.table :headers="['NIS', 'Nama Siswa', 'Kelas Asal', 'Tanggal Naik']" striped hover>
                                @foreach ($siswaList as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $item['siswa']->nis }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $item['siswa']->nama }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item['siswa']->created_at->format('d/m/Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </x-ui.table>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        @endif

        <!-- Siswa yang Belum Naik Kelas -->
        @if ($notPromotedByClass->isNotEmpty())
            <x-ui.card class="mb-6">
                <x-slot name="header">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-yellow-500 mr-2"></i>
                        <h3 class="text-lg font-semibold text-gray-800">Siswa yang Belum Naik Kelas</h3>
                    </div>
                </x-slot>

                <div class="space-y-6">
                    @foreach ($notPromotedByClass as $kelasNama => $siswaList)
                        <div class="border rounded-lg p-4">
                            <h4 class="text-md font-semibold text-gray-800 mb-3 bg-yellow-50 p-2 rounded">
                                Kelas {{ $kelasNama }}
                            </h4>
                            <x-ui.table :headers="['NIS', 'Nama Siswa', 'Status']" striped hover>
                                @foreach ($siswaList as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $item['siswa']->nis }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $item['siswa']->nama }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                                                Belum Naik Kelas
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </x-ui.table>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        @endif

        <!-- Siswa yang Lulus -->
        @if ($graduatedStudents->isNotEmpty())
            <x-ui.card class="mb-6">
                <x-slot name="header">
                    <div class="flex items-center">
                        <i class="fas fa-graduation-cap text-blue-500 mr-2"></i>
                        <h3 class="text-lg font-semibold text-gray-800">Siswa yang Lulus</h3>
                    </div>
                </x-slot>

                <x-ui.table :headers="['NIS', 'Nama Siswa', 'Kelas Asal', 'Tanggal Lulus']" striped hover>
                    @foreach ($graduatedStudents as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item['siswa']->nis }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $item['siswa']->nama }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item['kelas_asal']->nama }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item['siswa']->tanggal_lulus?->format('d/m/Y') ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </x-ui.table>
            </x-ui.card>
        @endif

        <!-- Empty State -->
        @if ($promotedByClass->isEmpty() && $notPromotedByClass->isEmpty() && $graduatedStudents->isEmpty())
            <x-ui.card>
                <div class="text-center py-12">
                    <i class="fas fa-info-circle text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data kenaikan kelas</h3>
                    <p class="text-gray-500">Data kenaikan kelas dan kelulusan akan muncul setelah proses promosi
                        dilakukan.</p>
                </div>
            </x-ui.card>
        @endif
    @else
        <x-ui.card>
            <div class="text-center py-12">
                <i class="fas fa-calendar-alt text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Pilih Tahun Ajaran</h3>
                <p class="text-gray-500">Silakan pilih tahun ajaran untuk melihat hasil kenaikan kelas dan kelulusan.
                </p>
            </div>
        </x-ui.card>
    @endif
</x-layout.dashboard>
