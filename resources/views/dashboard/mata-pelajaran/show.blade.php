<x-layout.dashboard title="Detail Mata Pelajaran">
    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Detail Mata Pelajaran</h3>
            <div class="flex space-x-2">
                <x-ui.button variant="outline" icon="fas fa-edit"
                    onclick="location.href='{{ route('mata-pelajaran.edit', $mataPelajaran) }}'">
                    Edit
                </x-ui.button>
                <x-ui.button variant="outline" icon="fas fa-arrow-left"
                    onclick="location.href='{{ route('mata-pelajaran.index') }}'">
                    Kembali
                </x-ui.button>
            </div>
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama -->
            <div>
                <x-ui.label>Nama Mata Pelajaran</x-ui.label>
                <p class="mt-1 text-sm text-gray-900 font-medium">{{ $mataPelajaran->nama }}</p>
            </div>

            <!-- Kode -->
            <div>
                <x-ui.label>Kode Mata Pelajaran</x-ui.label>
                <p class="mt-1">
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ $mataPelajaran->kode }}
                    </span>
                </p>
            </div>

            <!-- KKM -->
            <div>
                <x-ui.label>KKM (Kriteria Ketuntasan Minimal)</x-ui.label>
                <p class="mt-1 text-sm text-gray-900 font-medium">{{ $mataPelajaran->kkm }}</p>
            </div>

            <!-- Dibuat -->
            <div>
                <x-ui.label>Dibuat Pada</x-ui.label>
                <p class="mt-1 text-sm text-gray-900">{{ $mataPelajaran->created_at->format('d M Y H:i') }}</p>
            </div>

            <!-- Diperbarui -->
            <div>
                <x-ui.label>Terakhir Diperbarui</x-ui.label>
                <p class="mt-1 text-sm text-gray-900">{{ $mataPelajaran->updated_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        <!-- Delete Button -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex justify-end">
                <x-ui.button variant="danger" icon="fas fa-trash" onclick="sweetConfirm()">
                    Hapus Mata Pelajaran
                </x-ui.button>
            </div>
        </div>

        <!-- Sweet Confirm for Delete -->
        <x-sweet.sweet-confirm title="Hapus Mata Pelajaran?"
            text="Apakah Anda yakin ingin menghapus mata pelajaran {{ $mataPelajaran->nama }}? Aksi ini tidak bisa dibatalkan!"
            confirm-text="Ya, hapus!" cancel-text="Batal" icon="warning" confirm-button-color="#ef4444"
            cancel-button-color="#6b7280" action="{{ route('mata-pelajaran.destroy', $mataPelajaran) }}"
            method="DELETE" />
    </x-ui.card>
</x-layout.dashboard>
