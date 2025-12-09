<x-layout.dashboard title="Edit Komponen Nilai">
    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Edit Komponen Nilai</h3>
            <x-ui.button variant="outline" icon="fas fa-arrow-left"
                onclick="location.href='{{ route('nilai.show', $komponenNilai->id) }}'">
                Kembali
            </x-ui.button>
        </x-slot>

        <!-- Detail Penugasan Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h4 class="text-sm font-semibold text-blue-800 mb-2">Detail Penugasan</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="font-medium text-blue-700">Mata Pelajaran:</span>
                    <span class="text-blue-900">{{ $komponenNilai->penugasan_mengajar->mataPelajaran->nama }}</span>
                </div>
                <div>
                    <span class="font-medium text-blue-700">Kelas:</span>
                    <span class="text-blue-900">{{ $komponenNilai->penugasan_mengajar->kelas->nama }}</span>
                </div>
                <div>
                    <span class="font-medium text-blue-700">Tahun Ajaran:</span>
                    <span class="text-blue-900">{{ $komponenNilai->penugasan_mengajar->tahunAjaran->nama }}</span>
                </div>
            </div>
        </div>

        <form action="{{ route('nilai.update', $komponenNilai->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama Komponen -->
            <div>
                <x-form.label for="nama" required>Nama Komponen</x-form.label>
                <x-form.input name="nama" placeholder="Contoh: UH 1, Tugas Bab 1, UTS Semester 1"
                    value="{{ old('nama', $komponenNilai->nama) }}" required maxlength="255" />
                <x-form.error name="nama" />
                <p class="mt-1 text-sm text-gray-600">Beri nama yang jelas untuk komponen penilaian ini</p>
            </div>

            <!-- Jenis Komponen -->
            <div>
                <x-form.label for="jenis" required>Jenis Komponen</x-form.label>
                <x-form.select name="jenis" required>
                    <option value="">Pilih Jenis Komponen</option>
                    <option value="tugas" {{ old('jenis', $komponenNilai->jenis) == 'tugas' ? 'selected' : '' }}>Tugas
                    </option>
                    <option value="uh" {{ old('jenis', $komponenNilai->jenis) == 'uh' ? 'selected' : '' }}>Ulangan
                        Harian (UH)</option>
                    <option value="uts" {{ old('jenis', $komponenNilai->jenis) == 'uts' ? 'selected' : '' }}>Ujian
                        Tengah Semester (UTS)</option>
                    <option value="uas" {{ old('jenis', $komponenNilai->jenis) == 'uas' ? 'selected' : '' }}>Ujian
                        Akhir Semester (UAS)</option>
                </x-form.select>
                <x-form.error name="jenis" />
                <p class="mt-1 text-sm text-gray-600">Pilih jenis penilaian sesuai dengan komponen yang akan dinilai</p>
            </div>

            <!-- Bobot -->
            <div>
                <x-form.label for="bobot" required>Bobot (%)</x-form.label>
                <x-form.input type="number" name="bobot" placeholder="Contoh: 20"
                    value="{{ old('bobot', $komponenNilai->bobot) }}" required min="1" max="100" />
                <x-form.error name="bobot" />
                <p class="mt-1 text-sm text-gray-600">
                    Bobot penilaian dalam persen (1-100). Total bobot per jenis tidak boleh lebih dari 100%.
                </p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t">
                <x-ui.button variant="outline" onclick="location.href='{{ route('nilai.show', $komponenNilai->id) }}'">
                    Batal
                </x-ui.button>
                <x-ui.button type="submit" variant="primary" icon="fas fa-save">
                    Update Komponen
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>
</x-layout.dashboard>
