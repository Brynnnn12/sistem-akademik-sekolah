<x-layout.dashboard title="Ubah Password">
    <div class="space-y-6">
        <!-- Update Password -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 mr-4">
                    <i class="fas fa-lock text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Update Password</h3>
                    <p class="text-sm text-gray-600">Ensure your account is using a long, random password to stay
                        secure.</p>
                </div>
            </div>
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
</x-layout.dashboard>
