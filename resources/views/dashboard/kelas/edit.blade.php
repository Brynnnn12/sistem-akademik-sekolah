<x-layout.dashboard title="Edit Kelas">
    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Edit Kelas: {{ $kelas->nama_lengkap }}</h3>
            <x-ui.button variant="outline" icon="fas fa-arrow-left" onclick="location.href='{{ route('kelas.index') }}'">
                Kembali
            </x-ui.button>
        </x-slot>

        <form action="{{ route('kelas.update', $kelas) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama Kelas -->
            <div>
                <x-form.label for="nama" required>Nama Kelas</x-form.label>
                <x-form.input name="nama" placeholder="Contoh: 1A, 6B, 3C" value="{{ old('nama', $kelas->nama) }}"
                    required maxlength="10" />
                <x-form.error name="nama" />
                <p class="mt-1 text-sm text-gray-600">Format: Angka 1-6 diikuti huruf A-Z (contoh: 1A, 6B)</p>
            </div>

            <!-- Tingkat Kelas -->
            <div>
                <x-form.label for="tingkat_kelas" required>Tingkat Kelas</x-form.label>
                <x-form.select name="tingkat_kelas" required>
                    <option value="">Pilih Tingkat Kelas</option>
                    @for ($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}"
                            {{ old('tingkat_kelas', $kelas->tingkat_kelas) == $i ? 'selected' : '' }}>
                            Tingkat {{ $i }}
                        </option>
                    @endfor
                </x-form.select>
                <x-form.error name="tingkat_kelas" />
                <p class="mt-1 text-sm text-gray-600">Tingkat kelas dari 1 sampai 6</p>
            </div>

            <!-- Wali Kelas -->
            <div>
                <x-form.label for="wali_kelas_id" required>Wali Kelas</x-form.label>
                <x-form.select name="wali_kelas_id" required searchable="true">
                    <option value="">Pilih Wali Kelas</option>
                    @foreach ($availableWaliKelas as $guru)
                        @php
                            $isAssigned = $guru->kelasWali !== null && $guru->kelasWali->id !== $kelas->id;
                            $kelasInfo = $isAssigned ? ' (Wali Kelas ' . $guru->kelasWali->nama_lengkap . ')' : '';
                            $isCurrentWali = $guru->id === $kelas->wali_kelas_id;
                        @endphp
                        <option value="{{ $guru->id }}"
                            {{ old('wali_kelas_id', $kelas->wali_kelas_id) == $guru->id ? 'selected' : '' }}
                            {{ $isAssigned ? 'disabled' : '' }}>
                            {{ $guru->name }}
                            ({{ $guru->email }}){{ $kelasInfo }}{{ $isCurrentWali ? ' (Wali Kelas Saat Ini)' : '' }}
                        </option>
                    @endforeach
                </x-form.select>
                <x-form.error name="wali_kelas_id" />
                <p class="mt-1 text-sm text-gray-600">Guru yang akan menjadi wali kelas</p>
                @if ($availableWaliKelas->isEmpty())
                    <p class="mt-2 text-sm text-amber-600 bg-amber-50 p-2 rounded">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Tidak ada guru yang tersedia. <a href="#" class="text-blue-600 hover:text-blue-800">Tambah
                            guru terlebih dahulu</a>.
                    </p>
                @endif
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <x-ui.button variant="outline" type="button" onclick="location.href='{{ route('kelas.index') }}'">
                    Batal
                </x-ui.button>
                <x-ui.button variant="primary" type="submit">
                    <i class="fas fa-save mr-2"></i>Update
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>
</x-layout.dashboard>
