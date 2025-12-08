<x-layout.dashboard title="Manajemen Kelas">
    <!-- Flash Messages -->
    <x-ui.flash-messages />

    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Manajemen Kelas</h3>
            <x-ui.button variant="primary" icon="fas fa-plus" onclick="location.href='{{ route('kelas.create') }}'">
                Tambah Kelas
            </x-ui.button>
        </x-slot>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-500 rounded-lg">
                        <i class="fas fa-school text-white"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-600">Total Kelas</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $statistics['total_kelas'] }}</p>
                    </div>
                </div>
            </div>

            @for ($i = 1; $i <= 6; $i++)
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-500 rounded-lg">
                            <i class="fas fa-graduation-cap text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-600">Tingkat {{ $i }}</p>
                            <p class="text-2xl font-bold text-green-900">{{ $statistics['kelas_by_tingkat'][$i] ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <form method="GET" action="{{ route('kelas.index') }}" class="flex gap-2">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari berdasarkan nama kelas atau tingkat..."
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('kelas.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                @endif
            </form>
            @if (request('search'))
                <p class="mt-2 text-sm text-gray-600">
                    Menampilkan hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
                    ({{ $kelas->count() }} hasil ditemukan)
                </p>
            @endif
        </div>

        <!-- Kelas Table -->
        <x-ui.table :headers="['Nama Kelas', 'Tingkat', 'Wali Kelas', 'Jumlah Siswa', 'Aksi']" striped hover>
            @forelse($kelas as $kelasItem)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $kelasItem->nama }}</div>
                        <div class="text-sm text-gray-500">{{ $kelasItem->nama_lengkap }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Tingkat {{ $kelasItem->tingkat_kelas }} ({{ $kelasItem->tingkat_romawi }})
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $kelasItem->waliKelas?->name ?? 'Belum ditentukan' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $kelasItem->jumlah_siswa_aktif }} siswa
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <x-ui.button size="xs" variant="outline" icon="fas fa-eye"
                            onclick="location.href='{{ route('kelas.show', $kelasItem) }}'">
                            View
                        </x-ui.button>
                        <x-ui.button size="xs" variant="outline" icon="fas fa-edit"
                            onclick="location.href='{{ route('kelas.edit', $kelasItem) }}'">
                            Edit
                        </x-ui.button>
                        <x-ui.button size="xs" variant="danger" icon="fas fa-trash"
                            onclick="sweetConfirm{{ $kelasItem->id }}()">
                            Delete
                        </x-ui.button>
                    </td>
                </tr>

                <!-- Generate sweet-confirm for delete -->
                <x-sweet.sweet-confirm title="Hapus Kelas?"
                    text="Apakah Anda yakin ingin menghapus kelas {{ $kelasItem->nama }}? Aksi ini tidak bisa dibatalkan!"
                    confirm-text="Ya, hapus!" cancel-text="Batal" icon="warning" confirm-button-color="#ef4444"
                    cancel-button-color="#6b7280" :id="'sweetConfirm' . $kelasItem->id" action="{{ route('kelas.destroy', $kelasItem) }}"
                    method="DELETE" />
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-school text-4xl text-gray-300 mb-2"></i>
                            <p>Tidak ada data kelas</p>
                            @can('create', App\Models\Kelas::class)
                                <a href="{{ route('kelas.create') }}"
                                    class="mt-2 text-blue-600 hover:text-blue-800 text-sm">
                                    Tambah kelas pertama
                                </a>
                            @endcan
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-ui.table>

        <!-- Pagination -->
        @if ($kelas->hasPages())
            <div class="mt-6">
                {{ $kelas->appends(request()->query())->links() }}
            </div>
        @endif
    </x-ui.card>
</x-layout.dashboard>
