<x-layout.auth title="Forgot Password" subtitle="Enter your email to receive a password reset link">

    <x-slot name="footer">
        <p>Remember your password?
            <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300 font-medium">Sign in here</a>
        </p>
    </x-slot>

    <div class="text-center mb-6">
        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-key text-lg sm:text-2xl text-blue-600"></i>
        </div>
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-1">Reset Password</h2>
        <p class="text-sm text-gray-600">We'll send you a reset link</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2 text-sm"></i>
                <span class="text-sm">{{ session('status') }}</span>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                <i class="fas fa-envelope mr-1.5 text-gray-400 text-xs"></i>Email Address
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror"
                placeholder="Enter your email address">
            @error('email')
                <p class="mt-1 text-xs text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-2.5 px-4 rounded-lg font-medium hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transform hover:scale-[1.01] transition-all duration-200 shadow-md text-sm">
            <i class="fas fa-paper-plane mr-2 text-xs"></i>Send Reset Link
        </button>

        <!-- Back to Login -->
        <a href="{{ route('login') }}"
            class="w-full border border-gray-300 text-gray-700 py-2.5 px-4 rounded-lg font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-1 transition-colors duration-200 flex items-center justify-center text-sm">
            <i class="fas fa-arrow-left mr-2 text-xs"></i>Back to Login
        </a>
    </form>
</x-layout.auth>
