<x-layout.auth title="Login" subtitle="Welcome back! Please sign in to your account">

    <x-slot name="footer">
        <p>Don't have an account?
            <a href="{{ route('register') }}" class="text-blue-400 hover:text-blue-300 font-medium">Sign up here</a>
        </p>
    </x-slot>

    <!-- Header -->
    <div class="text-center mb-6">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-1">Sign In</h2>
        <p class="text-sm text-gray-600">Access your dashboard</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-2 text-sm"></i>
                <p class="text-green-700 text-sm">{{ session('status') }}</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4" x-data="{ showPassword: false }">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                <i class="fas fa-envelope mr-1.5 text-gray-400 text-xs"></i>Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                autocomplete="username"
                class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror"
                placeholder="Enter your email">
            @error('email')
                <p class="mt-1 text-xs text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                <i class="fas fa-lock mr-1.5 text-gray-400 text-xs"></i>Password
            </label>
            <div class="relative">
                <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                    autocomplete="current-password"
                    class="w-full px-3 py-2.5 pr-10 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 @enderror"
                    placeholder="Enter your password">
                <button type="button" @click="showPassword = !showPassword"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <i class="fas text-xs" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-xs text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between text-sm">
            <div class="flex items-center">
                <input id="remember_me" type="checkbox" name="remember"
                    class="h-3.5 w-3.5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="remember_me" class="ml-2 text-gray-700">
                    Remember me
                </label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-blue-600 hover:text-blue-500 font-medium">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-2.5 px-4 rounded-lg font-medium hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transform hover:scale-[1.01] transition-all duration-200 shadow-md text-sm">
            <i class="fas fa-sign-in-alt mr-2 text-xs"></i>Sign In
        </button>

        <!-- Divider -->
        <div class="relative my-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-xs">
                <span class="px-2 bg-white text-gray-500">New to Dashboard?</span>
            </div>
        </div>

        <!-- Register Link -->
        <a href="{{ route('register') }}"
            class="w-full border border-gray-300 text-gray-700 py-2.5 px-4 rounded-lg font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-1 transition-colors duration-200 flex items-center justify-center text-sm">
            <i class="fas fa-user-plus mr-2 text-xs"></i>Create New Account
        </a>
    </form>
</x-layout.auth>
