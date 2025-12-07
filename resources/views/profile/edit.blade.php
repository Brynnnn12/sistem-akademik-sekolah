<x-layout.dashboard title="Edit Profile">
    <div class="space-y-6">
        <!-- Profile Information -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mr-4">
                    <i class="fas fa-user text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Profile Information</h3>
                    <p class="text-sm text-gray-600">Update your account's profile information and email address.</p>
                </div>
            </div>
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

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
                <a href="{{ route('password.edit') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Ubah Password
                </a>
            </div>
        </div>

        <!-- Delete Account -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-red-600 mr-4">
                    <i class="fas fa-trash text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Delete Account</h3>
                    <p class="text-sm text-gray-600">Once your account is deleted, all of its resources and data will be
                        permanently deleted.</p>
                </div>
            </div>
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-layout.dashboard>
