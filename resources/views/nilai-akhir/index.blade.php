<x-layout.dashboard title="Nilai Akhir">
    <!-- Flash Messages -->
    <x-ui.flash-messages />

    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Nilai Akhir</h3>
        </x-slot>

        <!-- Filter Form -->
        <div class="mb-6">
            <form method="GET" action="{{ route('nilai-akhir.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="mata_pelajaran_id" class="block text-sm font-medium text-gray-700 mb-2">Mata
                        Pelajaran</label>
                    <select id="mata_pelajaran_id" name="mata_pelajaran_id"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach ($mataPelajarans as $mp)
                            <option value="{{ $mp->id }}" {{ $mataPelajaranId == $mp->id ? 'selected' : '' }}>
                                {{ $mp->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tahun_ajaran_id" class="block text-sm font-medium text-gray-700 mb-2">Tahun
                        Ajaran</label>
                    <select id="tahun_ajaran_id" name="tahun_ajaran_id"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Pilih Tahun Ajaran</option>
                        @foreach ($tahunAjarans as $ta)
                            <option value="{{ $ta->id }}" {{ $tahunAjaranId == $ta->id ? 'selected' : '' }}>
                                {{ $ta->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>

                    @if ($mataPelajaranId && $tahunAjaranId)
                        @can('generate', [App\Models\NilaiAkhir::class, $mataPelajaranId, $tahunAjaranId])
                            <button type="button" id="generateBtn"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-cog mr-2"></i>Generate Nilai Akhir
                            </button>
                        @endcan
                    @endif
                </div>
            </form>
        </div>

        @if ($mataPelajaran && $tahunAjaran)
            <!-- Info Section -->
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mb-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-500 rounded-lg">
                        <i class="fas fa-book text-white"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-semibold text-blue-900">{{ $mataPelajaran->nama }}</h4>
                        <p class="text-sm text-blue-700">Tahun Ajaran: {{ $tahunAjaran->nama }}</p>
                        <p class="text-sm text-blue-600">Total Siswa: {{ $nilaiAkhirs->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Nilai Akhir Table -->
            <x-ui.table :headers="['No', 'NIS', 'Nama Siswa', 'Nilai Akhir', 'Grade', 'Status']" striped hover>
                @forelse($nilaiAkhirs as $index => $nilaiAkhir)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $nilaiAkhir->siswa->nis ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $nilaiAkhir->siswa->nama }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="text-sm font-medium text-gray-900">{{ number_format($nilaiAkhir->nilai_akhir, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $gradeColor = match ($nilaiAkhir->grade) {
                                    'A' => 'bg-green-100 text-green-800',
                                    'B' => 'bg-blue-100 text-blue-800',
                                    'C' => 'bg-yellow-100 text-yellow-800',
                                    'D' => 'bg-orange-100 text-orange-800',
                                    'E' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $gradeColor }}">
                                {{ $nilaiAkhir->grade }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($nilaiAkhir->nilai_akhir >= 75)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Lulus
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Tidak Lulus
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-chart-line text-4xl text-gray-300 mb-2"></i>
                                <p>Belum ada nilai akhir yang di-generate</p>
                                <p class="text-sm text-gray-400 mt-1">Klik tombol "Generate Nilai Akhir" untuk
                                    menghitung nilai akhir siswa</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-ui.table>

            @if ($nilaiAkhirs->isNotEmpty())
                <!-- Statistics -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-500 rounded-lg">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-600">Lulus</p>
                                <p class="text-2xl font-bold text-green-900">
                                    {{ $nilaiAkhirs->where('nilai_akhir', '>=', 75)->count() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-red-500 rounded-lg">
                                <i class="fas fa-times-circle text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-red-600">Tidak Lulus</p>
                                <p class="text-2xl font-bold text-red-900">
                                    {{ $nilaiAkhirs->where('nilai_akhir', '<', 75)->count() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-500 rounded-lg">
                                <i class="fas fa-chart-line text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-blue-600">Rata-rata</p>
                                <p class="text-2xl font-bold text-blue-900">
                                    {{ number_format($nilaiAkhirs->avg('nilai_akhir'), 2) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-500 rounded-lg">
                                <i class="fas fa-trophy text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-purple-600">Grade Tertinggi</p>
                                <p class="text-2xl font-bold text-purple-900">
                                    {{ $nilaiAkhirs->where('grade', 'A')->count() }} siswa
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <!-- No Selection Message -->
            <div class="text-center py-12">
                <i class="fas fa-filter text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Pilih Mata Pelajaran dan Tahun Ajaran</h3>
                <p class="text-gray-500">Silakan pilih mata pelajaran dan tahun ajaran untuk melihat nilai akhir siswa
                </p>
            </div>
        @endif
    </x-ui.card>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.getElementById('generateBtn')?.addEventListener('click', function() {
                const mataPelajaranId = document.getElementById('mata_pelajaran_id').value;
                const tahunAjaranId = document.getElementById('tahun_ajaran_id').value;

                Swal.fire({
                    title: 'Generate Nilai Akhir?',
                    text: 'Apakah Anda yakin ingin menghitung ulang nilai akhir untuk semua siswa?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Generate!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Sedang memproses...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Send request
                        fetch('{{ route('nilai-akhir.generate') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    mata_pelajaran_id: mataPelajaranId,
                                    tahun_ajaran_id: tahunAjaranId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: data.message,
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: data.message,
                                        icon: 'error'
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan saat memproses request',
                                    icon: 'error'
                                });
                            });
                    }
                });
            });
        </script>
    @endpush
</x-layout.dashboard>
