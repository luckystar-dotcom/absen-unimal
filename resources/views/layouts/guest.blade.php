<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-900">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Absensi') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
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
            body {
                font-family: 'Inter', sans-serif;
                background-color: #0f172a;
            }

            /* Legacy guest styles mapped to premium Slate-Dark components */
            .luckystar-input {
                background-color: #0f172a !important;
                border: 1px solid #334155 !important;
                border-radius: 8px !important;
                color: #fff !important;
                padding: 10px 14px !important;
                width: 100% !important;
                font-size: 0.9rem !important;
                transition: all 0.2s ease !important;
                outline: none !important;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
            }

            .luckystar-input:focus {
                border-color: #fbbf24 !important;
                box-shadow: 0 0 0 1px #fbbf24, 0 0 12px rgba(251, 191, 36, 0.15) !important;
                background-color: rgba(15, 23, 42, 0.6) !important;
            }

            .luckystar-input::placeholder {
                color: #64748b !important;
            }

            .luckystar-label {
                color: #cbd5e1 !important;
                font-size: 0.85rem !important;
                font-weight: 500 !important;
                display: block !important;
                margin-bottom: 6px !important;
            }

            .luckystar-btn {
                background-color: #f59e0b !important;
                color: #0f172a !important;
                font-weight: 700 !important;
                padding: 10px 20px !important;
                border-radius: 8px !important;
                border: none !important;
                cursor: pointer !important;
                transition: all 0.2s ease !important;
                width: 100% !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                font-size: 0.9rem !important;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
            }

            .luckystar-btn:hover {
                background-color: #fbbf24 !important;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(251, 191, 36, 0.2) !important;
            }

            .luckystar-btn:active {
                transform: translateY(0);
            }

            .luckystar-link {
                color: #fbbf24 !important;
                text-decoration: none !important;
                font-size: 0.85rem !important;
                transition: color 0.2s !important;
                font-weight: 500 !important;
            }

            .luckystar-link:hover {
                color: #f59e0b !important;
                text-decoration: underline !important;
            }

            .luckystar-error {
                color: #f87171 !important;
                font-size: 0.8rem !important;
                margin-top: 6px !important;
                display: flex !important;
                align-items: center !important;
                gap: 4px !important;
            }
        </style>
    </head>
    <body class="h-full bg-slate-900 text-white">
        <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <!-- Glowing Gold Star Logo -->
                <div class="flex justify-center">
                    <svg class="h-12 w-12 text-amber-400 filter drop-shadow-[0_0_10px_rgba(251,191,36,0.6)]" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                    </svg>
                </div>
                
                <p class="mt-6 text-center text-sm text-slate-400 font-medium tracking-wide uppercase">Smart Attendance System</p>
            </div>

            <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
                <div class="bg-slate-800 px-6 py-12 shadow-xl border border-slate-700/50 sm:rounded-xl sm:px-12">
                    {{ $slot }}
                </div>
                
                <!-- Footer -->
                <p class="mt-8 text-center text-xs text-slate-500 font-medium">
                    &copy; {{ date('Y') }} &mdash; Universitas Malikussaleh
                </p>
            </div>
        </div>
    </body>
</html>
