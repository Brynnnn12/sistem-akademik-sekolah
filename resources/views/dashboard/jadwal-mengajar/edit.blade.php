<x-layout.dashboard title="Edit Jadwal Mengajar">
    <!-- Flash Messages -->
    <x-ui.flash-messages />

    <x-ui.card>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Edit Jadwal Mengajar</h3>
                <x-ui.button variant="secondary" onclick="location.href='{{ route('dashboard.jadwal-mengajar.index') }}'">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </x-ui.button>
            </div>
        </x-slot>

        <p class="text-gray-600 mb-6">Perbarui informasi jadwal mengajar.</p>

        <form action="{{ route('dashboard.jadwal-mengajar.update', $jadwalMengajar) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Penugasan Mengajar -->
            <x-ui.form-group label="Penugasan Mengajar" required>
                <x-form.select name="penugasan_mengajar_id" required placeholder="Pilih Penugasan Mengajar">
                    <option value="">Pilih Penugasan Mengajar</option>
                    @foreach ($penugasanMengajars as $penugasan)
                        <option value="{{ $penugasan->id }}"
                            {{ old('penugasan_mengajar_id', $jadwalMengajar->penugasan_mengajar_id) == $penugasan->id ? 'selected' : '' }}>
                            {{ $penugasan->kelas->nama }} - {{ $penugasan->mataPelajaran->nama }}
                            ({{ $penugasan->guru->name }})
                        </option>
                    @endforeach
                </x-form.select>
                <x-ui.error-message field="penugasan_mengajar_id" />
            </x-ui.form-group>

            <!-- Hari -->
            <x-ui.form-group label="Hari" required>
                <x-form.select name="hari" required placeholder="Pilih Hari">
                    <option value="">Pilih Hari</option>
                    @foreach ($hariList as $hari)
                        <option value="{{ $hari }}"
                            {{ old('hari', $jadwalMengajar->hari) == $hari ? 'selected' : '' }}>
                            {{ $hari }}
                        </option>
                    @endforeach
                </x-form.select>
                <x-ui.error-message field="hari" />
            </x-ui.form-group>

            <!-- Jam Mulai -->
            <x-ui.form-group label="Jam Mulai" required>
                <input type="time" id="jam_mulai" name="jam_mulai"
                    value="{{ old('jam_mulai', $jadwalMengajar->jam_mulai->format('H:i')) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jam_mulai') border-red-500 @enderror"
                    required>
                <x-ui.error-message field="jam_mulai" />
            </x-ui.form-group>

            <!-- Jam Selesai -->
            <x-ui.form-group label="Jam Selesai" required>
                <input type="time" id="jam_selesai" name="jam_selesai"
                    value="{{ old('jam_selesai', $jadwalMengajar->jam_selesai->format('H:i')) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jam_selesai') border-red-500 @enderror"
                    required>
                <x-ui.error-message field="jam_selesai" />
            </x-ui.form-group>

            @error('jadwal')
                <x-ui.alert variant="danger">
                    {{ $message }}
                </x-ui.alert>
            @enderror

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t">
                <x-ui.button variant="secondary"
                    onclick="location.href='{{ route('dashboard.jadwal-mengajar.index') }}'">
                    Batal
                </x-ui.button>
                <x-ui.button type="submit" variant="primary">
                    <i class="fas fa-save mr-2"></i>Perbarui
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>
</x-layout.dashboard>
