<x-layout.dashboard title="Detail Tahun Ajaran">
    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Detail Tahun Ajaran: {{ $tahunAjaran->nama }}</h3>
            <div class="flex space-x-2">
                <x-ui.button variant="outline" icon="fas fa-edit"
                    onclick="location.href='{{ route('tahun-ajaran.edit', $tahunAjaran) }}'">
                    Edit
                </x-ui.button>
                <x-ui.button variant="outline" icon="fas fa-arrow-left"
                    onclick="location.href='{{ route('tahun-ajaran.index') }}'">
                    Kembali
                </x-ui.button>
            </div>
        </x-slot>

        <div class="space-y-6">
            <!-- Status Badge -->
            <div class="flex items-center space-x-4">
                <span
                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                    {{ $tahunAjaran->aktif ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    <i class="fas {{ $tahunAjaran->aktif ? 'fa-check-circle' : 'fa-circle' }} mr-2"></i>
                    {{ $tahunAjaran->aktif ? 'Aktif' : 'Tidak Aktif' }}
                </span>
                <span
                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                    {{ $tahunAjaran->semester === 'ganjil' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                    <i class="fas {{ $tahunAjaran->semester === 'ganjil' ? 'fa-snowflake' : 'fa-sun' }} mr-2"></i>
                    Semester {{ ucfirst($tahunAjaran->semester) }}
                </span>
            </div>

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
                            <dt class="text-sm font-medium text-gray-500">Nama Tahun Ajaran</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $tahunAjaran->nama }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Semester</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ ucfirst($tahunAjaran->semester) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $tahunAjaran->aktif ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $tahunAjaran->aktif ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Timestamps -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-clock mr-2 text-green-600"></i>
                        Informasi Waktu
                    </h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dibuat Pada</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                <i class="fas fa-calendar-plus mr-1"></i>
                                {{ $tahunAjaran->created_at->format('l, d F Y') }}
                            </dd>
                            <dd class="text-xs text-gray-500 mt-1">
                                {{ $tahunAjaran->created_at->format('H:i:s') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Terakhir Diupdate</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                <i class="fas fa-calendar-check mr-1"></i>
                                {{ $tahunAjaran->updated_at->format('l, d F Y') }}
                            </dd>
                            <dd class="text-xs text-gray-500 mt-1">
                                {{ $tahunAjaran->updated_at->format('H:i:s') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                @if (!$tahunAjaran->aktif)
                    <form action="{{ route('tahun-ajaran.set-active', $tahunAjaran) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <x-ui.button variant="success" type="submit" icon="fas fa-check">
                            Aktifkan Tahun Ajaran Ini
                        </x-ui.button>
                    </form>
                @endif

                <x-ui.button variant="outline" icon="fas fa-edit"
                    onclick="location.href='{{ route('tahun-ajaran.edit', $tahunAjaran) }}'">
                    Edit Tahun Ajaran
                </x-ui.button>

                @if (!$tahunAjaran->aktif)
                    <form action="{{ route('tahun-ajaran.destroy', $tahunAjaran) }}" method="POST" class="inline"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus tahun ajaran ini?')">
                        @csrf
                        @method('DELETE')
                        <x-ui.button variant="danger" type="submit" icon="fas fa-trash">
                            Hapus Tahun Ajaran
                        </x-ui.button>
                    </form>
                @endif
            </div>
        </div>
    </x-ui.card>
</x-layout.dashboard>
