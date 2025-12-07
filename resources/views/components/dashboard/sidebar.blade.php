<!-- Sidebar Desktop -->
<div class="w-64 bg-blue-800 text-white p-4 hidden md:block">
    <div class="text-2xl font-bold mb-8 mt-4 flex items-center justify-center">
        <i class="fas fa-chart-line mr-2"></i>
        <span>DashBoard</span>
    </div>

    <nav>
        <a href="{{ route('dashboard') }}"
            class="flex items-center p-3 rounded-lg mb-2 transition-all hover:bg-blue-700 {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }}">
            <i class="fas fa-home mr-3"></i>
            <span>Overview</span>
        </a>

        <a href="{{ route('profile.edit') }}"
            class="flex items-center p-3 rounded-lg mb-2 transition-all hover:bg-blue-700 {{ request()->routeIs('profile.*') ? 'bg-blue-700' : '' }}">
            <i class="fas fa-user mr-3"></i>
            <span>Profile</span>
        </a>

        <a href="{{ route('tahun-ajaran.index') }}"
            class="flex items-center p-3 rounded-lg mb-2 transition-all hover:bg-blue-700 {{ request()->routeIs('tahun-ajaran.*') ? 'bg-blue-700' : '' }}">
            <i class="fas fa-calendar-alt mr-3"></i>
            <span>Tahun Ajaran</span>
        </a>

        <a href="{{ route('settings.index') }}"
            class="flex items-center p-3 rounded-lg mb-2 transition-all hover:bg-blue-700 {{ request()->routeIs('settings.*') ? 'bg-blue-700' : '' }}">
            <i class="fas fa-cog mr-3"></i>
            <span>Settings</span>
        </a>
    </nav>

    <div class="mt-8 pt-8 border-t border-blue-700">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                @php $profile = Auth::user()->profile @endphp
                @if ($profile && $profile->photo)
                    <img src="{{ $profile->photo }}" alt="Profile Photo" class="w-10 h-10 rounded-full object-cover">
                @else
                    <span class="font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                @endif
            </div>
            <div class="ml-3">
                <p class="font-medium">{{ Auth::user()->name }}</p>
                <p class="text-xs text-blue-300">{{ Auth::user()->email }}</p>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="flex items-center p-3 rounded-lg transition-all hover:bg-blue-700 w-full text-left">
                <i class="fas fa-sign-out-alt mr-3"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>
