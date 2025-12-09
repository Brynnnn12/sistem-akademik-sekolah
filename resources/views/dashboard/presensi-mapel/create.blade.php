<x-layout.dashboard>
    <x-slot:title>Input Presensi - {{ $mataPelajaran->nama }} - {{ $kelas->nama_lengkap }}</x-slot:title>

    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">📝 Input Presensi</h1>
                <p class="mt-1 text-sm text-gray-600">
                    {{ $mataPelajaran->nama }} - {{ $kelas->nama_lengkap }} |
                    {{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y') }}
                </p>
            </div>

            <a href="{{ route('presensi-mapel.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-150">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>

        @if (session('error'))
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-red-600 mr-3 mt-0.5"></i>
                    <div>
                        <p class="text-red-800 font-medium mb-2">Terdapat kesalahan pada input:</p>
                        <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('presensi-mapel.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Hidden Inputs -->
            <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
            <input type="hidden" name="mata_pelajaran_id" value="{{ $mataPelajaran->id }}">
            <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaran->id }}">
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
            <input type="hidden" name="jam_mulai" value="{{ $jamMulai }}">

            <!-- Jurnal Mengajar (Materi) -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-book-open mr-2 text-green-600"></i>
                    Jurnal Mengajar
                </h3>

                <div>
                    <x-form.label for="materi">Materi / Topik Pembelajaran</x-form.label>
                    <textarea name="materi" id="materi" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Contoh: Perkalian bilangan bulat, latihan soal bab 3, dll.">{{ old('materi', $materiExisting) }}</textarea>
                    <x-form.error name="materi" />
                    <p class="mt-1 text-xs text-gray-500">Opsional: Catat materi yang diajarkan hari ini</p>
                </div>
            </div>

            <!-- Daftar Siswa & Status Kehadiran -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-users mr-2 text-blue-600"></i>
                    Daftar Kehadiran Siswa ({{ $siswaList->count() }} Siswa)
                </h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                                    No
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Siswa
                                </th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hadir
                                </th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sakit
                                </th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Izin
                                </th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Alpha
                                </th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bolos
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Catatan
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($siswaList as $index => $kelasSiswa)
                                @php
                                    $siswa = $kelasSiswa->siswa;
                                    $presensiExist = $presensiExisting->get($siswa->id);
                                    $currentStatus = old(
                                        "presensi.{$siswa->id}.status",
                                        $presensiExist?->status ?? 'H',
                                    );
                                    $currentCatatan = old(
                                        "presensi.{$siswa->id}.catatan",
                                        $presensiExist?->catatan ?? '',
                                    );
                                @endphp

                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span
                                                    class="text-blue-600 font-medium text-xs">{{ substr($siswa->nama, 0, 2) }}</span>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $siswa->nama }}</p>
                                                <p class="text-xs text-gray-500">NIS: {{ $siswa->nis }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]"
                                            value="H" {{ $currentStatus === 'H' ? 'checked' : '' }}
                                            class="w-4 h-4 text-green-600 focus:ring-green-500">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]"
                                            value="S" {{ $currentStatus === 'S' ? 'checked' : '' }}
                                            class="w-4 h-4 text-yellow-600 focus:ring-yellow-500">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]"
                                            value="I" {{ $currentStatus === 'I' ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]"
                                            value="A" {{ $currentStatus === 'A' ? 'checked' : '' }}
                                            class="w-4 h-4 text-red-600 focus:ring-red-500">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]"
                                            value="B" {{ $currentStatus === 'B' ? 'checked' : '' }}
                                            class="w-4 h-4 text-purple-600 focus:ring-purple-500">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" name="presensi[{{ $siswa->id }}][catatan]"
                                            value="{{ $currentCatatan }}" placeholder="Catatan (opsional)"
                                            class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Legend -->
                <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Keterangan Status:</h4>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 text-sm">
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                            <span class="text-gray-700"><strong>H</strong> = Hadir</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></span>
                            <span class="text-gray-700"><strong>S</strong> = Sakit</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span>
                            <span class="text-gray-700"><strong>I</strong> = Izin</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                            <span class="text-gray-700"><strong>A</strong> = Alpha</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-purple-500 mr-2"></span>
                            <span class="text-gray-700"><strong>B</strong> = Bolos</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('presensi-mapel.index') }}"
                    class="inline-flex items-center px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-150">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-150">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Presensi
                </button>
            </div>
        </form>
    </div>
</x-layout.dashboard>
