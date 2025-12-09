<x-layout.dashboard title="Manajemen Nilai">
    <!-- Flash Messages -->
    <x-ui.flash-messages />

    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Manajemen Nilai</h3>

        </x-slot>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-500 rounded-lg">
                        <i class="fas fa-book text-white"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-600">Total Penugasan</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $penugasans->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <div class="flex items-center">
                    <div class="p-2 bg-green-500 rounded-lg">
                        <i class="fas fa-chalkboard-teacher text-white"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-600">Mata Pelajaran</p>
                        <p class="text-2xl font-bold text-green-900">
                            {{ $penugasans->unique('mata_pelajaran_id')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-500 rounded-lg">
                        <i class="fas fa-school text-white"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-purple-600">Kelas</p>
                        <p class="text-2xl font-bold text-purple-900">{{ $penugasans->unique('kelas_id')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Penugasan Table -->
        <x-ui.table :headers="['Mata Pelajaran', 'Kelas', 'Tahun Ajaran', 'Aksi']" striped hover>
            @forelse($penugasans as $penugasan)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $penugasan->mataPelajaran->nama }}</div>
                        <div class="text-sm text-gray-500">{{ $penugasan->mataPelajaran->kode }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $penugasan->kelas->nama }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $penugasan->tahunAjaran->nama }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <x-ui.button size="xs" variant="outline" icon="fas fa-eye"
                            onclick="location.href='{{ route('nilai.show-by-penugasan', $penugasan->id) }}'">
                            Lihat Komponen
                        </x-ui.button>

                        @if (auth()->user()->hasRole(['Guru', 'Admin']))
                            <x-ui.button size="xs" variant="primary" icon="fas fa-plus"
                                onclick="location.href='{{ route('nilai.create', ['penugasan_id' => $penugasan->id]) }}'">
                                Tambah Komponen
                            </x-ui.button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-book text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-sm">Belum ada mata pelajaran yang ditugaskan.</p>
                            @if (auth()->user()->hasRole('Admin'))
                                <p class="text-gray-400 text-xs mt-1">Silakan tambahkan penugasan mengajar terlebih
                                    dahulu.</p>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-ui.table>

        <!-- Pagination -->
        @if ($penugasans->hasPages())
            <div class="mt-6">
                {{ $penugasans->links() }}
            </div>
        @endif
    </x-ui.card>
</x-layout.dashboard>
