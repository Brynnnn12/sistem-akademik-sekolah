@extends('layouts.app')

@section('title', 'Kenaikan Kelas - Wali Kelas')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Kenaikan Kelas - Wali Kelas</h1>
                <div class="text-sm text-gray-600">
                    Kelas: <span class="font-semibold">{{ $waliKelas->nama_kelas }}</span>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filter Tahun Ajaran -->
            <form method="GET" class="mb-6">
                <div class="flex items-center space-x-4">
                    <label for="tahun_ajaran_id" class="text-sm font-medium text-gray-700">Tahun Ajaran:</label>
                    <select name="tahun_ajaran_id" id="tahun_ajaran_id" onchange="this.form.submit()"
                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @foreach ($tahunAjarans as $ta)
                            <option value="{{ $ta->id }}" {{ $tahunAjaranId == $ta->id ? 'selected' : '' }}>
                                {{ $ta->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

            @if ($tahunAjaran)
                <form method="POST" action="{{ route('promotion.wali-kelas-promote') }}">
                    @csrf
                    <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaran->id }}">
                    <input type="hidden" name="kelas_id" value="{{ $waliKelas->id }}">

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Siswa</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Presensi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nilai Tugas</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nilai Rapor</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Grade</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Keputusan</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Target Kelas</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($rekapData as $index => $data)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $data['siswa']->nama }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                NIS: {{ $data['siswa']->nis }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="space-y-1">
                                                <div>H: {{ $data['presensi']['hadir'] }}</div>
                                                <div>S: {{ $data['presensi']['sakit'] }}</div>
                                                <div>I: {{ $data['presensi']['izin'] }}</div>
                                                <div>A: {{ $data['presensi']['alpha'] }}</div>
                                                <div class="font-semibold">{{ $data['presensi']['persentase_kehadiran'] }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="space-y-1">
                                                <div>Rata-rata: {{ $data['nilai_tugas']['rata_rata_tugas'] }}</div>
                                                <div>Komponen: {{ $data['nilai_tugas']['total_komponen'] }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            {{ $data['nilai_rapor'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if ($data['grade_akhir'] == 'A') bg-green-100 text-green-800
                                            @elseif($data['grade_akhir'] == 'B') bg-blue-100 text-blue-800
                                            @elseif($data['grade_akhir'] == 'C') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                                {{ $data['grade_akhir'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if ($data['status'] == 'Lulus') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                                {{ $data['status'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <select name="promotions[{{ $index }}][decision]"
                                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                onchange="toggleTargetKelas(this, {{ $index }})">
                                                <option value="">Pilih Keputusan</option>
                                                <option value="naik">Naik Kelas</option>
                                                <option value="tidak_naik">Tidak Naik Kelas</option>
                                                @if ($waliKelas->tingkat_kelas === 6)
                                                    <option value="lulus">Lulus</option>
                                                @endif
                                            </select>
                                            <input type="hidden" name="promotions[{{ $index }}][siswa_id]"
                                                value="{{ $data['siswa']->id }}">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <select name="promotions[{{ $index }}][target_kelas_id]"
                                                id="target_kelas_{{ $index }}"
                                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                style="display: none;">
                                                <option value="">Pilih Kelas</option>
                                                @php
                                                    $nextTingkat = $waliKelas->tingkat_kelas + 1;
                                                    $nextKelas = \App\Models\Kelas::where(
                                                        'tingkat_kelas',
                                                        $nextTingkat,
                                                    )->get();
                                                @endphp
                                                @foreach ($nextKelas as $kelas)
                                                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Proses Kenaikan Kelas
                        </button>
                    </div>
                </form>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">Pilih tahun ajaran untuk melihat data rapor siswa.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleTargetKelas(selectElement, index) {
            const targetKelasSelect = document.getElementById('target_kelas_' + index);
            if (selectElement.value === 'naik') {
                targetKelasSelect.style.display = 'block';
                targetKelasSelect.required = true;
            } else {
                targetKelasSelect.style.display = 'none';
                targetKelasSelect.required = false;
            }
        }
    </script>
@endsection
