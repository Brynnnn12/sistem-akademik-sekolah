<!-- Stats Section -->
<section class="py-24 px-4" x-data="{ inView: false, stats: { downloads: 0, packages: 0, contributors: 0, stars: 0 } }" x-intersect.once="inView = true; animateStats()">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700">
                <div class="text-4xl md:text-5xl font-bold text-laravel mb-2"
                    x-text="stats.downloads.toLocaleString() + 'M+'">200M+</div>
                <div class="text-gray-600 dark:text-gray-400 font-semibold">Downloads</div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700">
                <div class="text-4xl md:text-5xl font-bold text-purple-600 mb-2"
                    x-text="stats.packages.toLocaleString() + 'K+'">30K+</div>
                <div class="text-gray-600 dark:text-gray-400 font-semibold">Packages</div>
            </div>
            <div
                class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700">
                <div class="text-4xl md:text-5xl font-bold text-blue-600 mb-2"
                    x-text="stats.contributors.toLocaleString() + '+'">2K+</div>
                <div class="text-gray-600 dark:text-gray-400 font-semibold">Contributors</div>
            </div>
            <div
                class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700">
                <div class="text-4xl md:text-5xl font-bold text-green-600 mb-2"
                    x-text="stats.stars.toLocaleString() + 'K+'">75K+</div>
                <div class="text-gray-600 dark:text-gray-400 font-semibold">GitHub Stars</div>
            </div>
        </div>
    </div>
</section>
