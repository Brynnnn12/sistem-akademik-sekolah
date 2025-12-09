<x-layout.dashboard>
    <x-slot:title>Presensi Mata Pelajaran</x-slot:title>

    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">📝 Presensi Mata Pelajaran</h1>
                <p class="mt-1 text-sm text-gray-600">Isi kehadiran siswa per mata pelajaran</p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('presensi-mapel.jurnal') }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-150">
                    <i class="fas fa-book mr-2"></i>
                    Jurnal Mengajar
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-3"></i>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Info Hari & Tanggal -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold">{{ $hariIni }},
                        {{ \Carbon\Carbon::parse($tanggalHariIni)->isoFormat('D MMMM Y') }}</h2>
                    <p class="mt-1 text-blue-100">{{ $jadwalHariIni->count() }} jadwal mengajar hari ini</p>
                </div>
                <div class="text-5xl opacity-50">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>

        @if ($jadwalHariIni->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                <i class="fas fa-coffee text-yellow-600 text-4xl mb-3"></i>
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">Tidak Ada Jadwal Mengajar Hari Ini</h3>
                <p class="text-yellow-700">Anda tidak memiliki jadwal mengajar pada hari {{ $hariIni }}.</p>
                <p class="text-yellow-600 text-sm mt-2">Nikmati waktu luang Anda! ☕</p>
            </div>
        @else
            <!-- Jadwal Mengajar Hari Ini -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-clipboard-list mr-2 text-green-600"></i>
                    Jadwal Mengajar Hari Ini
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($jadwalHariIni as $jadwal)
                        @php
                            $penugasan = $jadwal->penugasanMengajar;
                        @endphp
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-base font-semibold text-gray-900">
                                        {{ $penugasan->mataPelajaran->nama }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-door-open mr-1"></i>
                                        {{ $penugasan->kelas->nama_lengkap }}
                                    </p>
                                    <p class="text-sm text-blue-600 font-medium mt-2">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $jadwal->jam_mulai->format('H:i') }} -
                                        {{ $jadwal->jam_selesai->format('H:i') }}
                                    </p>
                                </div>
                                <div class="ml-4">
                                    <a href="{{ route('presensi-mapel.create', ['jadwal_id' => $jadwal->id]) }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                                        <i class="fas fa-arrow-right mr-2"></i>
                                        Input Presensi
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($penugasanList->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                <i class="fas fa-info-circle text-yellow-600 text-4xl mb-3"></i>
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">Tidak Ada Penugasan Mengajar</h3>
                <p class="text-yellow-700">Anda belum ditugaskan mengajar untuk tahun ajaran aktif saat ini.</p>
            </div>
        @else
            <!-- Form Manual (Jika Tidak Ada Jadwal atau Ingin Input Manual) -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-edit mr-2 text-purple-600"></i>
                    Input Manual (Tanpa Jadwal)
                </h2>
                <p class="text-sm text-gray-600 mb-4">Gunakan form ini jika Anda ingin mengisi presensi untuk
                    tanggal/jam yang berbeda atau tidak sesuai jadwal.</p>

                <form action="{{ route('presensi-mapel.create') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Pilih dari Penugasan -->
                        <div>
                            <x-form.label for="penugasan_id" required>Penugasan Mengajar</x-form.label>
                            <select name="penugasan_id" id="penugasan_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Penugasan</option>
                                @foreach ($penugasanList as $penugasan)
                                    <option value="{{ $penugasan->id }}" data-kelas="{{ $penugasan->kelas_id }}"
                                        data-mapel="{{ $penugasan->mata_pelajaran_id }}">
                                        {{ $penugasan->mataPelajaran->nama }} - {{ $penugasan->kelas->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tanggal -->
                        <div>
                            <x-form.label for="tanggal" required>Tanggal</x-form.label>
                            <x-form.input type="date" name="tanggal" id="tanggal" required
                                value="{{ old('tanggal', date('Y-m-d')) }}" />
                        </div>

                        <!-- Jam Mulai (Opsional) -->
                        <div>
                            <x-form.label for="jam_mulai">Jam Mulai</x-form.label>
                            <x-form.input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai') }}"
                                placeholder="07:00" />
                            <p class="mt-1 text-xs text-gray-500">Opsional: Untuk membedakan jam pelajaran</p>
                        </div>
                    </div>

                    <!-- Hidden inputs untuk kelas_id dan mata_pelajaran_id -->
                    <input type="hidden" name="kelas_id" id="kelas_id">
                    <input type="hidden" name="mata_pelajaran_id" id="mata_pelajaran_id">

                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-150">
                            <i class="fas fa-arrow-right mr-2"></i>
                            Lanjut ke Input Presensi
                        </button>
                    </div>
                </form>
            </div>

            <!-- Info Penugasan Mengajar -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-chalkboard-teacher mr-2 text-purple-600"></i>
                    Penugasan Mengajar Anda
                </h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mata Pelajaran
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kelas
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tahun Ajaran
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($penugasanList as $penugasan)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <i class="fas fa-book text-blue-600 mr-2"></i>
                                            <span class="text-sm font-medium text-gray-900">
                                                {{ $penugasan->mataPelajaran->nama }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="text-sm text-gray-900">{{ $penugasan->kelas->nama_lengkap }}</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $penugasan->tahunAjaran->nama }} -
                                            {{ ucfirst($penugasan->tahunAjaran->semester) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            // Auto-fill kelas_id dan mata_pelajaran_id based on penugasan selection
            document.getElementById('penugasan_id').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                document.getElementById('kelas_id').value = selectedOption.dataset.kelas || '';
                document.getElementById('mata_pelajaran_id').value = selectedOption.dataset.mapel || '';
            });
        </script>
    @endpush
</x-layout.dashboard>
