<x-layout.dashboard title="Edit Penugasan Mengajar">
    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Edit Penugasan Mengajar</h3>
            <x-ui.button variant="outline" icon="fas fa-arrow-left"
                onclick="location.href='{{ route('penugasan-mengajar.index') }}'">
                Kembali
            </x-ui.button>
        </x-slot>

        <form action="{{ route('penugasan-mengajar.update', $penugasanMengajar) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Guru -->
            <div>
                <x-form.label for="guru_id" required>Guru</x-form.label>
                <x-form.select name="guru_id" required searchable="true">
                    <option value="">Pilih Guru</option>
                    @foreach ($availableGuru as $guru)
                        <option value="{{ $guru->id }}"
                            {{ old('guru_id', $penugasanMengajar->guru_id) == $guru->id ? 'selected' : '' }}>
                            {{ $guru->name }} ({{ $guru->email }})
                        </option>
                    @endforeach
                </x-form.select>
                <x-form.error name="guru_id" />
                <p class="mt-1 text-sm text-gray-600">Guru yang akan ditugaskan mengajar</p>
            </div>

            <!-- Mata Pelajaran -->
            <div>
                <x-form.label for="mata_pelajaran_id" required>Mata Pelajaran</x-form.label>
                <x-form.select name="mata_pelajaran_id" required searchable="true">
                    <option value="">Pilih Mata Pelajaran</option>
                    @foreach (\App\Models\MataPelajaran::all() as $mataPelajaran)
                        <option value="{{ $mataPelajaran->id }}"
                            {{ old('mata_pelajaran_id', $penugasanMengajar->mata_pelajaran_id) == $mataPelajaran->id ? 'selected' : '' }}>
                            {{ $mataPelajaran->nama }}
                        </option>
                    @endforeach
                </x-form.select>
                <x-form.error name="mata_pelajaran_id" />
                <p class="mt-1 text-sm text-gray-600">Mata pelajaran yang akan diajarkan</p>
            </div>

            <!-- Kelas -->
            <div>
                <x-form.label for="kelas_id" required>Kelas</x-form.label>
                <x-form.select name="kelas_id" required searchable="true">
                    <option value="">Pilih Kelas</option>
                    @foreach (\App\Models\Kelas::with('waliKelas')->get() as $kelas)
                        <option value="{{ $kelas->id }}"
                            {{ old('kelas_id', $penugasanMengajar->kelas_id) == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_lengkap }} - Wali: {{ $kelas->waliKelas?->name ?? 'Belum ditentukan' }}
                        </option>
                    @endforeach
                </x-form.select>
                <x-form.error name="kelas_id" />
                <p class="mt-1 text-sm text-gray-600">Kelas tempat mengajar</p>
            </div>

            <!-- Tahun Ajaran -->
            <div>
                <x-form.label for="tahun_ajaran_id" required>Tahun Ajaran</x-form.label>
                <x-form.select name="tahun_ajaran_id" required searchable="true">
                    <option value="">Pilih Tahun Ajaran</option>
                    @foreach (\App\Models\TahunAjaran::all() as $tahunAjaran)
                        <option value="{{ $tahunAjaran->id }}"
                            {{ old('tahun_ajaran_id', $penugasanMengajar->tahun_ajaran_id) == $tahunAjaran->id ? 'selected' : '' }}>
                            {{ $tahunAjaran->nama }} {{ $tahunAjaran->aktif ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </x-form.select>
                <x-form.error name="tahun_ajaran_id" />
                <p class="mt-1 text-sm text-gray-600">Tahun ajaran untuk penugasan ini</p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t">
                <x-ui.button variant="outline"
                    onclick="location.href='{{ route('penugasan-mengajar.show', $penugasanMengajar) }}'">
                    Batal
                </x-ui.button>
                <x-ui.button type="submit" variant="primary">
                    Update Penugasan
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>
</x-layout.dashboard>
