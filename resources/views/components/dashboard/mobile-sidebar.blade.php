            <div x-show="isSidebarOpen" @click.away="isSidebarOpen = false" class="fixed inset-0 z-50 md:hidden" x-cloak>
                <div class="fixed inset-0 bg-gray-800 bg-opacity-75" @click="isSidebarOpen = false"></div>
                <div class="fixed inset-y-0 left-0 w-64 bg-blue-800 text-white p-4">
                    <div class="text-2xl font-bold mb-8 mt-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-chart-line mr-2"></i>
                            <span>DashBoard</span>
                        </div>
                        <button @click="isSidebarOpen = false">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <nav>
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center p-3 rounded-lg mb-2 transition-all hover:bg-blue-700 {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }}"
                            @click="isSidebarOpen = false">
                            <i class="fas fa-home mr-3"></i>
                            <span>Overview</span>
                        </a>

                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center p-3 rounded-lg mb-2 transition-all hover:bg-blue-700 {{ request()->routeIs('profile.*') ? 'bg-blue-700' : '' }}"
                            @click="isSidebarOpen = false">
                            <i class="fas fa-user mr-3"></i>
                            <span>Profile</span>
                        </a>

                        <a href="{{ route('tahun-ajaran.index') }}"
                            class="flex items-center p-3 rounded-lg mb-2 transition-all hover:bg-blue-700 {{ request()->routeIs('tahun-ajaran.*') ? 'bg-blue-700' : '' }}"
                            @click="isSidebarOpen = false">
                            <i class="fas fa-calendar-alt mr-3"></i>
                            <span>Tahun Ajaran</span>
                        </a>

                        <a href="{{ route('settings.index') }}"
                            class="flex items-center p-3 rounded-lg mb-2 transition-all hover:bg-blue-700 {{ request()->routeIs('settings.*') ? 'bg-blue-700' : '' }}"
                            @click="isSidebarOpen = false">
                            <i class="fas fa-cog mr-3"></i>
                            <span>Settings</span>
                        </a>
                    </nav>
                </div>
            </div>
