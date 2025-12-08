<x-layout.dashboard title="Pengaturan">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Pengaturan</h3>

        <form action="{{ route('settings.update') }}" method="POST" class="space-y-6" x-data="{ notifications: true, theme: 'light' }">
            @csrf
            @method('PUT')

            <!-- Profile Settings -->
            <div class="border-b border-gray-200 pb-6">
                <h4 class="text-md font-medium text-gray-900 mb-4">Profil Pengguna</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" id="name" name="name"
                            value="{{ old('name', Auth::user()->name) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email"
                            value="{{ old('email', Auth::user()->email) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="border-b border-gray-200 pb-6">
                <h4 class="text-md font-medium text-gray-900 mb-4">Notifikasi</h4>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notifikasi Email</label>
                        <div class="flex items-center">
                            <button :class="{ 'bg-blue-600': notifications, 'bg-gray-200': !notifications }"
                                @click="notifications = !notifications" type="button"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                role="switch" aria-checked="false">
                                <span :class="{ 'translate-x-5': notifications, 'translate-x-0': !notifications }"
                                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                            <span class="ml-3 text-sm text-gray-500"
                                x-text="notifications ? 'Aktif' : 'Nonaktif'"></span>
                            <input type="hidden" name="email_notifications" :value="notifications ? '1' : '0'">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notifikasi Push</label>
                        <div class="flex items-center">
                            <input type="checkbox" id="push_notifications" name="push_notifications" value="1"
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="push_notifications" class="ml-2 text-sm text-gray-700">
                                Terima notifikasi push di browser
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appearance Settings -->
            <div class="border-b border-gray-200 pb-6">
                <h4 class="text-md font-medium text-gray-900 mb-4">Tampilan</h4>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tema Tampilan</label>
                        <div class="flex space-x-4">
                            <button @click="theme = 'light'" type="button"
                                :class="{ 'ring-2 ring-blue-500': theme === 'light' }"
                                class="px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm">
                                <i class="fas fa-sun mr-2"></i> Terang
                            </button>
                            <button @click="theme = 'dark'" type="button"
                                :class="{ 'ring-2 ring-blue-500': theme === 'dark' }"
                                class="px-4 py-2 bg-gray-800 text-white border border-gray-700 rounded-lg shadow-sm hover:bg-gray-700 transition-colors">
                                <i class="fas fa-moon mr-2"></i> Gelap
                            </button>
                            <input type="hidden" name="theme" :value="theme">
                        </div>
                    </div>

                    <div>
                        <label for="language" class="block text-sm font-medium text-gray-700 mb-2">Bahasa</label>

                        {{-- Tom Select untuk Bahasa --}}
                        @push('styles')
                            <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css"
                                rel="stylesheet">
                            <style>
                                .ts-control {
                                    border-radius: 0.375rem;
                                    padding: 0.5rem 0.75rem;
                                    border-color: #d1d5db;
                                }

                                .ts-control:focus {
                                    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
                                    border-color: #3b82f6;
                                }

                                .ts-dropdown {
                                    z-index: 60;
                                }
                            </style>
                        @endpush

                        @push('scripts')
                            <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const selectEl = document.getElementById('language');
                                    if (selectEl && !selectEl.tomselect) {
                                        new TomSelect(selectEl, {
                                            create: false,
                                            sortField: {
                                                field: "text",
                                                direction: "asc"
                                            },
                                            placeholder: "Pilih bahasa...",
                                            plugins: ['clear_button'],
                                            dropdownParent: 'body'
                                        });
                                    }
                                });
                            </script>
                        @endpush

                        <select id="language" name="language"
                            class="w-full md:w-1/3 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            autocomplete="off">
                            <option value="id" selected>Indonesia</option>
                            <option value="en">English</option>
                            <option value="ja">日本語</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <button type="button" onclick="location.reload()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                    Reset
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</x-layout.dashboard>
