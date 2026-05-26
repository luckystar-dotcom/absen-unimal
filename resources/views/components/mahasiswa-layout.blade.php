<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'Dashboard' }} — LuckyStar</title>
    <meta name="description" content="LuckyStar Student Dashboard — Sistem Absensi Mahasiswa Berbasis Lokasi">

    <!-- Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        surface: {
                            900: '#0b1120',
                            800: '#0f172a',
                            700: '#1e293b',
                            600: '#334155',
                            500: '#475569',
                            400: '#64748b',
                            300: '#94a3b8',
                            200: '#cbd5e1',
                            100: '#e2e8f0',
                        }
                    }
                }
            }
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        body { margin: 0; background-color: #0f172a; color: #fff; }

        /* Custom Scrollbar */
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* Sidebar transition */
        .sidebar-link {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-link:hover {
            background: rgba(59, 130, 246, 0.08);
            color: #93c5fd;
        }
        .sidebar-link.active {
            background: rgba(59, 130, 246, 0.12);
            color: #60a5fa;
            border-right: 3px solid #3b82f6;
        }

        /* Mobile overlay */
        .sidebar-overlay {
            transition: opacity 0.3s ease;
        }

        /* Subtle animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-16px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-slide-in {
            animation: slideInLeft 0.3s ease forwards;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-surface-800 text-white antialiased">
    <div class="flex h-screen overflow-hidden">

        <!-- ============================================ -->
        <!-- SIDEBAR KIRI (Desktop: fixed, Mobile: drawer) -->
        <!-- ============================================ -->

        <!-- Mobile overlay -->
        <div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-[260px] bg-surface-900 border-r border-surface-600/50 flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">

            <!-- Logo -->
            <div class="flex items-center gap-3 px-6 py-5 border-b border-surface-600/50">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center shadow-lg shadow-brand-500/20">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-extrabold tracking-tight text-white">LuckyStar</h1>
                    <p class="text-[10px] font-medium text-surface-400 uppercase tracking-widest">Student Portal</p>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 py-4 px-3 space-y-1 overflow-y-auto custom-scroll">
                <p class="px-3 mb-2 text-[11px] font-semibold text-surface-400 uppercase tracking-wider">Menu Utama</p>

                <!-- Dasbor (KRS) -->
                <a href="{{ route('mahasiswa.dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : 'text-surface-300' }}" id="nav-dashboard">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dasbor KRS</span>
                </a>

                <!-- Katalog MK -->
                <a href="{{ route('mahasiswa.katalog') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('mahasiswa.katalog') ? 'active' : 'text-surface-300' }}" id="nav-katalog">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span>Katalog MK</span>
                </a>

                <!-- Direktori Dosen -->
                <a href="{{ route('mahasiswa.dosen') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('mahasiswa.dosen') ? 'active' : 'text-surface-300' }}" id="nav-dosen">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Direktori Dosen</span>
                </a>

                <div class="my-3 border-t border-surface-600/40"></div>
                <p class="px-3 mb-2 text-[11px] font-semibold text-surface-400 uppercase tracking-wider">Presensi</p>

                <!-- Riwayat Absen -->
                <a href="{{ route('riwayat') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('riwayat') ? 'active' : 'text-surface-300' }}" id="nav-riwayat">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Riwayat Absen</span>
                </a>

                <!-- Presensi GPS -->
                <a href="{{ route('absensi') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('absensi') ? 'active' : 'text-surface-300' }}" id="nav-absensi">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Presensi GPS</span>
                </a>
            </nav>

            <!-- Logout Button (Bottom) -->
            <div class="p-3 border-t border-surface-600/50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-all duration-200" id="btn-logout">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- ============================================ -->
        <!-- MAIN AREA (Header + Content) -->
        <!-- ============================================ -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            <!-- HEADER ATAS -->
            <header class="h-16 bg-surface-700/50 backdrop-blur-sm border-b border-surface-600/50 flex items-center justify-between px-4 lg:px-6 flex-shrink-0 z-30">
                <div class="flex items-center gap-3">
                    <!-- Mobile menu button -->
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg text-surface-300 hover:bg-surface-600 hover:text-white transition-colors" id="btn-menu-toggle">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <h2 class="text-lg font-bold text-white tracking-tight">{{ $pageTitle ?? 'Dashboard' }}</h2>
                </div>

                <!-- Profile section -->
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-white leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] text-surface-400 font-medium">{{ Auth::user()->nip_nim }} · Mahasiswa</p>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-sm font-bold text-white shadow-lg shadow-brand-500/20">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                </div>
            </header>

            <!-- SCROLLABLE CONTENT AREA -->
            <main class="flex-1 overflow-y-auto custom-scroll bg-surface-800">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Sidebar Toggle Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>

    @stack('scripts')
</body>
</html>
