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
                            <dt class="text-sm font-medium text-gray-500">Nama Mata Pelajaran</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $mataPelajaran->nama }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Kode Mata Pelajaran</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $mataPelajaran->kode }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">KKM</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $mataPelajaran->kkm }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Timestamp Information -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-clock mr-2 text-green-600"></i>
                        Informasi Waktu
                    </h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dibuat Pada</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $mataPelajaran->created_at->format('d M Y H:i') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Terakhir Diperbarui</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $mataPelajaran->updated_at->format('d M Y H:i') }}
                            </dd>
                        </div>
                    </dl>
                </div>
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
