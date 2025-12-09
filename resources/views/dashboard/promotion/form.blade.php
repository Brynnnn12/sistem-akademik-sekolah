<x-layout.dashboard title="Kenaikan Kelas">
    <x-ui.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">Proses Kenaikan Kelas</h3>
            <x-ui.button variant="outline" icon="fas fa-arrow-left" onclick="location.href='{{ route('dashboard') }}'">
                Kembali
            </x-ui.button>
        </x-slot>

        <form id="promotionForm" class="space-y-6">
            @csrf

            <!-- Source Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="source_tahun_ajaran_id" class="block text-sm font-medium text-gray-700">Tahun Ajaran
                        Asal</label>
                    <select id="source_tahun_ajaran_id" name="source_tahun_ajaran_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih Tahun Ajaran</option>
                        @foreach ($tahunAjarans as $tahun)
                            <option value="{{ $tahun->id }}">{{ $tahun->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="source_kelas_id" class="block text-sm font-medium text-gray-700">Kelas Asal</label>
                    <select id="source_kelas_id" name="source_kelas_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih Kelas</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id }}" data-tingkat="{{ $k->tingkat_kelas }}">
                                {{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Target Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="target_tahun_ajaran_id" class="block text-sm font-medium text-gray-700">Tahun Ajaran
                        Tujuan</label>
                    <select id="target_tahun_ajaran_id" name="target_tahun_ajaran_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih Tahun Ajaran</option>
                        @foreach ($tahunAjarans as $tahun)
                            <option value="{{ $tahun->id }}">{{ $tahun->nama }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Biasanya sama dengan tahun ajaran asal</p>
                </div>

                <div>
                    <label for="target_kelas_id" class="block text-sm font-medium text-gray-700">Kelas Tujuan</label>
                    <select id="target_kelas_id" name="target_kelas_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        disabled>
                        <option value="">Pilih Kelas Asal terlebih dahulu</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Otomatis terisi berdasarkan kelas asal + 1 tingkat</p>
                </div>
            </div>

            <button type="button" id="loadStudents"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Muat Daftar Siswa
            </button>
        </form>

        <!-- Students Table -->
        <div id="studentsSection" class="mt-8 hidden">
            <form id="promoteForm" action="{{ route('promotion.promote') }}" method="POST">
                @csrf
                <input type="hidden" name="target_kelas_id" id="promote_target_kelas_id">
                <input type="hidden" name="target_tahun_ajaran_id" id="promote_target_tahun_ajaran_id">
                <input type="hidden" name="source_kelas_id" id="promote_source_kelas_id">
                <input type="hidden" name="source_tahun_ajaran_id" id="promote_source_tahun_ajaran_id">

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Daftar Siswa</h2>
                    <button type="submit" id="submitBtn"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Proses Kenaikan Kelas
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
            const sourceTahun = document.getElementById('source_tahun_ajaran_id').value;
            const sourceKelas = document.getElementById('source_kelas_id').value;
            const targetTahun = document.getElementById('target_tahun_ajaran_id').value;
            const targetKelas = document.getElementById('target_kelas_id').value;

            // Check if this is graduation (grade 6)
            const sourceOption = document.getElementById('source_kelas_id').querySelector(
                `option[value="${sourceKelas}"]`);
            const isGraduation = sourceOption && parseInt(sourceOption.getAttribute('data-tingkat')) === 6;

            if (!sourceTahun || !sourceKelas || !targetTahun || (!isGraduation && !targetKelas)) {
                alert('Harap pilih semua field terlebih dahulu.');
                return;
            }

            // Set hidden inputs for form submission
            document.getElementById('promote_source_kelas_id').value = sourceKelas;
            document.getElementById('promote_source_tahun_ajaran_id').value = sourceTahun;
            document.getElementById('promote_target_kelas_id').value = isGraduation ? '' : targetKelas;
            document.getElementById('promote_target_tahun_ajaran_id').value = targetTahun;

            // Update submit button text based on graduation or promotion
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.textContent = isGraduation ? 'Proses Kelulusan' : 'Proses Kenaikan Kelas';

            fetch('{{ route('promotion.students') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        source_tahun_ajaran_id: sourceTahun,
                        source_kelas_id: sourceKelas
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
                    </tr>
                `;
                        tbody.innerHTML += row;
                    });

                    document.getElementById('promote_target_kelas_id').value = targetKelas;
                    document.getElementById('promote_target_tahun_ajaran_id').value = targetTahun;
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

        // Logic for automatic target class selection based on source class
        document.getElementById('source_kelas_id').addEventListener('change', function() {
            const sourceKelasId = this.value;
            const targetKelasSelect = document.getElementById('target_kelas_id');
            const targetTahunAjaranSelect = document.getElementById('target_tahun_ajaran_id');
            const loadStudentsBtn = document.getElementById('loadStudents');

            if (!sourceKelasId) {
                targetKelasSelect.disabled = true;
                targetKelasSelect.innerHTML = '<option value="">Pilih Kelas Asal terlebih dahulu</option>';
                loadStudentsBtn.disabled = true;
                return;
            }

            // Get source class level from data attribute
            const sourceOption = this.querySelector(`option[value="${sourceKelasId}"]`);
            const sourceTingkat = parseInt(sourceOption.getAttribute('data-tingkat'));

            // If source is grade 6, this is graduation (no target class)
            if (sourceTingkat === 6) {
                targetKelasSelect.disabled = true;
                targetKelasSelect.innerHTML =
                    '<option value="">Kelas 6 akan lulus (tidak ada kelas tujuan)</option>';
                loadStudentsBtn.disabled = false;
                return;
            }

            // Calculate target grade (source + 1)
            const targetTingkat = sourceTingkat + 1;

            // Filter classes with target grade
            const allKelasOptions = @json($kelas);
            const targetKelasOptions = allKelasOptions.filter(kelas => parseInt(kelas.tingkat_kelas) ===
                targetTingkat);

            // Populate target class dropdown
            targetKelasSelect.innerHTML = '<option value="">Pilih Kelas Tujuan</option>';
            targetKelasOptions.forEach(kelas => {
                const option = document.createElement('option');
                option.value = kelas.id;
                option.textContent = kelas.nama;
                option.setAttribute('data-tingkat', kelas.tingkat_kelas);
                targetKelasSelect.appendChild(option);
            });

            targetKelasSelect.disabled = false;
            loadStudentsBtn.disabled = false;

            // Auto-select first available target class if only one option
            if (targetKelasOptions.length === 1) {
                targetKelasSelect.value = targetKelasOptions[0].id;
            }
        });

        // Enable/disable load students button based on selections
        document.getElementById('target_tahun_ajaran_id').addEventListener('change', function() {
            const loadStudentsBtn = document.getElementById('loadStudents');
            const sourceKelasId = document.getElementById('source_kelas_id').value;
            const targetTahunAjaranId = this.value;
            const targetKelasId = document.getElementById('target_kelas_id').value;

            // For graduation (grade 6), only need source class and target year
            const sourceOption = document.getElementById('source_kelas_id').querySelector(
                `option[value="${sourceKelasId}"]`);
            const isGraduation = sourceOption && parseInt(sourceOption.getAttribute('data-tingkat')) === 6;

            loadStudentsBtn.disabled = !(sourceKelasId && targetTahunAjaranId && (isGraduation || targetKelasId));
        });

        document.getElementById('target_kelas_id').addEventListener('change', function() {
            const loadStudentsBtn = document.getElementById('loadStudents');
            const sourceKelasId = document.getElementById('source_kelas_id').value;
            const targetTahunAjaranId = document.getElementById('target_tahun_ajaran_id').value;
            const targetKelasId = this.value;

            loadStudentsBtn.disabled = !(sourceKelasId && targetTahunAjaranId && targetKelasId);
        });
    </script>
</x-layout.dashboard>
