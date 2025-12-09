<x-layout.dashboard title="Jadwal Mengajar">
    <!-- Flash Messages -->
    <x-ui.flash-messages />

    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Jadwal Mengajar</h3>
            @can('create', App\Models\JadwalMengajar::class)
                <x-ui.button variant="primary" icon="fas fa-plus"
                    onclick="location.href='{{ route('dashboard.jadwal-mengajar.create') }}'">
                    Tambah Jadwal
                </x-ui.button>
            @endcan
        </x-slot>

        <!-- Filter -->
        <div class="mb-6 flex flex-wrap gap-4">
            <form method="GET" class="flex gap-2">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="Cari kelas, mata pelajaran, atau guru..."
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent w-80">
                </div>
                <x-ui.button type="submit" variant="secondary">
                    <i class="fas fa-search"></i>
                </x-ui.button>
            </form>

            <div class="flex gap-2">
                @foreach ($hariList as $hari)
                    <a href="{{ route('dashboard.jadwal-mengajar.index', ['hari' => $hari]) }}"
                        class="px-3 py-2 rounded-lg text-sm font-medium transition duration-200 {{ request('hari') === $hari ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ $hari }}
                    </a>
                @endforeach
                <a href="{{ route('dashboard.jadwal-mengajar.index') }}"
                    class="px-3 py-2 rounded-lg text-sm font-medium transition duration-200 {{ !request('hari') ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Semua
                </a>
            </div>
        </div>

        <!-- Jadwal Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata
                            Pelajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($jadwalMengajars as $jadwal)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $jadwal->penugasanMengajar->kelas->nama }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $jadwal->penugasanMengajar->mataPelajaran->nama }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $jadwal->penugasanMengajar->guru->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $jadwal->hari }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $jadwal->jam_lengkap }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    @can('view', $jadwal)
                                        <x-ui.button variant="secondary" size="sm"
                                            onclick="location.href='{{ route('dashboard.jadwal-mengajar.show', $jadwal) }}'">
                                            <i class="fas fa-eye"></i>
                                        </x-ui.button>
                                    @endcan
                                    @can('update', $jadwal)
                                        <x-ui.button variant="primary" size="sm"
                                            onclick="location.href='{{ route('dashboard.jadwal-mengajar.edit', $jadwal) }}'">
                                            <i class="fas fa-edit"></i>
                                        </x-ui.button>
                                    @endcan
                                    @can('delete', $jadwal)
                                        <form method="POST"
                                            action="{{ route('dashboard.jadwal-mengajar.destroy', $jadwal) }}"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <x-ui.button type="submit" variant="danger" size="sm">
                                                <i class="fas fa-trash"></i>
                                            </x-ui.button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada jadwal mengajar</h3>
                                    <p class="text-gray-500">Belum ada jadwal mengajar yang dibuat.</p>
                                    @can('create', App\Models\JadwalMengajar::class)
                                        <x-ui.button variant="primary" class="mt-4"
                                            onclick="location.href='{{ route('dashboard.jadwal-mengajar.create') }}'">
                                            <i class="fas fa-plus mr-2"></i>Buat Jadwal Pertama
                                        </x-ui.button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($jadwalMengajars instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-6">
                {{ $jadwalMengajars->appends(request()->query())->links() }}
            </div>
        @endif
    </x-ui.card>
</x-layout.dashboard>
