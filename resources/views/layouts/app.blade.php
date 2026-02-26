<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EasyColoc')</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen bg-gray-50">
        <!-- Sidebar -->
        @auth
            @include('components.sidebar')
        @endauth

        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Navbar -->
            @auth
                @include('components.navbar')
            @endauth

            <!-- Main Content -->
            <main class="flex-1 overflow-auto">
                @if(!Auth::check())
                    <div class="min-h-screen">
                        @yield('content')
                    </div>
                @else
                    <div class="p-4 sm:p-6 lg:p-8">
                        @include('components.flash-message')
                        @yield('content')
                    </div>
                @endif
            </main>
        </div>
    </div>
</body>
</html>
