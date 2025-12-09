<x-layout.dashboard title="Detail Kelas">
    {{-- 1. ASSETS & STYLES (Dipush ke Head) --}}
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
        <style>
            .ts-control {
                border-radius: 0.375rem;
                padding: 0.5rem 0.75rem;
                border-color: #d1d5db;
            }

            .ts-control:focus {
                box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
                border-color: #3b82f6;
            }

            /* Fix z-index agar dropdown tidak tertutup modal */
            .ts-dropdown {
                z-index: 60;
            }
        </style>
    @endpush

    {{-- 2. MAIN CONTENT --}}
    {{-- Kita bungkus dengan x-data untuk state modal --}}
    <div x-data="{ showModal: false }" class="space-y-6">

        <x-ui.card>
            {{-- HEADER --}}
            <x-slot name="header">
                <h3 class="text-lg font-semibold text-gray-800">Detail Kelas: {{ $kelas->nama_lengkap }}</h3>
                <div class="flex space-x-2">
                    @can('update', $kelas)
                        <x-ui.button variant="outline" icon="fas fa-edit"
                            onclick="location.href='{{ route('kelas.edit', $kelas) }}'">
                            Edit
                        </x-ui.button>
                    @endcan
                    <x-ui.button variant="outline" icon="fas fa-arrow-left"
                        onclick="location.href='{{ route('kelas.index') }}'">
                        Kembali
                    </x-ui.button>
                </div>
            </x-slot>

            <div class="space-y-6">
                {{-- INFO CARDS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Informasi Dasar --}}
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-info-circle mr-2 text-blue-600"></i> Informasi Dasar
                        </h4>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama Kelas</dt>
                                <dd class="text-sm text-gray-900 mt-1">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $kelas->nama }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tingkat</dt>
                                <dd class="text-sm text-gray-900 mt-1">Tingkat {{ $kelas->tingkat_kelas }}
                                    ({{ $kelas->tingkat_romawi }})</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Wali Kelas</dt>
                                <dd class="text-sm text-gray-900 mt-1">
                                    {{ $kelas->waliKelas->name ?? 'Belum ada wali kelas' }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Daftar Siswa Summary --}}
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-users mr-2 text-purple-600"></i> Daftar Siswa
                                <span
                                    class="text-sm font-normal text-gray-600 ml-1">({{ $siswaDiKelas->count() }})</span>
                            </h4>

                            {{-- Tombol Buka Modal (Pakai Alpine @click) --}}
                            @can('update', $kelas)
                                @if ($tahunAjaranAktif && $siswaTersedia->count() > 0)
                                    <button type="button" @click="showModal = true"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition">
                                        <i class="fas fa-plus mr-1"></i> Tambah
                                    </button>
                                @endif
                            @endcan
                        </div>

                        {{-- Tabel Siswa --}}
                        @if ($siswaDiKelas->count() > 0)
                            <div class="overflow-x-auto max-h-60 custom-scrollbar">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50 sticky top-0">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                NIS</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Nama</th>
                                            <th
                                                class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">
                                                Kehadiran</th>
                                            <th
                                                class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($siswaDiKelas as $ks)
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-900">{{ $ks->siswa->nis }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-900">{{ $ks->siswa->nama }}</td>
                                                <td class="px-4 py-2 text-sm text-center">
                                                    @php
                                                        $stats = $ks->siswa->attendance_stats ?? [
                                                            'hadir' => 0,
                                                            'sakit' => 0,
                                                            'izin' => 0,
                                                            'alpha' => 0,
                                                            'total' => 0,
                                                        ];
                                                    @endphp
                                                    <div class="flex justify-center space-x-2 text-xs">
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-800">
                                                            <i class="fas fa-check mr-1"></i>{{ $stats['hadir'] }}
                                                        </span>
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded-full bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-sick mr-1"></i>{{ $stats['sakit'] }}
                                                        </span>
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                                                            <i class="fas fa-envelope mr-1"></i>{{ $stats['izin'] }}
                                                        </span>
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded-full bg-red-100 text-red-800">
                                                            <i class="fas fa-times mr-1"></i>{{ $stats['alpha'] }}
                                                        </span>
                                                    </div>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        Total: {{ $stats['total'] }} pertemuan
                                                    </div>
                                                </td>
                                                <td class="px-4 py-2 text-sm font-medium text-right">
                                                    @can('update', $kelas)
                                                        <button type="button"
                                                            onclick="deleteSiswa({{ $ks->id }}, '{{ $ks->siswa->nama }}')"
                                                            class="text-red-600 hover:text-red-900"
                                                            title="Hapus dari kelas">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-6 text-gray-500 text-sm">Belum ada siswa di kelas ini.</div>
                        @endif
                    </div>
                </div>

                {{-- Delete Class Section --}}
                @can('delete', $kelas)
                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <x-ui.button variant="danger" icon="fas fa-trash"
                            onclick="confirmDelete('{{ route('kelas.destroy', $kelas) }}', '{{ $kelas->nama }}')">Hapus
                            Kelas</x-ui.button>
                    </div>
                @endcan
            </div>
        </x-ui.card>

        {{-- 3. MODAL (Controlled by Alpine) --}}
        @if ($tahunAjaranAktif && $siswaTersedia->count() > 0)
            <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
                aria-labelledby="modal-title" role="dialog" aria-modal="true">

                {{-- Backdrop --}}
                <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>

                {{-- Modal Panel --}}
                <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                    <div x-show="showModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg w-full">

                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah
                                        Siswa</h3>
                                    <div class="mt-4">
                                        <form action="{{ route('kelas.add-siswa', $kelas) }}" method="POST"
                                            id="addSiswaForm">
                                            @csrf
                                            <input type="hidden" name="tahun_ajaran_id"
                                                value="{{ $tahunAjaranAktif->id }}">

                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih
                                                    Siswa <span class="text-xs text-gray-500">(bisa pilih
                                                        banyak)</span></label>
                                                <select name="siswa_id[]" id="select-siswa" autocomplete="off"
                                                    placeholder="Cari dan pilih siswa..." multiple>
                                                    @foreach ($siswaTersedia as $siswa)
                                                        <option value="{{ $siswa->id }}">{{ $siswa->nis }} -
                                                            {{ $siswa->nama }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-xs text-gray-500 mt-1">Gunakan Ctrl+Click atau drag
                                                    untuk memilih banyak siswa</p>
                                            </div>

                                            <div class="flex justify-end space-x-3 mt-6">
                                                <button type="button" @click="showModal = false"
                                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Batal</button>
                                                <button type="submit"
                                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Bulk Errors Alert --}}
        @if (session('bulk_errors'))
            <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Beberapa siswa gagal ditambahkan:</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach (session('bulk_errors') as $error)
                                    <li>Siswa ID {{ $error['siswa_id'] }}: {{ $error['message'] }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Sweet Confirm Delete Kelas --}}
        <x-sweet.sweet-confirm title="Hapus Kelas?" text="Yakin hapus kelas {{ $kelas->nama }}?"
            action="{{ route('kelas.destroy', $kelas) }}" method="DELETE" />
    </div>

    {{-- 4. SCRIPTS (Dipush ke Bawah Body) --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
        <script>
            // Global delete confirmation function
            function confirmDelete(deleteUrl, namaItem) {
                Swal.fire({
                    title: 'Hapus Kelas?',
                    text: `Apakah Anda yakin ingin menghapus kelas ${namaItem}? Aksi ini tidak bisa dibatalkan!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Membuat form submit secara dinamis
                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = deleteUrl;

                        // Menambahkan CSRF Token
                        let csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';
                        form.appendChild(csrfInput);

                        // Menambahkan Method DELETE spoofing
                        let methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        form.appendChild(methodInput);

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            // 1. Inisialisasi Tom Select
            document.addEventListener('DOMContentLoaded', function() {
                const selectEl = document.getElementById('select-siswa');
                if (selectEl) {
                    new TomSelect(selectEl, {
                        create: false,
                        sortField: {
                            field: "text",
                            direction: "asc"
                        },
                        placeholder: "Cari dan pilih siswa...",
                        plugins: ['clear_button', 'remove_button'],
                        dropdownParent: 'body', // Penting: agar dropdown muncul di atas modal z-index
                        maxItems: null, // Unlimited selection
                        hideSelected: true, // Hide selected items from dropdown
                        closeAfterSelect: false, // Keep dropdown open after selection
                        render: {
                            option: function(data, escape) {
                                return '<div>' + escape(data.text) + '</div>';
                            },
                            item: function(data, escape) {
                                return '<div class="flex items-center"><span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded mr-2">SISWA</span>' +
                                    escape(data.text) + '</div>';
                            }
                        }
                    });
                }
            });

            // 2. Fungsi Delete Siswa (Tanpa Form Builder manual yang panjang)
            function deleteSiswa(id, name) {
                if (confirm(`Hapus ${name} dari kelas ini?`)) {
                    // Buat form hidden element secara ringkas
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ route('kelas.remove-siswa', [$kelas, ':kelasSiswaId']) }}`.replace(':kelasSiswaId', id);

                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;

                    document.body.appendChild(form);
                    form.submit();
                }
            }
        </script>
    @endpush
</x-layout.dashboard>
