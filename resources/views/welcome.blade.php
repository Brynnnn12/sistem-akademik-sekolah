<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: false }" :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - The PHP Framework for Web Artisans</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Instrument Sans', 'ui-sans-serif', 'system-ui'],
                    },
                    colors: {
                        'laravel': '#FF2D20',
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .dark .hero-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #581c87 100%);
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-slide-up {
            animation: slideInUp 0.8s ease-out forwards;
        }

        .feature-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-card:hover {
            transform: translateY(-8px) scale(1.02);
        }

        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .dark .glass-effect {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body
    class="bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-purple-900 text-gray-900 dark:text-white transition-colors duration-300">

    <!-- Navigation -->
    <x-home.navigation />

    <!-- Hero Section -->
    <x-home.hero />

    <!-- Features Section -->
    <x-home.features />

    <!-- Getting Started Section -->
    <x-home.getting-started />

    <!-- Stats Section -->
    <x-home.stats />

    <!-- Testimonials Section -->
    <x-home.testimonials />

    <!-- Footer -->
    <x-home.footer />

    <!-- Scripts -->
    <x-home.scripts />
</body>

</html>
