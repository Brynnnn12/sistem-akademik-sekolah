<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100 font-sans" x-data="{ isSidebarOpen: false, activeTab: '{{ $title ?? 'Dashboard' }}' }">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <x-dashboard.sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <x-dashboard.header />

            <!-- Mobile Sidebar -->
            <x-dashboard.mobile-sidebar />



            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <x-ui.flash-messages />

                {{ $slot }}
            </main>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</body>

</html>
