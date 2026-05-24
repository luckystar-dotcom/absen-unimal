<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Absensi') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Tailwind CSS Fallback CDN for absolute safety -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        },
                    }
                }
            }
        </script>

        <style>
            * { font-family: 'Inter', sans-serif; box-sizing: border-box; }

            body {
                margin: 0;
                background-color: #0f172a;
                color: #fff;
                min-height: 100vh;
            }

            .topbar {
                background: #1e293b;
                border-bottom: 1px solid #334155;
                padding: 0 24px;
                height: 64px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                position: sticky;
                top: 0;
                z-index: 50;
            }

            .topbar-brand {
                display: flex;
                align-items: center;
                gap: 8px;
                font-weight: 800;
                font-size: 1.15rem;
            }

            .topbar-user {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .topbar-avatar {
                width: 36px;
                height: 36px;
                border-radius: 8px;
                background: linear-gradient(135deg, #fbbf24, #f59e0b);
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                color: #0f172a;
                font-size: 0.85rem;
            }

            .topbar-info {
                text-align: right;
            }

            .topbar-name {
                font-weight: 600;
                font-size: 0.9rem;
                color: #fff;
            }

            .topbar-role {
                font-size: 0.75rem;
                color: #94a3b8;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .logout-btn {
                background: rgba(239, 68, 68, 0.1);
                border: 1px solid rgba(239, 68, 68, 0.25);
                color: #f87171;
                padding: 8px 16px;
                border-radius: 8px;
                cursor: pointer;
                font-size: 0.8rem;
                font-weight: 600;
                transition: all 0.2s;
            }

            .logout-btn:hover {
                background: rgba(239, 68, 68, 0.2);
                border-color: rgba(239, 68, 68, 0.4);
                transform: translateY(-1px);
            }
        </style>

        @stack('styles')
    </head>
    <body class="bg-slate-900 text-white">
        <!-- Top Navigation Bar -->
        <nav class="topbar shadow-md">
            <div class="topbar-brand">
                <svg class="h-6 w-6 text-amber-400 filter drop-shadow-[0_0_4px_rgba(251,191,36,0.5)]" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                </svg>

                @if(Auth::user()->isMahasiswa())
                <div style="display: flex; align-items: center; gap: 4px; margin-left: 12px;">
                    <a href="{{ route('absensi') }}" style="padding: 6px 14px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; text-decoration: none; transition: all 0.2s; {{ request()->routeIs('absensi') ? 'background: rgba(251,191,36,0.15); color: #fbbf24; border: 1px solid rgba(251,191,36,0.25);' : 'color: #94a3b8; border: 1px solid transparent;' }}">
                        Presensi
                    </a>
                    <a href="{{ route('riwayat') }}" style="padding: 6px 14px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; text-decoration: none; transition: all 0.2s; {{ request()->routeIs('riwayat') ? 'background: rgba(251,191,36,0.15); color: #fbbf24; border: 1px solid rgba(251,191,36,0.25);' : 'color: #94a3b8; border: 1px solid transparent;' }}">
                        Riwayat
                    </a>
                </div>
                @endif
            </div>

            <div class="topbar-user">
                <div class="topbar-info hidden sm:block">
                    <div class="topbar-name">{{ Auth::user()->name }}</div>
                    <div class="topbar-role">{{ Auth::user()->role === 'mahasiswa' ? 'Mahasiswa' : 'Admin' }} · {{ Auth::user()->nip_nim }}</div>
                </div>
                <div class="topbar-avatar shadow-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="logout-btn">Keluar</button>
                </form>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </body>
</html>
