<x-layout.dashboard title="Edit Tahun Ajaran">
    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Edit Tahun Ajaran: {{ $tahunAjaran->nama }}</h3>
            <x-ui.button variant="outline" icon="fas fa-arrow-left"
                onclick="location.href='{{ route('tahun-ajaran.index') }}'">
                Kembali
            </x-ui.button>
        </x-slot>

        <form action="{{ route('tahun-ajaran.update', $tahunAjaran) }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Nama Tahun Ajaran -->
            <div>
                <x-form.label for="nama" required>Nama Tahun Ajaran</x-form.label>
                <x-form.input name="nama" placeholder="Contoh: 2024/2025"
                    value="{{ old('nama', $tahunAjaran->nama) }}" required />
                <x-form.error name="nama" />
                <p class="mt-1 text-sm text-gray-600">Masukkan nama tahun ajaran dalam format YYYY/YYYY</p>
            </div>

            <!-- Semester -->
            <div>
                <x-form.label for="semester" required>Semester</x-form.label>
                <x-form.select name="semester" required>
                    <option value="">Pilih Semester</option>
                    <option value="ganjil" {{ old('semester', $tahunAjaran->semester) === 'ganjil' ? 'selected' : '' }}>
                        Ganjil
                    </option>
                    <option value="genap" {{ old('semester', $tahunAjaran->semester) === 'genap' ? 'selected' : '' }}>
                        Genap
                    </option>
                </x-form.select>
                <x-form.error name="semester" />
            </div>

            <!-- Status Aktif -->
            <div>
                <x-form.checkbox name="aktif" label="Jadikan tahun ajaran ini aktif"
                    description="Jika dicentang, tahun ajaran ini akan diaktifkan dan tahun ajaran lain yang aktif akan dinonaktifkan"
                    :checked="old('aktif', $tahunAjaran->aktif)" />
                <x-form.error name="aktif" />
            </div>

            <!-- Info Section -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Informasi Tambahan</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-700">Dibuat:</span>
                        <span class="text-gray-600">{{ $tahunAjaran->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Terakhir diupdate:</span>
                        <span class="text-gray-600">{{ $tahunAjaran->updated_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <x-ui.button variant="outline" type="button"
                    onclick="location.href='{{ route('tahun-ajaran.index') }}'">
                    Batal
                </x-ui.button>
                <x-ui.button variant="primary" type="submit" icon="fas fa-save">
                    Update Tahun Ajaran
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>
</x-layout.dashboard>
