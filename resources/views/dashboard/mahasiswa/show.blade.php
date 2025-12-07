<x-layout.dashboard title="Detail Mahasiswa">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center mb-6">
            <a href="{{ route('mahasiswa.index') }}" class="text-blue-600 hover:text-blue-700 mr-4">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <h3 class="text-lg font-semibold text-gray-800">Detail Mahasiswa</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informasi Dasar -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-user-graduate mr-2 text-blue-600"></i>
                    Informasi Mahasiswa
                </h4>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">NIM:</span>
                        <span class="font-medium text-gray-900">{{ $mahasiswa->nim }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama:</span>
                        <span class="font-medium text-gray-900">{{ $mahasiswa->nama }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Kelas:</span>
                        <span class="font-medium text-gray-900">{{ $mahasiswa->kelas }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Alamat:</span>
                        <span class="font-medium text-gray-900">{{ $mahasiswa->alamat_formatted }}</span>
                    </div>
                </div>
            </div>

            <!-- Informasi Tambahan -->
            <div class="bg-blue-50 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-blue-900 mb-4">
                    <i class="fas fa-info-circle mr-2"></i>
                    Informasi Tambahan
                </h4>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-blue-700">ID:</span>
                        <span class="font-medium text-blue-900">#{{ $mahasiswa->id }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-blue-700">Dibuat:</span>
                        <span class="font-medium text-blue-900">{{ $mahasiswa->created_at->format('d M Y H:i') }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-blue-700">Diupdate:</span>
                        <span class="font-medium text-blue-900">{{ $mahasiswa->updated_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-center space-x-4">
            <x-ui.button variant="outline" icon="fas fa-edit"
                onclick="location.href='{{ route('mahasiswa.edit', $mahasiswa) }}'">
                Edit Mahasiswa
            </x-ui.button>

            <x-ui.button variant="danger" icon="fas fa-trash" onclick="confirmDelete{{ $mahasiswa->id }}()">
                Hapus Mahasiswa
            </x-ui.button>
        </div>
    </div>

    <!-- Sweet Alert untuk konfirmasi hapus -->
    <x-sweet.sweet-confirm title="Hapus Mahasiswa?"
        text="Apakah Anda yakin ingin menghapus mahasiswa {{ $mahasiswa->nama_lengkap }}? Aksi ini tidak bisa dibatalkan!"
        confirm-text="Ya, Hapus" cancel-text="Batal" icon="warning"
        action="{{ route('mahasiswa.destroy', $mahasiswa) }}" method="DELETE" />

    <script>
        function confirmDelete{{ $mahasiswa->id }}() {
            Swal.fire({
                title: 'Hapus Mahasiswa?',
                text: 'Apakah Anda yakin ingin menghapus mahasiswa {{ $mahasiswa->nama_lengkap }}?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-{{ $mahasiswa->id }}').submit();
                }
            });
        }
    </script>

    <form id="delete-form-{{ $mahasiswa->id }}" action="{{ route('mahasiswa.destroy', $mahasiswa) }}" method="POST"
        style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</x-layout.dashboard>
