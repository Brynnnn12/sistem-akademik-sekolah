<x-layout.dashboard title="Detail Jadwal Mengajar">
    <!-- Flash Messages -->
    <x-ui.flash-messages />

    <x-ui.card>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Detail Jadwal Mengajar</h3>
                <div class="flex space-x-2">
                    @can('update', $jadwalMengajar)
                        <x-ui.button variant="primary"
                            onclick="location.href='{{ route('dashboard.jadwal-mengajar.edit', $jadwalMengajar) }}'">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </x-ui.button>
                    @endcan
                    <x-ui.button variant="secondary"
                        onclick="location.href='{{ route('dashboard.jadwal-mengajar.index') }}'">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </x-ui.button>
                </div>
            </div>
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informasi Penugasan -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Informasi Penugasan</h4>
                <div class="space-y-3">
                    <x-ui.info-item label="Kelas" :value="$jadwalMengajar->penugasanMengajar->kelas->nama" />
                    <x-ui.info-item label="Mata Pelajaran" :value="$jadwalMengajar->penugasanMengajar->mataPelajaran->nama" />
                    <x-ui.info-item label="Guru Pengajar" :value="$jadwalMengajar->penugasanMengajar->guru->name" />
                </div>
            </div>

            <!-- Informasi Jadwal -->
            <div class="bg-blue-50 rounded-lg p-4">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Informasi Jadwal</h4>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Hari</label>
                        <p class="text-sm text-gray-900">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                {{ $jadwalMengajar->hari }}
                            </span>
                        </p>
                    </div>
                    <x-ui.info-item label="Jam Mulai" :value="$jadwalMengajar->jam_mulai->format('H:i')" />
                    <x-ui.info-item label="Jam Selesai" :value="$jadwalMengajar->jam_selesai->format('H:i')" />
                    <x-ui.info-item label="Durasi" :value="$jadwalMengajar->jam_lengkap" />
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex justify-end space-x-3">
                @can('delete', $jadwalMengajar)
                    <form method="POST" action="{{ route('dashboard.jadwal-mengajar.destroy', $jadwalMengajar) }}"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal mengajar ini?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <x-ui.button type="submit" variant="danger">
                            <i class="fas fa-trash mr-2"></i>Hapus Jadwal
                        </x-ui.button>
                    </form>
                @endcan
            </div>
        </div>
    </x-ui.card>
</x-layout.dashboard>
