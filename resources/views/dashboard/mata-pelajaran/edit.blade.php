<x-layout.dashboard title="Edit Mata Pelajaran">
    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Edit Mata Pelajaran</h3>
            <x-ui.button variant="outline" icon="fas fa-arrow-left"
                onclick="location.href='{{ route('mata-pelajaran.index') }}'">
                Kembali
            </x-ui.button>
        </x-slot>

        <form action="{{ route('mata-pelajaran.update', $mataPelajaran) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama Mata Pelajaran -->
            <div>
                <x-form.label for="nama" required>Nama Mata Pelajaran</x-form.label>
                <x-form.input name="nama" placeholder="Contoh: Matematika, Bahasa Indonesia"
                    value="{{ old('nama', $mataPelajaran->nama) }}" required />
                <x-form.error name="nama" />
            </div>

            <!-- KKM -->
            <div>
                <x-form.label for="kkm" required>KKM (Kriteria Ketuntasan Minimal)</x-form.label>
                <x-form.input name="kkm" type="number" min="0" max="100" placeholder="75"
                    value="{{ old('kkm', $mataPelajaran->kkm) }}" required />
                <x-form.error name="kkm" />
                <p class="mt-1 text-sm text-gray-600">Nilai antara 0-100</p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <x-ui.button variant="outline" type="button"
                    onclick="location.href='{{ route('mata-pelajaran.index') }}'">
                    Batal
                </x-ui.button>
                <x-ui.button variant="primary" type="submit">
                    <i class="fas fa-save mr-2"></i>Perbarui
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>
</x-layout.dashboard>
