<x-layout.dashboard title="Tambah Tahun Ajaran">
    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Tambah Tahun Ajaran Baru</h3>
            <x-ui.button variant="outline" icon="fas fa-arrow-left"
                onclick="location.href='{{ route('tahun-ajaran.index') }}'">
                Kembali
            </x-ui.button>
        </x-slot>

        <form action="{{ route('tahun-ajaran.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Nama Tahun Ajaran -->
            <div>
                <x-form.label for="nama" required>Nama Tahun Ajaran</x-form.label>
                <x-form.input name="nama" placeholder="Contoh: 2024/2025" value="{{ old('nama') }}" required />
                <x-form.error name="nama" />
                <p class="mt-1 text-sm text-gray-600">Masukkan nama tahun ajaran dalam format YYYY/YYYY</p>
            </div>

            <!-- Semester -->
            <div>
                <x-form.label for="semester" required>Semester</x-form.label>
                <x-form.select name="semester" required>
                    <option value="">Pilih Semester</option>
                    <option value="ganjil" {{ old('semester') === 'ganjil' ? 'selected' : '' }}>
                        Ganjil
                    </option>
                    <option value="genap" {{ old('semester') === 'genap' ? 'selected' : '' }}>
                        Genap
                    </option>
                </x-form.select>
                <x-form.error name="semester" />
            </div>

            <!-- Status Aktif -->
            <div>
                <x-form.checkbox name="aktif" label="Jadikan tahun ajaran ini aktif"
                    description="Jika dicentang, tahun ajaran ini akan diaktifkan dan tahun ajaran lain yang aktif akan dinonaktifkan"
                    :checked="old('aktif', false)" />
                <x-form.error name="aktif" />
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <x-ui.button variant="outline" type="button"
                    onclick="location.href='{{ route('tahun-ajaran.index') }}'">
                    Batal
                </x-ui.button>
                <x-ui.button variant="primary" type="submit" icon="fas fa-save">
                    Simpan Tahun Ajaran
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>
</x-layout.dashboard>
