<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
</head>

<body class="bg-gray-100 font-sans h-screen overflow-hidden">

    <div class="flex h-full w-full">
        <!-- Sidebar -->
        <x-dashboard.sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-full overflow-hidden relative">
            <!-- Header -->
            <x-dashboard.header />

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-100 scroll-smooth">
                <x-ui.flash-messages />

                {{ $slot }}
            </main>
        </div>
    </div>

    <style>
        /* Custom Scrollbar untuk Sidebar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
    @stack('scripts')
</body>

</html>
