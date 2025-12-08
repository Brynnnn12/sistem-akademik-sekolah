<x-layout.dashboard title="Detail Penugasan Mengajar">
    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Detail Penugasan: {{ $penugasanMengajar->nama_lengkap }}</h3>
            <div class="flex space-x-2">
                <x-ui.button variant="outline" icon="fas fa-edit"
                    onclick="location.href='{{ route('penugasan-mengajar.edit', $penugasanMengajar) }}'">
                    Edit
                </x-ui.button>
                <x-ui.button variant="outline" icon="fas fa-arrow-left"
                    onclick="location.href='{{ route('penugasan-mengajar.index') }}'">
                    Kembali
                </x-ui.button>
            </div>
        </x-slot>

        <div class="space-y-6">
            <!-- Information Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Guru Information -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-user-tie mr-2 text-blue-600"></i>
                        Informasi Guru
                    </h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Guru</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $penugasanMengajar->guru->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $penugasanMengajar->guru->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Role</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                @if ($penugasanMengajar->guru->hasRole('Guru'))
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Guru
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Bukan Guru
                                    </span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Mata Pelajaran Information -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-book mr-2 text-green-600"></i>
                        Mata Pelajaran
                    </h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Mata Pelajaran</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $penugasanMengajar->mataPelajaran->nama }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                {{ $penugasanMengajar->mataPelajaran->deskripsi ?? 'Tidak ada deskripsi' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Kelas and Tahun Ajaran Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kelas Information -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-school mr-2 text-purple-600"></i>
                        Informasi Kelas
                    </h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Kelas</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $penugasanMengajar->kelas->nama }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $penugasanMengajar->kelas->nama_lengkap }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tingkat</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                Tingkat {{ $penugasanMengajar->kelas->tingkat_kelas }}
                                ({{ $penugasanMengajar->kelas->tingkat_romawi }})
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Wali Kelas</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                {{ $penugasanMengajar->kelas->waliKelas?->name ?? 'Belum ditentukan' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Tahun Ajaran Information -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-calendar-alt mr-2 text-orange-600"></i>
                        Tahun Ajaran
                    </h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Tahun Ajaran</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $penugasanMengajar->tahunAjaran->nama }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                @if ($penugasanMengajar->tahunAjaran->aktif)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Aktif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Mulai</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                {{ $penugasanMengajar->tahunAjaran->tanggal_mulai?->format('d M Y') ?? 'Tidak ditentukan' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Selesai</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                {{ $penugasanMengajar->tahunAjaran->tanggal_selesai?->format('d M Y') ?? 'Tidak ditentukan' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Assignment Summary -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-clipboard-list mr-2 text-indigo-600"></i>
                    Ringkasan Penugasan
                </h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-700">
                        <strong>{{ $penugasanMengajar->guru->name }}</strong> ditugaskan untuk mengajar
                        <strong>{{ $penugasanMengajar->mataPelajaran->nama }}</strong> di kelas
                        <strong>{{ $penugasanMengajar->kelas->nama_lengkap }}</strong> untuk tahun ajaran
                        <strong>{{ $penugasanMengajar->tahunAjaran->nama }}</strong>.
                    </p>
                </div>
            </div>
        </div>

        <!-- Delete Button -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex justify-end">
                <x-ui.button variant="danger" icon="fas fa-trash" onclick="sweetConfirm{{ $penugasanMengajar->id }}()">
                    Hapus Penugasan
                </x-ui.button>
            </div>
        </div>

        <!-- Sweet Confirm for Delete -->
        <x-sweet.sweet-confirm title="Hapus Penugasan?"
            text="Apakah Anda yakin ingin menghapus penugasan mengajar {{ $penugasanMengajar->nama_lengkap }}? Aksi ini tidak bisa dibatalkan!"
            confirm-text="Ya, hapus!" cancel-text="Batal" icon="warning" confirm-button-color="#ef4444"
            cancel-button-color="#6b7280" :id="'sweetConfirm' . $penugasanMengajar->id"
            action="{{ route('penugasan-mengajar.destroy', $penugasanMengajar) }}" method="DELETE" />
    </x-ui.card>
</x-layout.dashboard>
