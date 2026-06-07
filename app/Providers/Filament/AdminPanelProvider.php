<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Filament\View\PanelsRenderHook; // Pastikan ini diimport
use Illuminate\Support\Facades\Blade; // Pastikan ini diimport

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;

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
            ->brandName('Aplikasi POS Kasir') 
            ->font('Poppins') 

            // Pengaturan Tema Warna
            ->colors([
                'primary' => Color::Blue,
                'danger' => Color::Red,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'gray' => Color::Slate,
            ]) // Selesai konfigurasi colors, kurung ditutup di sini

            // Efek Glassmorphism halaman Login
       // Efek Animasi Premium & Glassmorphism halaman Login
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                fn (): string => Blade::render('
                    <style>
                        /* 1. Background Kasir Modern + Overlay Gelap Elegan */
                        .fi-simple-layout {
                            background-image: 
                                /* Gradasi warna gelap agar gambar kasir di belakangnya terasa premium */
                                linear-gradient(rgba(15, 23, 42, 0.6), rgba(15, 23, 42, 0.9)), 
                                /* Link gambar kasir (POS/Card Terminal) dari Unsplash */
                                url("https://images.unsplash.com/photo-1556740738-b6a63e27c4df?q=80&w=1920") !important;
                            background-size: cover !important;
                            background-position: center !important;
                            background-repeat: no-repeat !important;
                        }

                        /* 2. Kotak Login (Glassmorphism Super Mulus) */
                        .fi-simple-main-ctn > div {
                            background-color: rgba(255, 255, 255, 0.85) !important;
                            backdrop-filter: blur(16px) !important;
                            -webkit-backdrop-filter: blur(16px) !important;
                            border: 1px solid rgba(255, 255, 255, 0.4) !important;
                            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6) !important; /* Shadow lebih tebal ala Apple */
                            border-radius: 1.5rem !important;

                            /* Panggilan 2 Animasi Sekaligus: Muncul (Cinematic) & Melayang (Floating) */
                            animation: 
                                cinematicReveal 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards,
                                floatingBox 5s ease-in-out 1.2s infinite alternate !important;
                        }

                        /* 3. Animasi Muncul Pertama Kali (Cinematic) */
                        @keyframes cinematicReveal {
                            0% {
                                opacity: 0;
                                transform: translateY(50px) scale(0.95);
                            }
                            100% {
                                opacity: 1;
                                transform: translateY(0) scale(1);
                            }
                        }

                        /* 4. Animasi Kotak Melayang Tiada Henti (Floating Effect) */
                        @keyframes floatingBox {
                            0% {
                                transform: translateY(0px);
                            }
                            100% {
                                transform: translateY(-12px);
                            }
                        }
                    </style>
                ')
            )

            // Sidebar bisa collapse
            ->sidebarCollapsibleOnDesktop()

            // Database Resource
            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\\Filament\\Resources'
            )

            // Pages
            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\\Filament\\Pages'
            )

            ->pages([
                Dashboard::class,
            ])

            // Widgets Dashboard
            ->discoverWidgets(
                in: app_path('Filament/Widgets'),
                for: 'App\\Filament\\Widgets'
            )

            ->widgets([
                AccountWidget::class,
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\PenjualanChart::class,
            ])

            // Middleware
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

            // Auth Middleware
            ->authMiddleware([
                Authenticate::class,
            ]); // Diakhiri dengan titik koma yang bersih di sini
    }
}