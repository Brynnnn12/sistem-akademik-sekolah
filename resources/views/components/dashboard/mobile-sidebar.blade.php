            <div x-show="isSidebarOpen" @click.away="isSidebarOpen = false" class="fixed inset-0 z-50 md:hidden" x-cloak>
                <div class="fixed inset-0 bg-gray-800 bg-opacity-75" @click="isSidebarOpen = false"></div>
                <aside class="fixed inset-y-0 left-0 w-64 bg-blue-800 text-white flex flex-col h-full flex-shrink-0">

                    <div class="h-16 flex items-center justify-between px-4 border-b border-blue-700 shadow-md z-10">
                        <div class="text-2xl font-bold flex items-center">
                            <i class="fas fa-chart-line mr-2"></i>
                            <span>DashBoard</span>
                        </div>
                        <button @click="isSidebarOpen = false" class="text-white hover:text-blue-300">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <nav class="flex-1 overflow-y-auto py-4 custom-scrollbar">
                        <!-- Menu Utama -->
                        <div class="mb-4">
                            <h3 class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2">Menu
                                Utama</h3>

                            <div class="px-2 space-y-1">
                                <a href="{{ route('dashboard') }}"
                                    class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}"
                                    @click="isSidebarOpen = false">
                                    <i class="fas fa-home mr-3 w-5 text-center"></i>
                                    <span>Overview</span>
                                </a>

                                <a href="{{ route('siswa.index') }}"
                                    class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('siswa.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}"
                                    @click="isSidebarOpen = false">
                                    <i class="fas fa-users mr-3 w-5 text-center"></i>
                                    <span>Siswa</span>
                                </a>

                                <a href="{{ route('tahun-ajaran.index') }}"
                                    class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('tahun-ajaran.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}"
                                    @click="isSidebarOpen = false">
                                    <i class="fas fa-calendar-alt mr-3 w-5 text-center"></i>
                                    <span>Tahun Ajaran</span>
                                </a>

                                <a href="{{ route('mata-pelajaran.index') }}"
                                    class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('mata-pelajaran.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}"
                                    @click="isSidebarOpen = false">
                                    <i class="fas fa-book mr-3 w-5 text-center"></i>
                                    <span>Mata Pelajaran</span>
                                </a>
                            </div>
                        </div>

                        <!-- Pengaturan -->
                        <div class="border-t border-blue-700 pt-4 mt-2">
                            <h3 class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2">
                                Pengaturan</h3>
                            <div class="px-2 space-y-1">
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('profile.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}"
                                    @click="isSidebarOpen = false">
                                    <i class="fas fa-user mr-3 w-5 text-center"></i>
                                    <span>Profile</span>
                                </a>

                                <a href="{{ route('settings.index') }}"
                                    class="flex items-center p-3 rounded-lg transition-colors {{ request()->routeIs('settings.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}"
                                    @click="isSidebarOpen = false">
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
                        <div class="mt-4">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="flex items-center justify-center p-3 rounded-lg transition-colors text-blue-100 hover:bg-blue-700 hover:text-white w-full text-left"
                                    @click="isSidebarOpen = false">
                                    <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </aside>
            </div>
