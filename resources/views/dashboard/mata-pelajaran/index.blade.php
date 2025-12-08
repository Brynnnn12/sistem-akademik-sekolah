<x-layout.dashboard title="Manajemen Mata Pelajaran">
    <!-- Flash Messages -->
    <x-ui.flash-messages />

    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Manajemen Mata Pelajaran</h3>
            <x-ui.button variant="primary" icon="fas fa-plus"
                onclick="location.href='{{ route('mata-pelajaran.create') }}'">
                Tambah Mata Pelajaran
            </x-ui.button>
        </x-slot>

        <!-- Search Bar -->
        <div class="mb-6">
            <form method="GET" action="{{ route('mata-pelajaran.index') }}" class="flex gap-2">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari berdasarkan nama atau kode mata pelajaran..."
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('mata-pelajaran.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                @endif
            </form>
            @if (request('search'))
                <p class="mt-2 text-sm text-gray-600">
                    Menampilkan hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
                    ({{ $mataPelajarans->count() }} hasil ditemukan)
                </p>
            @endif
        </div>

        <!-- Mata Pelajaran Table -->
        <x-ui.table :headers="['Nama', 'Kode', 'KKM', 'Dibuat', 'Aksi']" striped hover>
            @forelse($mataPelajarans as $mataPelajaran)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $mataPelajaran->nama }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $mataPelajaran->kode }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $mataPelajaran->kkm }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $mataPelajaran->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <x-ui.button size="xs" variant="outline" icon="fas fa-eye"
                            onclick="location.href='{{ route('mata-pelajaran.show', $mataPelajaran) }}'">
                            View
                        </x-ui.button>
                        <x-ui.button size="xs" variant="outline" icon="fas fa-edit"
                            onclick="location.href='{{ route('mata-pelajaran.edit', $mataPelajaran) }}'">
                            Edit
                        </x-ui.button>
                        <x-ui.button size="xs" variant="danger" icon="fas fa-trash"
                            onclick="sweetConfirm{{ $mataPelajaran->id }}()">
                            Delete
                        </x-ui.button>
                    </td>
                </tr>

                <!-- Generate sweet-confirm for delete -->
                <x-sweet.sweet-confirm title="Hapus Mata Pelajaran?"
                    text="Apakah Anda yakin ingin menghapus mata pelajaran {{ $mataPelajaran->nama }}? Aksi ini tidak bisa dibatalkan!"
                    confirm-text="Ya, hapus!" cancel-text="Batal" icon="warning" confirm-button-color="#ef4444"
                    cancel-button-color="#6b7280" :id="'sweetConfirm' . $mataPelajaran->id"
                    action="{{ route('mata-pelajaran.destroy', $mataPelajaran) }}" method="DELETE" />
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-book text-4xl text-gray-300 mb-2"></i>
                            <p>Tidak ada data mata pelajaran</p>
                            @can('create', App\Models\MataPelajaran::class)
                                <a href="{{ route('mata-pelajaran.create') }}"
                                    class="mt-2 text-blue-600 hover:text-blue-800 text-sm">
                                    Tambah mata pelajaran pertama
                                </a>
                            @endcan
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-ui.table>

        <!-- Pagination -->
        @if ($mataPelajarans->hasPages())
            <div class="mt-6">
                {{ $mataPelajarans->appends(request()->query())->links() }}
            </div>
        @endif
    </x-ui.card>
</x-layout.dashboard>
