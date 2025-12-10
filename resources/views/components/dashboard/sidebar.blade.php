<aside class="w-64 bg-blue-800 text-white hidden md:flex flex-col h-full flex-shrink-0 transition-all duration-300">
    <div class="h-16 flex items-center justify-center z-10">
        <div class="text-2xl font-bold flex items-center">
            <i class="fas fa-chart-line mr-2"></i>
            <span>DashBoard</span>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 custom-scrollbar">
        <div class="mb-6">
            <div class="px-2 space-y-1">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                    <i class="fas fa-home mr-3 w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2">Manajemen Akademik</h3>
            <div class="px-2 space-y-1">

                <a href="{{ route('siswa.index') }}"
                    class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('siswa.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                    <i class="fas fa-users mr-3 w-5 text-center"></i>
                    <span>Siswa</span>
                </a>

                @hasanyrole('Admin|KepalaSekolah')
                    <a href="{{ route('tahun-ajaran.index') }}"
                        class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('tahun-ajaran.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-calendar-alt mr-3 w-5 text-center"></i>
                        <span>Tahun Ajaran</span>
                    </a>

                    <a href="{{ route('mata-pelajaran.index') }}"
                        class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('mata-pelajaran.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-book mr-3 w-5 text-center"></i>
                        <span>Mata Pelajaran</span>
                    </a>

                    <a href="{{ route('kelas.index') }}"
                        class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('kelas.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-school mr-3 w-5 text-center"></i>
                        <span>Kelas</span>
                    </a>
                @endhasanyrole
            </div>
        </div>

        <div class="mb-6">
            <h3 class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2">Manajemen Pengajaran</h3>
            <div class="px-2 space-y-1">

                @role('Admin')
                    <a href="{{ route('penugasan-mengajar.index') }}"
                        class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('penugasan-mengajar.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-chalkboard-teacher mr-3 w-5 text-center"></i>
                        <span>Penugasan Mengajar</span>
                    </a>

                    <!-- Menu items removed -->
                @endrole

                @hasanyrole('Admin|Guru|KepalaSekolah')
                    <a href="{{ route('presensi-mapel.index') }}"
                        class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('presensi-mapel.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-clipboard-list mr-3 w-5 text-center"></i>
                        <span>Presensi & Jurnal</span>
                    </a>

                    @role('Guru')
                        <a href="{{ route('nilai.index') }}"
                            class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('nilai.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                            <i class="fas fa-graduation-cap mr-3 w-5 text-center"></i>
                            <span>Nilai Siswa</span>
                        </a>

                        <a href="{{ route('nilai-akhir.index') }}"
                            class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('nilai-akhir.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                            <i class="fas fa-chart-line mr-3 w-5 text-center"></i>
                            <span>Nilai Akhir</span>
                        </a>
                    @endrole
                @endhasanyrole


                {{-- TODO: Tambahkan Menu Nilai di sini nanti untuk Guru --}}
                @role('Guru')
                    @php
                        $waliKelas = \App\Models\Kelas::where('wali_kelas_id', auth()->id())->first();
                    @endphp
                    @if ($waliKelas)
                        <a href="{{ route('kelas.show', $waliKelas->id) }}"
                            class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('kelas.show') && request()->route('kelas')?->id == $waliKelas->id ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                            <i class="fas fa-chalkboard mr-3 w-5 text-center"></i>
                            <span>Kelas Saya ({{ $waliKelas->nama }})</span>
                        </a>

                        <a href="{{ route('nilai-akhir.rekap-wali-kelas') }}"
                            class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('nilai-akhir.rekap-wali-kelas') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                            <i class="fas fa-file-alt mr-3 w-5 text-center"></i>
                            <span>Rapor Akhir Kelas</span>
                        </a>
                    @endif

                @endrole
            </div>
        </div>

        @hasanyrole('Admin|Guru|KepalaSekolah')
            <div class="mb-6">
                <h3 class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2">Proses Akademik</h3>
                <div class="px-2 space-y-1">
                    <a href="{{ route('promotion.form') }}"
                        class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('promotion.form') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-arrow-up mr-3 w-5 text-center"></i>
                        <span>Kenaikan Kelas</span>
                    </a>

                    <a href="{{ route('promotion.results') }}"
                        class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('promotion.results') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-chart-bar mr-3 w-5 text-center"></i>
                        <span>Hasil Kenaikan</span>
                    </a>

                    <a href="{{ route('graduation.form') }}"
                        class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('graduation.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-graduation-cap mr-3 w-5 text-center"></i>
                        <span>Kelulusan</span>
                    </a>
                </div>
            </div>
        @endhasanyrole

        <div class="border-t border-blue-700 pt-4 mt-2">
            <h3 class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2">Pengaturan</h3>
            <div class="px-2 space-y-1">
                <a href="{{ route('profile.edit') }}"
                    class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('profile.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                    <i class="fas fa-user mr-3 w-5 text-center"></i>
                    <span>Profile</span>
                </a>

                <a href="{{ route('settings.index') }}"
                    class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('settings.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                    <i class="fas fa-cog mr-3 w-5 text-center"></i>
                    <span>Settings</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="p-4 border-t border-blue-700 bg-blue-900 bg-opacity-50 z-10">
        <div class="text-center">
            <div class="flex items-center justify-center mb-1">
                <i class="fas fa-school mr-2 text-blue-300"></i>
                <span class="text-sm font-semibold text-blue-100">SMA Negeri 1 Jakarta</span>
            </div>
            <p class="text-xs text-blue-400">Sistem Akademik Sekolah</p>
        </div>
    </div>
</aside>

<aside id="mobile-sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-blue-800 text-white transform -translate-x-full md:hidden transition-transform duration-300 ease-in-out">

    <div class="h-16 flex items-center justify-between px-4 z-10">
        <div class="text-xl font-bold flex items-center">
            <i class="fas fa-chart-line mr-2"></i>
            <span>Dashboard</span>
        </div>
        <button id="close-mobile-sidebar" class="text-white hover:text-blue-300">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 custom-scrollbar">
        <div class="mb-6">
            <div class="px-2 space-y-1">
                <a href="{{ route('dashboard') }}" onclick="closeMobileSidebar()"
                    class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                    <i class="fas fa-home mr-3 w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2">Manajemen Akademik</h3>
            <div class="px-2 space-y-1">
                <a href="{{ route('siswa.index') }}" onclick="closeMobileSidebar()"
                    class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('siswa.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                    <i class="fas fa-users mr-3 w-5 text-center"></i>
                    <span>Siswa</span>
                </a>

                @hasanyrole('Admin|KepalaSekolah')
                    <a href="{{ route('tahun-ajaran.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('tahun-ajaran.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-calendar-alt mr-3 w-5 text-center"></i>
                        <span>Tahun Ajaran</span>
                    </a>

                    <a href="{{ route('mata-pelajaran.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('mata-pelajaran.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-book mr-3 w-5 text-center"></i>
                        <span>Mata Pelajaran</span>
                    </a>

                    <a href="{{ route('kelas.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('kelas.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-school mr-3 w-5 text-center"></i>
                        <span>Kelas</span>
                    </a>
                @endhasanyrole
            </div>
        </div>

        <div class="mb-6">
            <h3 class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2">Manajemen Pengajaran
            </h3>
            <div class="px-2 space-y-1">

                @role('Admin')
                    <a href="{{ route('penugasan-mengajar.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('penugasan-mengajar.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-chalkboard-teacher mr-3 w-5 text-center"></i>
                        <span>Penugasan Mengajar</span>
                    </a>

                    <!-- Mobile menu items removed -->
                @endrole

                @hasanyrole('Admin|Guru|KepalaSekolah')
                    <a href="{{ route('presensi-mapel.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('presensi-mapel.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-clipboard-list mr-3 w-5 text-center"></i>
                        <span>Presensi & Jurnal</span>
                    </a>

                    @role('Guru')
                        <a href="{{ route('nilai.index') }}" onclick="closeMobileSidebar()"
                            class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('nilai.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                            <i class="fas fa-graduation-cap mr-3 w-5 text-center"></i>
                            <span>Nilai Siswa</span>
                        </a>
                    @endrole
                @endhasanyrole
            </div>
        </div>

        @hasanyrole('Admin|KepalaSekolah')
            <div class="mb-6">
                <h3 class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2">Proses Akademik</h3>
                <div class="px-2 space-y-1">
                    <a href="{{ route('promotion.form') }}" onclick="closeMobileSidebar()"
                        class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('promotion.form') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-arrow-up mr-3 w-5 text-center"></i>
                        <span>Kenaikan Kelas</span>
                    </a>

                    <a href="{{ route('promotion.results') }}" onclick="closeMobileSidebar()"
                        class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('promotion.results') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-chart-bar mr-3 w-5 text-center"></i>
                        <span>Hasil Kenaikan</span>
                    </a>

                    <a href="{{ route('graduation.form') }}" onclick="closeMobileSidebar()"
                        class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('graduation.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                        <i class="fas fa-graduation-cap mr-3 w-5 text-center"></i>
                        <span>Kelulusan</span>
                    </a>
                </div>
            </div>
        @endhasanyrole

        @hasrole('Guru')
            @php
                $isWaliKelas = \App\Models\Kelas::where('wali_kelas_id', auth()->id())->exists();
            @endphp
            @if ($isWaliKelas)
                <div class="mb-6">
                    <h3 class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2">Wali Kelas</h3>
                    <div class="px-2 space-y-1">
                        <a href="{{ route('promotion.wali-kelas-form') }}" onclick="closeMobileSidebar()"
                            class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('promotion.wali-kelas-*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                            <i class="fas fa-user-graduate mr-3 w-5 text-center"></i>
                            <span>Kenaikan Kelas</span>
                        </a>
                    </div>
                </div>
            @endif
        @endhasrole

        <div class="border-t border-blue-700 pt-4 mt-2">
            <h3 class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2">Pengaturan</h3>
            <div class="px-2 space-y-1">
                <a href="{{ route('profile.edit') }}" onclick="closeMobileSidebar()"
                    class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('profile.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                    <i class="fas fa-user mr-3 w-5 text-center"></i>
                    <span>Profile</span>
                </a>

                <a href="{{ route('settings.index') }}" onclick="closeMobileSidebar()"
                    class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('settings.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                    <i class="fas fa-cog mr-3 w-5 text-center"></i>
                    <span>Settings</span>
                </a>
            </div>
        </div>
    </nav>
</aside>

<div class="md:hidden fixed top-4 left-4 z-40">
    <button id="open-mobile-sidebar"
        class="bg-blue-800 text-white p-2 rounded-lg shadow-lg hover:bg-blue-700 transition-colors">
        <i class="fas fa-bars text-xl"></i>
    </button>
</div>

<div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"
    onclick="closeMobileSidebar()"></div>

<script>
    function openMobileSidebar() {
        document.getElementById('mobile-sidebar').classList.remove('-translate-x-full');
        document.getElementById('mobile-overlay').classList.remove('hidden');
    }

    function closeMobileSidebar() {
        document.getElementById('mobile-sidebar').classList.add('-translate-x-full');
        document.getElementById('mobile-overlay').classList.add('hidden');
    }

    document.getElementById('open-mobile-sidebar').addEventListener('click', openMobileSidebar);
    document.getElementById('close-mobile-sidebar').addEventListener('click', closeMobileSidebar);
</script>
