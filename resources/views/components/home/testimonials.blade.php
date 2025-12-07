<!-- Testimonials Section -->
<section class="py-24 px-4 bg-gradient-to-r from-purple-600 to-pink-600">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Loved by Developers Worldwide
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8" x-data="{
            testimonials: [
                { name: 'Sarah Johnson', role: 'Senior Developer', company: 'TechCorp', text: 'Laravel has transformed how we build web applications. The elegant syntax and powerful features make development a breeze.' },
                { name: 'Mike Chen', role: 'CTO', company: 'StartupXYZ', text: 'We chose Laravel for our platform and it was the best decision. The ecosystem is incredible and the community is amazing.' },
                { name: 'Emma Wilson', role: 'Full Stack Developer', company: 'Digital Agency', text: 'Laravel Eloquent ORM is a game-changer. Building complex database relationships has never been this intuitive.' }
            ]
        }">
            <template x-for="(testimonial, index) in testimonials" :key="index">
                <div class="bg-white bg-opacity-10 backdrop-blur-lg p-8 rounded-2xl text-white" x-show="true"
                    x-transition:enter="transition ease-out duration-600"
                    x-transition:enter-start="opacity-0 transform translate-y-8"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    :style="`animation-delay: ${index * 200}ms;`">
                    <div class="mb-6">
                        <svg class="w-10 h-10 text-white opacity-50" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                        </svg>
                    </div>
                    <p class="text-lg mb-6 leading-relaxed" x-text="testimonial.text"></p>
                    <div class="flex items-center">
                        <div
                            class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                            <span class="text-lg font-bold" x-text="testimonial.name.charAt(0)"></span>
                        </div>
                        <div>
                            <div class="font-semibold" x-text="testimonial.name"></div>
                            <div class="text-white text-opacity-70"
                                x-text="`${testimonial.role} at ${testimonial.company}`"></div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</section>
