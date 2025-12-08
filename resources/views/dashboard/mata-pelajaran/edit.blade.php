<x-layout.dashboard title="Edit Mata Pelajaran">
    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Edit Mata Pelajaran</h3>
            <x-ui.button variant="outline" icon="fas fa-arrow-left"
                onclick="location.href='{{ route('mata-pelajaran.index') }}'">
                Kembali
            </x-ui.button>
        </x-slot>

        <form action="{{ route('mata-pelajaran.update', $mataPelajaran) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama -->
                <div>
                    <x-ui.label for="nama">Nama Mata Pelajaran *</x-ui.label>
                    <x-ui.input id="nama" name="nama" type="text"
                        placeholder="Contoh: Matematika, Bahasa Indonesia"
                        value="{{ old('nama', $mataPelajaran->nama) }}" required />
                    <x-ui.error name="nama" />
                </div>

                <!-- KKM -->
                <div>
                    <x-ui.label for="kkm">KKM (Kriteria Ketuntasan Minimal) *</x-ui.label>
                    <x-ui.input id="kkm" name="kkm" type="number" min="0" max="100"
                        placeholder="75" value="{{ old('kkm', $mataPelajaran->kkm) }}" required />
                    <x-ui.error name="kkm" />
                    <p class="mt-1 text-sm text-gray-500">Nilai antara 0-100</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
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
