<x-layout.dashboard title="Manajemen Tahun Ajaran">
    <!-- Flash Messages -->
    <x-ui.flash-messages />

    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Manajemen Tahun Ajaran</h3>
            <x-ui.button variant="primary" icon="fas fa-plus" onclick="location.href='{{ route('tahun-ajaran.create') }}'">
                Tambah Tahun Ajaran
            </x-ui.button>
        </x-slot>

        <!-- Search Bar -->
        <div class="mb-6">
            <form method="GET" action="{{ route('tahun-ajaran.index') }}" class="flex gap-2">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari berdasarkan nama tahun ajaran..."
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('tahun-ajaran.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                @endif
            </form>
            @if (request('search'))
                <p class="mt-2 text-sm text-gray-600">
                    Menampilkan hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
                    ({{ $tahunAjarans->count() }} hasil ditemukan)
                </p>
            @endif
        </div>

        <!-- Tahun Ajaran Table -->
        <x-ui.table :headers="['Nama', 'Semester', 'Status', 'Dibuat', 'Aksi']" striped hover>
            @forelse($tahunAjarans as $tahunAjaran)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $tahunAjaran->nama }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $tahunAjaran->semester === 'ganjil' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                            <i
                                class="fas {{ $tahunAjaran->semester === 'ganjil' ? 'fa-snowflake' : 'fa-sun' }} mr-1"></i>
                            {{ ucfirst($tahunAjaran->semester) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $tahunAjaran->aktif ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            <i class="fas {{ $tahunAjaran->aktif ? 'fa-check-circle' : 'fa-circle' }} mr-1"></i>
                            {{ $tahunAjaran->aktif ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $tahunAjaran->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <x-ui.button size="xs" variant="outline" icon="fas fa-eye"
                            onclick="showTahunAjaranModal{{ $tahunAjaran->id }}()">
                            View
                        </x-ui.button>

                        @can('update', $tahunAjaran)
                            <x-ui.button size="xs" variant="outline" icon="fas fa-edit"
                                onclick="location.href='{{ route('tahun-ajaran.edit', $tahunAjaran) }}'">
                                Edit
                            </x-ui.button>
                        @endcan

                        @can('delete', $tahunAjaran)
                            @if (!$tahunAjaran->aktif)
                                <x-ui.button size="xs" variant="danger" icon="fas fa-trash"
                                    onclick="confirmDelete('{{ route('tahun-ajaran.destroy', $tahunAjaran) }}', '{{ $tahunAjaran->nama }}')">
                                    Delete
                                </x-ui.button>
                            @else
                                <span class="text-xs text-gray-400 italic">Aktif</span>
                            @endif
                        @endcan

                        @can('update', $tahunAjaran)
                            @if (!$tahunAjaran->aktif)
                                <x-ui.button size="xs" variant="success" icon="fas fa-check"
                                    onclick="confirmSetActive('{{ route('tahun-ajaran.set-active', $tahunAjaran) }}', '{{ $tahunAjaran->nama }}')">
                                    Aktifkan
                                </x-ui.button>
                            @endif
                        @endcan
                    </td>
                </tr>

                {{-- Removed sweet-confirm components from loop --}}

                <!-- Generate sweet-modal for viewing tahun ajaran details -->
                <x-sweet.sweet-modal title="Detail Tahun Ajaran: {{ $tahunAjaran->nama }}"
                    function-name="showTahunAjaranModal{{ $tahunAjaran->id }}" width="600" :show-cancel-button="false"
                    confirm-text="Tutup">
                    <div class="space-y-6">
                        <!-- Tahun Ajaran Header -->
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                {{ $tahunAjaran->nama }}
                            </h3>
                            <div class="flex items-center space-x-4">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $tahunAjaran->aktif ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    <i
                                        class="fas {{ $tahunAjaran->aktif ? 'fa-check-circle' : 'fa-circle' }} mr-2"></i>
                                    {{ $tahunAjaran->aktif ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $tahunAjaran->semester === 'ganjil' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    <i
                                        class="fas {{ $tahunAjaran->semester === 'ganjil' ? 'fa-snowflake' : 'fa-sun' }} mr-2"></i>
                                    Semester {{ ucfirst($tahunAjaran->semester) }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Dibuat: {{ $tahunAjaran->created_at->format('d M Y') }}
                                </span>
                            </div>
                        </div>

                        <!-- Tahun Ajaran Information -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <h5 class="text-sm font-medium text-blue-900 mb-1">
                                    <i class="fas fa-hashtag mr-1"></i>
                                    ID Tahun Ajaran
                                </h5>
                                <p class="text-blue-700">#{{ $tahunAjaran->id }}</p>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-4">
                                <h5 class="text-sm font-medium text-purple-900 mb-1">
                                    <i class="fas fa-clock mr-1"></i>
                                    Terakhir Diupdate
                                </h5>
                                <p class="text-purple-700">{{ $tahunAjaran->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        <!-- Action Buttons in Modal -->
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex space-x-3 justify-center">
                                <button onclick="location.href='{{ route('tahun-ajaran.edit', $tahunAjaran) }}'"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit Tahun Ajaran
                                </button>
                                @if (!$tahunAjaran->aktif)
                                    <button
                                        onclick="Swal.close(); confirmSetActive('{{ route('tahun-ajaran.set-active', $tahunAjaran) }}', '{{ $tahunAjaran->nama }}')"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <i class="fas fa-check mr-2"></i>
                                        Aktifkan
                                    </button>
                                    <button
                                        onclick="Swal.close(); confirmDelete('{{ route('tahun-ajaran.destroy', $tahunAjaran) }}', '{{ $tahunAjaran->nama }}')"
                                        class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <i class="fas fa-trash mr-2"></i>
                                        Hapus
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </x-sweet.sweet-modal>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="text-gray-500">
                            <i class="fas fa-calendar-alt text-4xl mb-4"></i>
                            <p class="text-lg font-medium">Belum ada Tahun Ajaran</p>
                            <p class="mt-2">Mulai dengan membuat tahun ajaran pertama Anda.</p>
                            <x-ui.button variant="primary" icon="fas fa-plus" class="mt-4"
                                onclick="location.href='{{ route('tahun-ajaran.create') }}'">
                                Buat Tahun Ajaran
                            </x-ui.button>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-ui.table>

        @if ($tahunAjarans->hasPages())
            <x-slot name="footer">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan <span class="font-medium">{{ $tahunAjarans->firstItem() }}</span> sampai
                        <span class="font-medium">{{ $tahunAjarans->lastItem() }}</span> dari
                        <span class="font-medium">{{ $tahunAjarans->total() }}</span> hasil
                    </div>
                    <div class="flex space-x-1">
                        {{ $tahunAjarans->links() }}
                    </div>
                </div>
            </x-slot>
        @endif
    </x-ui.card>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function confirmDelete(deleteUrl, namaItem) {
                Swal.fire({
                    title: 'Hapus Tahun Ajaran?',
                    text: `Apakah Anda yakin ingin menghapus tahun ajaran ${namaItem}? Aksi ini tidak bisa dibatalkan!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Membuat form submit secara dinamis
                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = deleteUrl;

                        // Menambahkan CSRF Token
                        let csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';
                        form.appendChild(csrfInput);

                        // Menambahkan Method DELETE spoofing
                        let methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        form.appendChild(methodInput);

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            function confirmSetActive(activeUrl, namaItem) {
                Swal.fire({
                    title: 'Aktifkan Tahun Ajaran?',
                    text: `Apakah Anda yakin ingin mengaktifkan tahun ajaran ${namaItem}? Tahun ajaran lain yang aktif akan dinonaktifkan.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, aktifkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Membuat form submit secara dinamis
                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = activeUrl;

                        // Menambahkan CSRF Token
                        let csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';
                        form.appendChild(csrfInput);

                        // Menambahkan Method PATCH spoofing
                        let methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'PATCH';
                        form.appendChild(methodInput);

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }
        </script>
    @endpush
</x-layout.dashboard>
