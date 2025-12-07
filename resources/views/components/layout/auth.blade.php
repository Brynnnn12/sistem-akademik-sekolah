<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Authentication' }} - Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 min-h-screen">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="40" height="40" viewBox="0 0 40 40"
        xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="%23ffffff" fill-opacity="0.03"%3E%3Cpath
        d="M20 20c0-5.5-4.5-10-10-10s-10 4.5-10 10 4.5 10 10 10 10-4.5 10-10zm10 0c0-5.5-4.5-10-10-10s-10 4.5-10 10 4.5 10 10 10 10-4.5 10-10z"
        /%3E%3C/g%3E%3C/svg%3E')]"></div>

    <div class="min-h-screen flex items-center justify-center p-3 sm:p-4 relative auth-container">
        <!-- Main Content Card -->
        <div class="w-full max-w-sm sm:max-w-md">
            <div
                class="auth-card auth-form bg-white/95 backdrop-blur-sm rounded-xl sm:rounded-2xl shadow-xl sm:shadow-2xl p-4 sm:p-6 border border-white/20 max-h-[90vh] overflow-y-auto">
                {{ $slot }}
            </div>

            <!-- Footer Links -->
            <div class="text-center mt-4 text-blue-200 text-xs sm:text-sm px-4">
                {{ $footer ?? '' }}
            </div>
        </div>

        <!-- Decorative Elements - Hidden on small screens -->
        <div class="hidden sm:block absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full blur-xl animate-pulse">
        </div>
        <div class="hidden sm:block absolute top-40 right-20 w-32 h-32 bg-indigo-400/20 rounded-full blur-2xl animate-pulse"
            style="animation-delay: 1s;"></div>
        <div class="hidden sm:block absolute bottom-20 left-1/4 w-24 h-24 bg-blue-400/20 rounded-full blur-xl animate-pulse"
            style="animation-delay: 2s;"></div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Custom animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-form {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Responsive improvements */
        @media (max-width: 640px) {
            .auth-container {
                padding: 1rem;
            }

            .auth-card {
                padding: 1.5rem;
                margin: 0.5rem;
            }
        }

        /* Focus improvements */
        input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Custom scrollbar for mobile */
        .auth-card::-webkit-scrollbar {
            width: 2px;
        }

        .auth-card::-webkit-scrollbar-track {
            background: transparent;
        }

        .auth-card::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.3);
            border-radius: 1px;
        }
    </style>
</body>

</html>
