@props(['darkMode' => false])

<!-- Navigation -->
<nav class="fixed top-0 left-0 right-0 z-50 glass-effect" x-data="{ isOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <h1 class="text-2xl font-bold text-laravel">Laravel</h1>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-8">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="px-4 py-2 rounded-lg bg-laravel text-white hover:bg-red-600 transition-colors duration-200 font-medium">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-gray-700 dark:text-gray-300 hover:text-laravel transition-colors duration-200 font-medium">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-4 py-2 rounded-lg bg-laravel text-white hover:bg-red-600 transition-colors duration-200 font-medium">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif

                    <!-- Dark Mode Toggle -->
                    <button @click="$dispatch('toggle-dark-mode')"
                        class="p-2 rounded-lg bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200">
                        <svg x-show="!$wire.darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <svg x-show="$wire.darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" x-cloak>
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button @click="isOpen = !isOpen" class="p-2">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': isOpen, 'inline-flex': !isOpen }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !isOpen, 'inline-flex': isOpen }" class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-show="isOpen" x-transition class="md:hidden glass-effect border-t">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium bg-laravel text-white">Dashboard</a>
                @else
                    <a href="{{ route('login') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-200 dark:hover:bg-gray-700">Log
                        in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="block px-3 py-2 rounded-md text-base font-medium bg-laravel text-white">Register</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</nav>
