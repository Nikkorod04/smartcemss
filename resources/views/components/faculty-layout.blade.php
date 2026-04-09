<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SmartCEMES') }} - Faculty Portal</title>
        <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Livewire Styles -->
        @livewireStyles

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="flex h-screen bg-gray-100">
            <!-- Sidebar -->
            <div class="hidden md:flex w-64 bg-white shadow-lg flex-col border-r border-gray-200 animate-slide-in-left">
                <!-- Logo Section -->
                <div class="px-6 py-6 border-b border-gray-200 flex items-center gap-3">
                    <img src="{{ asset('ceso_logobig.png') }}" alt="SmartCEMES" class="h-12 object-contain flex-shrink-0">
                    <h1 class="text-lg font-bold text-lnu-blue">SmartCEMES</h1>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 px-2 py-6 space-y-1 overflow-y-auto">
                    <!-- Dashboard -->
                    <a href="{{ route('faculty.dashboard') }}" class="sidebar-item {{ request()->routeIs('faculty.dashboard') ? 'active' : '' }}">
                        <img src="{{ asset('icons/faculty.png') }}" alt="Profile" class="w-5 h-5 object-contain">
                        <span>Profile</span>
                    </a>

                    <!-- Programs -->
                    <a href="{{ route('faculty.programs') }}" class="sidebar-item {{ request()->routeIs('faculty.programs') ? 'active' : '' }}">
                        <img src="{{ asset('icons/project.png') }}" alt="Programs" class="w-5 h-5 object-contain">
                        <span>Programs</span>
                    </a>

                    <!-- Calendar -->
                    <a href="{{ route('faculty.calendar') }}" class="sidebar-item {{ request()->routeIs('faculty.calendar') ? 'active' : '' }}">
                        <img src="{{ asset('icons/calendar.png') }}" alt="Calendar" class="w-5 h-5 object-contain">
                        <span>Calendar</span>
                    </a>
                </nav>

                <!-- Logout at Bottom -->
                <div class="border-t border-gray-200 p-2">
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg transition font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Navigation / Header -->
                <header class="bg-white shadow-sm border-b border-gray-200 animate-slide-in-right">
                    <div class="flex items-center justify-between px-6 py-4 md:px-8">
                        <!-- Left: Page Title -->
                        <div>
                            @isset($header)
                                <h1 class="text-2xl font-bold text-lnu-blue">{{ $header }}</h1>
                            @else
                                <h1 class="text-2xl font-bold text-lnu-blue">Faculty Portal</h1>
                            @endisset
                        </div>

                        <!-- Right: User Profile -->
                        <div class="flex items-center gap-4">
                            <a href="{{ route('faculty.dashboard') }}" class="relative flex items-center gap-3 pl-4 border-l border-gray-200 cursor-pointer hover:bg-gray-50 px-3 py-2 rounded-lg transition">
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-600">Faculty</p>
                                </div>
                                <div class="w-8 h-8 bg-lnu-blue rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            </a>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-auto">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
