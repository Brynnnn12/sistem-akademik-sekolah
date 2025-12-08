<x-layout.dashboard title="Manajemen Siswa">
    <!-- Flash Messages -->
    <x-ui.flash-messages />

    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Manajemen Siswa</h3>
            <x-ui.button variant="primary" icon="fas fa-plus" onclick="location.href='{{ route('siswa.create') }}'">
                Tambah Siswa
            </x-ui.button>
        </x-slot>

        <!-- Search Bar -->
        <div class="mb-6">
            <form method="GET" action="{{ route('siswa.index') }}" class="flex gap-2">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari berdasarkan NIS, NISN, atau nama siswa..."
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('siswa.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                @endif
            </form>
            @if (request('search'))
                <p class="mt-2 text-sm text-gray-600">
                    Menampilkan hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
                    ({{ $siswas->count() }} hasil ditemukan)
                </p>
            @endif
        </div>

        <!-- Siswa Table -->
        <x-ui.table :headers="['NIS', 'NISN', 'Nama', 'Jenis Kelamin', 'Tanggal Lahir', 'Aksi']" striped hover>
            @forelse($siswas as $siswa)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $siswa->nis }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $siswa->nisn ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $siswa->nama }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $siswa->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                            {{ $siswa->jenis_kelamin_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $siswa->tanggal_lahir->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <x-ui.button size="xs" variant="outline" icon="fas fa-eye"
                            onclick="location.href='{{ route('siswa.show', $siswa) }}'">
                            View
                        </x-ui.button>
                        <x-ui.button size="xs" variant="outline" icon="fas fa-edit"
                            onclick="location.href='{{ route('siswa.edit', $siswa) }}'">
                            Edit
                        </x-ui.button>
                        <x-ui.button size="xs" variant="danger" icon="fas fa-trash"
                            onclick="sweetConfirm{{ $siswa->id }}()">
                            Delete
                        </x-ui.button>
                    </td>
                </tr>

                <!-- Generate sweet-confirm for delete -->
                <x-sweet.sweet-confirm title="Hapus Siswa?"
                    text="Apakah Anda yakin ingin menghapus siswa {{ $siswa->nama }}? Aksi ini tidak bisa dibatalkan!"
                    confirm-text="Ya, hapus!" cancel-text="Batal" icon="warning" confirm-button-color="#ef4444"
                    cancel-button-color="#6b7280" :id="'sweetConfirm' . $siswa->id" action="{{ route('siswa.destroy', $siswa) }}"
                    method="DELETE" />
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-users text-4xl text-gray-300 mb-2"></i>
                            <p>Tidak ada data siswa</p>
                            @can('create', App\Models\Siswa::class)
                                <a href="{{ route('siswa.create') }}"
                                    class="mt-2 text-blue-600 hover:text-blue-800 text-sm">
                                    Tambah siswa pertama
                                </a>
                            @endcan
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-ui.table>

        <!-- Pagination -->
        @if ($siswas->hasPages())
            <div class="mt-6">
                {{ $siswas->appends(request()->query())->links() }}
            </div>
        @endif
    </x-ui.card>
</x-layout.dashboard>
