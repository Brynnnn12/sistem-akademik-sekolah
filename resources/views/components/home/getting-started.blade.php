<!-- Getting Started Section -->
<section class="py-24 px-4 bg-gray-50 dark:bg-gray-900" x-data="{ inView: false }" x-intersect.once="inView = true">
    <div class="max-w-4xl mx-auto text-center">
        <div x-show="inView" x-transition:enter="transition ease-out duration-800"
            x-transition:enter-start="opacity-0 transform translate-y-8"
            x-transition:enter-end="opacity-100 transform translate-y-0">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-8">
                Ready to Get Started?
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 mb-12 leading-relaxed">
                Join thousands of developers who choose Laravel for their next project.
                Experience the joy of elegant, expressive syntax.
            </p>

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-2xl border border-gray-200 dark:border-gray-700">
                <div class="bg-gray-900 dark:bg-black rounded-xl p-6 text-left overflow-x-auto">
                    <div class="flex items-center mb-4">
                        <div class="flex space-x-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        </div>
                        <span class="ml-4 text-gray-400 text-sm">Terminal</span>
                    </div>
                    <code class="text-green-400 font-mono text-lg">
                        $ composer create-project laravel/laravel my-app<br>
                        $ cd my-app<br>
                        $ php artisan serve
                    </code>
                </div>
            </div>

            <div class="mt-12">
                <a href="https://cloud.laravel.com"
                    class="inline-flex items-center px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-laravel to-pink-600 rounded-xl hover:from-red-600 hover:to-pink-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                    Deploy Now
                </a>
            </div>
        </div>
    </div>
</section>
