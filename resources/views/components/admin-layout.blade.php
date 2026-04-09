<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SmartCEMES') }} - Dashboard</title>
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
                    <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <img src="{{ asset('icons/dashboard.png') }}" alt="Dashboard" class="w-5 h-5 object-contain">
                        <span>Dashboard</span>
                    </a>

                    <!-- Extension Programs -->
                    <a href="{{ route('programs.index') }}" class="sidebar-item {{ request()->routeIs('programs.*') ? 'active' : '' }}">
                        <img src="{{ asset('icons/project.png') }}" alt="Programs" class="w-5 h-5 object-contain">
                        <span>Programs</span>
                    </a>

                    <!-- Communities -->
                    <a href="{{ route('communities.index') }}" class="sidebar-item {{ request()->routeIs('communities.*') ? 'active' : '' }}">
                        <img src="{{ asset('icons/community.png') }}" alt="Communities" class="w-5 h-5 object-contain">
                        <span>Communities</span>
                    </a>

                    <!-- Beneficiaries -->
                    <a href="{{ route('beneficiaries.index') }}" class="sidebar-item {{ request()->routeIs('beneficiaries.*') ? 'active' : '' }}">
                        <img src="{{ asset('icons/beneficiaries.png') }}" alt="Beneficiaries" class="w-5 h-5 object-contain">
                        <span>Beneficiaries</span>
                    </a>

                    <!-- Activities -->
                    <a href="{{ route('activities.index') }}" class="sidebar-item {{ request()->routeIs('activities.*') ? 'active' : '' }}">
                        <img src="{{ asset('icons/activities.png') }}" alt="Activities" class="w-5 h-5 object-contain">
                        <span>Activities</span>
                    </a>

                    <!-- Assessment -->
                    <a href="{{ route('assessments.index') }}" class="sidebar-item {{ request()->routeIs('assessments.*') ? 'active' : '' }}">
                        <img src="{{ asset('icons/assessment.png') }}" alt="Assessments" class="w-5 h-5 object-contain">
                        <span>Assessments</span>
                    </a>

                    <!-- Calendar -->
                    <a href="{{ route('calendar.index') }}" class="sidebar-item {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                        <img src="{{ asset('icons/calendar.png') }}" alt="Calendar" class="w-5 h-5 object-contain">
                        <span>Calendar</span>
                    </a>

                    <!-- Reports -->
                    <a href="#" class="sidebar-item hover:bg-gray-100">
                        <img src="{{ asset('icons/reports.png') }}" alt="Reports" class="w-5 h-5 object-contain">
                        <span>Reports</span>
                    </a>

                    <!-- Divider -->
                    <div class="my-4 border-t border-gray-200"></div>

                    <!-- System Management -->
                    @if(auth()->user()->role === 'director')
                    <div class="px-4 py-3 mb-2">
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Administration</p>
                    </div>

                    <!-- Faculty Management -->
                    <a href="{{ route('faculties.index') }}" class="sidebar-item {{ request()->routeIs('faculties.*') ? 'active' : '' }}">
                        <img src="{{ asset('icons/faculty.png') }}" alt="Faculty" class="w-5 h-5 object-contain">
                        <span>Faculty</span>
                    </a>

                    <!-- Access Tokens -->
                    <a href="#" class="sidebar-item hover:bg-gray-100">
                        <img src="{{ asset('icons/lock.png') }}" alt="Access Tokens" class="w-5 h-5 object-contain">
                        <span>Access Tokens</span>
                    </a>

                    <!-- Audit Logs -->
                    <a href="#" class="sidebar-item hover:bg-gray-100">
                        <img src="{{ asset('icons/audit.png') }}" alt="Audit Logs" class="w-5 h-5 object-contain">
                        <span>Audit Logs</span>
                    </a>
                    @endif
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
                                <h1 class="text-2xl font-bold text-lnu-blue">Dashboard</h1>
                            @endisset
                        </div>

                        <!-- Right: User Profile & Notifications -->
                        <div class="flex items-center gap-4">
                            <!-- Notifications -->
                            <button class="relative p-2 text-gray-600 hover:text-lnu-blue transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-lnu-gold rounded-full"></span>
                            </button>

                            <!-- User Menu Dropdown -->
                            <a href="{{ route('profile.edit') }}" class="relative flex items-center gap-3 pl-4 border-l border-gray-200 cursor-pointer hover:bg-gray-50 px-3 py-2 rounded-lg transition">
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-600 capitalize">{{ Auth::user()->role }}</p>
                                </div>
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=003599&color=fff" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full">
                            </a>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-6 md:p-8">
                    <div class="animate-fade-in">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <!-- Livewire Scripts -->
        @livewireScripts
    </body>
</html>
