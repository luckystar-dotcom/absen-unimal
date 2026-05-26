<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                'panels::body.end',
                fn (): string => \Illuminate\Support\Facades\Blade::render("
                    <script src='https://cdnjs.cloudflare.com/ajax/libs/pusher/8.3.0/pusher.min.js'></script>
                    <script src='https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js'></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            if (typeof Pusher !== 'undefined') {
                                window.Pusher = Pusher;
                                const EchoClass = window.LaravelEcho || window.Echo;
                                if (EchoClass) {
                                    window.Echo = new EchoClass({
                                        broadcaster: 'reverb',
                                        key: '{{ env('REVERB_APP_KEY') }}',
                                        wsHost: window.location.hostname,
                                        wsPort: {{ env('REVERB_PORT', 8080) }},
                                        wssPort: {{ env('REVERB_PORT', 8080) }},
                                        forceTLS: false,
                                        enabledTransports: ['ws', 'wss'],
                                    });
                                    console.log('Laravel Echo initialized successfully via CDN on Filament Dashboard!');
                                } else {
                                    console.error('Laravel Echo class not found!');
                                }
                            } else {
                                console.error('Pusher library not loaded!');
                            }
                        });
                    </script>
                "),
            );
    }
}
