<x-layout.dashboard title="Kelulusan Siswa">
    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Proses Kelulusan Siswa Kelas Akhir</h3>
            <x-ui.button variant="outline" icon="fas fa-arrow-left" onclick="location.href='{{ route('dashboard') }}'">
                Kembali
            </x-ui.button>
        </x-slot>

        <form id="graduationForm" class="space-y-6">
            @csrf

            <!-- Info Alert -->
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">
                            Kelulusan Siswa Kelas Akhir
                        </h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>Fitur ini khusus untuk meluluskan siswa dari kelas akhir (tingkat 6). Pastikan siswa yang
                                dipilih sudah memenuhi syarat kelulusan.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Class Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="tahun_ajaran_id" class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
                    <select id="tahun_ajaran_id" name="tahun_ajaran_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih Tahun Ajaran</option>
                        @foreach ($tahunAjarans as $tahun)
                            <option value="{{ $tahun->id }}">{{ $tahun->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700">Kelas Akhir (Tingkat
                        6)</label>
                    <select id="kelas_id" name="kelas_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih Kelas Akhir</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="button" id="loadStudents"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Muat Daftar Siswa
            </button>
        </form>

        <!-- Students Table -->
        <div id="studentsSection" class="mt-8 hidden">
            <form id="graduateForm" action="{{ route('graduation.graduate') }}" method="POST">
                @csrf

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Daftar Siswa Kelas Akhir yang Akan Lulus</h2>
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Proses Kelulusan
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead>
                            <tr class="bg-gray-50">
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="selectAll" class="rounded">
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    NIS</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jenis Kelamin</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody id="studentsTableBody">
                            <!-- Students will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </x-ui.card>

    <script>
        document.getElementById('loadStudents').addEventListener('click', function() {
            const tahun = document.getElementById('tahun_ajaran_id').value;
            const kelas = document.getElementById('kelas_id').value;

            if (!tahun || !kelas) {
                alert('Harap pilih tahun ajaran dan kelas akhir terlebih dahulu.');
                return;
            }

            fetch('{{ route('graduation.students') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        tahun_ajaran_id: tahun,
                        kelas_id: kelas
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('studentsTableBody');
                    tbody.innerHTML = '';

                    data.forEach(siswa => {
                        const row = `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" name="siswa_ids[]" value="${siswa.id}" class="student-checkbox rounded">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">${siswa.nis}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${siswa.nama}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${siswa.jenis_kelamin}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${siswa.status}</td>
                    </tr>
                `;
                        tbody.innerHTML += row;
                    });

                    document.getElementById('studentsSection').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat data siswa.');
                });
        });

        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.student-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
</x-layout.dashboard>
