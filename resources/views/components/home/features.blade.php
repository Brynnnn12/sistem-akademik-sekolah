<!-- Features Section -->
<section class="py-24 px-4" x-data="{ inView: false }" x-intersect.once="inView = true">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                Why Choose Laravel?
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                Laravel combines simplicity with powerful features to help you build amazing applications.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="feature-card bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700"
                x-show="inView" x-transition:enter="transition ease-out duration-600"
                x-transition:enter-start="opacity-0 transform translate-y-8"
                x-transition:enter-end="opacity-100 transform translate-y-0">
                <div
                    class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Rapid Development</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    Built-in tools and conventions help you develop applications faster with less code and more
                    functionality.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="feature-card bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700"
                x-show="inView" x-transition:enter="transition ease-out duration-600 delay-150"
                x-transition:enter-start="opacity-0 transform translate-y-8"
                x-transition:enter-end="opacity-100 transform translate-y-0">
                <div
                    class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-600 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Security First</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    Laravel provides security features out of the box, including authentication, authorization, and
                    protection against common vulnerabilities.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="feature-card bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700"
                x-show="inView" x-transition:enter="transition ease-out duration-600 delay-300"
                x-transition:enter-start="opacity-0 transform translate-y-8"
                x-transition:enter-end="opacity-100 transform translate-y-0">
                <div
                    class="w-16 h-16 bg-gradient-to-r from-pink-500 to-rose-600 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Developer Experience</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    Elegant syntax, comprehensive documentation, and an amazing community make Laravel a joy to work
                    with.
                </p>
            </div>
        </div>
    </div>
</section>
