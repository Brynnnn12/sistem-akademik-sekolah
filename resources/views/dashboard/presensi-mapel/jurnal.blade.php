<x-layout.dashboard>
    <x-slot:title>Jurnal Mengajar</x-slot:title>

    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">📚 Jurnal Mengajar</h1>
                <p class="mt-1 text-sm text-gray-600">Riwayat materi pembelajaran yang telah diajarkan</p>
            </div>

            <a href="{{ route('presensi-mapel.index') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-150">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-filter mr-2 text-blue-600"></i>
                Filter Jurnal
            </h3>

            <form action="{{ route('presensi-mapel.jurnal') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Filter Kelas -->
                    <div>
                        <x-form.label for="kelas_id">Kelas</x-form.label>
                        <select name="kelas_id" id="kelas_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Kelas</option>
                            @foreach ($kelasList as $kelas)
                                <option value="{{ $kelas->id }}"
                                    {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Mata Pelajaran -->
                    <div>
                        <x-form.label for="mata_pelajaran_id">Mata Pelajaran</x-form.label>
                        <select name="mata_pelajaran_id" id="mata_pelajaran_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Mata Pelajaran</option>
                            @foreach ($mapelList as $mapel)
                                <option value="{{ $mapel->id }}"
                                    {{ request('mata_pelajaran_id') == $mapel->id ? 'selected' : '' }}>
                                    {{ $mapel->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div>
                        <x-form.label for="start_date">Dari Tanggal</x-form.label>
                        <x-form.input type="date" name="start_date" id="start_date"
                            value="{{ request('start_date') }}" />
                    </div>

                    <!-- Tanggal Akhir -->
                    <div>
                        <x-form.label for="end_date">Sampai Tanggal</x-form.label>
                        <x-form.input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" />
                    </div>
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('presensi-mapel.jurnal') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-150">
                        <i class="fas fa-redo mr-2"></i>
                        Reset
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-150">
                        <i class="fas fa-search mr-2"></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Jurnal List -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-list mr-2 text-green-600"></i>
                    Daftar Jurnal ({{ $total }} Entri)
                </h3>
            </div>

            @if ($jurnal->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada jurnal mengajar yang tercatat.</p>
                    <p class="text-gray-400 text-sm mt-2">Mulai isi presensi untuk mencatat jurnal mengajar Anda.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($jurnal as $item)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                <div class="flex-1">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-calendar-day text-blue-600"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-base font-semibold text-gray-900 mb-1">
                                                {{ $item->mataPelajaran->nama }} - {{ $item->kelas->nama_lengkap }}
                                            </h4>
                                            <p class="text-sm text-gray-700 mb-2">
                                                <i class="fas fa-book-open text-green-600 mr-1"></i>
                                                {{ $item->materi }}
                                            </p>
                                            <div class="flex flex-wrap gap-3 text-xs text-gray-500">
                                                <span>
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('dddd, D MMMM Y') }}
                                                </span>
                                                @if ($item->jam_mulai)
                                                    <span>
                                                        <i class="fas fa-clock mr-1"></i>
                                                        {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}
                                                        WIB
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layout.dashboard>
