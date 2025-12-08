<x-layout.dashboard title="Edit Siswa">
    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Edit Siswa</h3>
            <x-ui.button variant="outline" icon="fas fa-arrow-left" onclick="location.href='{{ route('siswa.index') }}'">
                Kembali
            </x-ui.button>
        </x-slot>

        <form action="{{ route('siswa.update', $siswa) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- NIS -->
            <div>
                <x-form.label for="nis" required>Nomor Induk Siswa (NIS)</x-form.label>
                <x-form.input name="nis" placeholder="Contoh: 1234567890" value="{{ old('nis', $siswa->nis) }}"
                    required maxlength="20" />
                <x-form.error name="nis" />
                <p class="mt-1 text-sm text-gray-600">Nomor unik untuk identifikasi siswa</p>
            </div>

            <!-- NISN -->
            <div>
                <x-form.label for="nisn">Nomor Induk Siswa Nasional (NISN)</x-form.label>
                <x-form.input name="nisn" placeholder="Contoh: 1234567890" value="{{ old('nisn', $siswa->nisn) }}"
                    maxlength="20" />
                <x-form.error name="nisn" />
                <p class="mt-1 text-sm text-gray-600">Opsional, nomor unik nasional</p>
            </div>

            <!-- Nama -->
            <div>
                <x-form.label for="nama" required>Nama Lengkap</x-form.label>
                <x-form.input name="nama" placeholder="Contoh: Ahmad Fauzi" value="{{ old('nama', $siswa->nama) }}"
                    required maxlength="255" />
                <x-form.error name="nama" />
            </div>

            <!-- Jenis Kelamin -->
            <div>
                <x-form.label for="jenis_kelamin" required>Jenis Kelamin</x-form.label>
                <x-form.select name="jenis_kelamin" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) === 'L' ? 'selected' : '' }}>
                        Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) === 'P' ? 'selected' : '' }}>
                        Perempuan</option>
                </x-form.select>
                <x-form.error name="jenis_kelamin" />
            </div>

            <!-- Tanggal Lahir -->
            <div>
                <x-form.label for="tanggal_lahir" required>Tanggal Lahir</x-form.label>
                <x-form.input name="tanggal_lahir" type="date"
                    value="{{ old('tanggal_lahir', $siswa->tanggal_lahir->format('Y-m-d')) }}" required />
                <x-form.error name="tanggal_lahir" />
            </div>

            <!-- Alamat -->
            <div>
                <x-form.label for="alamat">Alamat</x-form.label>
                <x-form.textarea name="alamat" rows="3" placeholder="Contoh: Jl. Sudirman No. 123, Jakarta"
                    :value="old('alamat', $siswa->alamat)" />
                <x-form.error name="alamat" />
                <p class="mt-1 text-sm text-gray-600">Opsional, maksimal 500 karakter</p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <x-ui.button variant="outline" type="button" onclick="location.href='{{ route('siswa.index') }}'">
                    Batal
                </x-ui.button>
                <x-ui.button variant="primary" type="submit">
                    <i class="fas fa-save mr-2"></i>Perbarui
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>
</x-layout.dashboard>
