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
            
            // Konfigurasi Notifikasi
            ->databaseNotifications()
            ->databaseNotificationsPolling('5s')

            // Branding & UI
            ->brandName('Aplikasi POS Kasir')
            ->font('Poppins')
            ->colors([
                'primary' => Color::Blue,
                'danger' => Color::Red,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'gray' => Color::Slate,
            ])
    
             // Selesai konfigurasi colors, kurung ditutup di sini

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
            // Efek Custom Tema "Dark Sidebar" Premium
          // Efek Custom Tema "Dark Sidebar" Premium
         // Efek Custom Tema "Dark Sidebar" Premium
         // Efek Custom Tema "Dark Sidebar" Premium
            ->renderHook(
                PanelsRenderHook::HEAD_END, 
                fn (): string => Blade::render('
                    <style>
                        /* 1. Background Sidebar & Header Dark Navy */
                        .fi-sidebar, .fi-sidebar-header {
                            background-color: #0f172a !important; 
                        }

                        /* 2. Judul Kategori (Master Data, dll) */
                        .fi-sidebar-group-label {
                            color: #94a3b8 !important; 
                        }

                        /* 3. Reset Background Menu Biasa */
                        .fi-sidebar-item a, 
                        .fi-sidebar-item button {
                            background-color: transparent !important;
                        }
                        
                        /* Warna Teks Menu Biasa */
                        .fi-sidebar-item a *, 
                        .fi-sidebar-item button * {
                            color: #cbd5e1 !important;
                        }

                        /* 4. FIX UNTUK FILAMENT V3: Menu AKTIF */
                        .fi-sidebar-item.fi-active > a, 
                        .fi-sidebar-item.fi-active > button {
                            background-color: #2563eb !important; /* Kotak biru */
                            border-radius: 0.5rem !important;
                            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.4) !important;
                        }
                        /* Paksa isi menu aktif jadi putih */
                        .fi-sidebar-item.fi-active > a *, 
                        .fi-sidebar-item.fi-active > button * {
                            color: #ffffff !important; 
                            font-weight: 600 !important;
                        }

                        /* 5. Menu saat di-HOVER */
                        .fi-sidebar-item a:hover, 
                        .fi-sidebar-item button:hover {
                            background-color: #1e293b !important; /* Kotak dark navy terang */
                            border-radius: 0.5rem !important;
                        }
                        .fi-sidebar-item a:hover *, 
                        .fi-sidebar-item button:hover * {
                            color: #ffffff !important; 
                        }

                        /* 6. Logo Teks di Pojok Kiri Atas */
                        .fi-logo {
                            color: #4248f9 !important;
                        }
                    </style>
                ')
            )
            // Sidebar bisa collapse
        ->sidebarFullyCollapsibleOnDesktop()

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