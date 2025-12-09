<x-layout.dashboard title="Tambah Penugasan Mengajar">
    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Tambah Penugasan Mengajar Baru</h3>
            <x-ui.button variant="outline" icon="fas fa-arrow-left"
                onclick="location.href='{{ route('penugasan-mengajar.index') }}'">
                Kembali
            </x-ui.button>
        </x-slot>

        <form action="{{ route('penugasan-mengajar.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Guru -->
            <div>
                <x-form.label for="guru_id" required>Guru</x-form.label>
                <x-form.select name="guru_id" required searchable="true">
                    <option value="">Pilih Guru</option>
                    @foreach ($availableGuru as $guru)
                        <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                            {{ $guru->name }} ({{ $guru->email }})
                        </option>
                    @endforeach
                </x-form.select>
                <x-form.error name="guru_id" />
                <p class="mt-1 text-sm text-gray-600">Guru yang akan ditugaskan mengajar</p>
                @if ($availableGuru->isEmpty())
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Tidak ada guru yang tersedia. Pastikan ada user dengan role 'guru'.
                    </p>
                @endif
            </div>

            <!-- Mata Pelajaran -->
            <div>
                <x-form.label for="mata_pelajaran_id" required>Mata Pelajaran</x-form.label>
                <x-form.select name="mata_pelajaran_id" required searchable="true">
                    <option value="">Pilih Mata Pelajaran</option>
                    @foreach (\App\Models\MataPelajaran::all() as $mataPelajaran)
                        <option value="{{ $mataPelajaran->id }}"
                            {{ old('mata_pelajaran_id') == $mataPelajaran->id ? 'selected' : '' }}>
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
                        <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
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
                            {{ old('tahun_ajaran_id') == $tahunAjaran->id ? 'selected' : '' }}>
                            {{ $tahunAjaran->nama }} {{ $tahunAjaran->aktif ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </x-form.select>
                <x-form.error name="tahun_ajaran_id" />
                <p class="mt-1 text-sm text-gray-600">Tahun ajaran untuk penugasan ini</p>
            </div>

            <!-- Jadwal Mengajar -->
            <div id="jadwal-section">
                <x-form.label for="jadwal-section" required>Jadwal Mengajar</x-form.label>
                <div id="jadwal-container" class="space-y-4">
                    <!-- Template for jadwal item -->
                    <div class="jadwal-item border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="text-sm font-medium text-gray-700">Jadwal 1</h4>
                            <button type="button" class="remove-jadwal text-red-600 hover:text-red-800 text-sm"
                                style="display: none;">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Hari -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                                <select name="jadwal[0][hari]"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                    <option value="">Pilih Hari</option>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                </select>
                            </div>
                            <!-- Jam Mulai -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                                <input type="time" name="jadwal[0][jam_mulai]"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>
                            <!-- Jam Selesai -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                                <input type="time" name="jadwal[0][jam_selesai]"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>
                <x-form.error name="jadwal" />
                <div class="mt-3">
                    <button type="button" id="add-jadwal"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-plus mr-2"></i> Tambah Jadwal
                    </button>
                </div>
                <p class="mt-1 text-sm text-gray-600">Jadwal mengajar untuk mata pelajaran ini</p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t">
                <x-ui.button variant="outline" onclick="location.href='{{ route('penugasan-mengajar.index') }}'">
                    Batal
                </x-ui.button>
                <x-ui.button type="submit" variant="primary">
                    Simpan Penugasan
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>

    <script>
        let jadwalIndex = 1;

        document.getElementById('add-jadwal').addEventListener('click', function() {
            const container = document.getElementById('jadwal-container');
            const newJadwal = createJadwalItem(jadwalIndex);
            container.appendChild(newJadwal);
            jadwalIndex++;

            // Show remove buttons if more than 1 jadwal
            updateRemoveButtons();
        });

        function createJadwalItem(index) {
            const div = document.createElement('div');
            div.className = 'jadwal-item border border-gray-200 rounded-lg p-4 bg-gray-50';
            div.innerHTML = `
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-sm font-medium text-gray-700">Jadwal ${index + 1}</h4>
                    <button type="button" class="remove-jadwal text-red-600 hover:text-red-800 text-sm">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                        <select name="jadwal[${index}][hari]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Pilih Hari</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                        <input type="time" name="jadwal[${index}][jam_mulai]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                        <input type="time" name="jadwal[${index}][jam_selesai]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>
            `;

            div.querySelector('.remove-jadwal').addEventListener('click', function() {
                div.remove();
                updateRemoveButtons();
                renumberJadwal();
            });

            return div;
        }

        function updateRemoveButtons() {
            const items = document.querySelectorAll('.jadwal-item');
            const removeButtons = document.querySelectorAll('.remove-jadwal');

            if (items.length > 1) {
                removeButtons.forEach(btn => btn.style.display = 'block');
            } else {
                removeButtons.forEach(btn => btn.style.display = 'none');
            }
        }

        function renumberJadwal() {
            const items = document.querySelectorAll('.jadwal-item');
            items.forEach((item, index) => {
                const title = item.querySelector('h4');
                title.textContent = `Jadwal ${index + 1}`;

                // Update input names
                const selects = item.querySelectorAll('select');
                const inputs = item.querySelectorAll('input');

                selects.forEach(select => {
                    const name = select.name.replace(/\[\d+\]/, `[${index}]`);
                    select.name = name;
                });

                inputs.forEach(input => {
                    const name = input.name.replace(/\[\d+\]/, `[${index}]`);
                    input.name = name;
                });
            });
            jadwalIndex = items.length;
        }

        // Initialize remove buttons
        updateRemoveButtons();
    </script>
</x-layout.dashboard>
