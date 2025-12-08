<!-- Sidebar Desktop -->
<aside class="w-64 bg-blue-800 text-white hidden md:flex flex-col h-full flex-shrink-0 transition-all duration-300">

    <div class="h-16 flex items-center justify-center z-10">
        <div class="text-2xl font-bold flex items-center">
            <i class="fas fa-chart-line mr-2"></i>
            <span>DashBoard</span>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 custom-scrollbar">
        <!-- Menu Utama -->
        <div class="mb-4">
            <h3 class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2">Menu Utama</h3>

            <div class="px-2 space-y-1">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                    <i class="fas fa-home mr-3 w-5 text-center"></i>
                    <span>Overview</span>
                </a>

                <a href="{{ route('siswa.index') }}"
                    class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('siswa.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                    <i class="fas fa-users mr-3 w-5 text-center"></i>
                    <span>Siswa</span>
                </a>

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

                <a href="{{ route('penugasan-mengajar.index') }}"
                    class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('penugasan-mengajar.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                    <i class="fas fa-chalkboard-teacher mr-3 w-5 text-center"></i>
                    <span>Penugasan Mengajar</span>
                </a>
            </div>
        </div>

        <!-- Pengaturan -->
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

    <!-- Footer dengan nama sekolah -->
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
