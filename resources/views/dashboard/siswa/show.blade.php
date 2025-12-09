<x-layout.dashboard title="Detail Siswa">
    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Detail Siswa</h3>
            <div class="flex space-x-2">
                <x-ui.button variant="outline" icon="fas fa-edit"
                    onclick="location.href='{{ route('siswa.edit', $siswa) }}'">
                    Edit
                </x-ui.button>
                <x-ui.button variant="outline" icon="fas fa-arrow-left"
                    onclick="location.href='{{ route('siswa.index') }}'">
                    Kembali
                </x-ui.button>
            </div>
        </x-slot>

        <div class="space-y-6">
            <!-- Information Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                        Informasi Dasar
                    </h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $siswa->nama }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">NIS</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $siswa->nis }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">NISN</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $siswa->nisn ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Jenis Kelamin</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $siswa->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                    {{ $siswa->jenis_kelamin_label }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Additional Information -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-address-card mr-2 text-green-600"></i>
                        Informasi Tambahan
                    </h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Lahir</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $siswa->tanggal_lahir->format('d M Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Umur</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $siswa->umur }} tahun</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $siswa->alamat ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Timestamp Information -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-clock mr-2 text-purple-600"></i>
                    Informasi Waktu
                </h4>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Dibuat Pada</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ $siswa->created_at->format('d M Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Terakhir Diperbarui</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ $siswa->updated_at->format('d M Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Delete Button -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex justify-end">
                <x-ui.button variant="danger" icon="fas fa-trash"
                    onclick="confirmDelete('{{ route('siswa.destroy', $siswa) }}', '{{ $siswa->nama }}')">
                    Hapus Siswa
                </x-ui.button>
            </div>
        </div>

        {{-- Removed sweet-confirm component --}}
    </x-ui.card>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function confirmDelete(deleteUrl, namaItem) {
                Swal.fire({
                    title: 'Hapus Siswa?',
                    text: `Apakah Anda yakin ingin menghapus siswa ${namaItem}? Aksi ini tidak bisa dibatalkan!`,
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
        </script>
    @endpush
</x-layout.dashboard>
