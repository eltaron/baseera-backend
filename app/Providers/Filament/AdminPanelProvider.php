<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
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
            // 1. تحديد ألوان الهوية (البرتقالي كلون أساسي)
            ->colors([
                'primary' => Color::rgb('rgb(255, 123, 0)'), // لون بصيرة البرتقالي #FF7B00
                'gray' => Color::Slate,
            ])
            // 2. تغيير الخط ليكون متناسقاً مع التصميم (Tajawal)
            ->font('Tajawal')

            // 3. إضافة اللوجو والبراندينج
            ->brandName('منصة بصيرة')
            ->brandLogo(asset('images/logo.png')) // تأكد من وجود اللوجو في مجلد public/images
            ->brandLogoHeight('3rem')
            ->favicon(asset('favicon/favicon.ico'))

            // 5. تفعيل البحث العالمي (Global Search)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])

            // 6. تفعيل الملف الشخصي للمدير
            ->profile()

            // 7. تحديد عرض الصفحة (مثالي للتقارير)
            ->maxContentWidth(MaxWidth::ScreenExtraLarge)

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
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
            ->plugins([
                FilamentShieldPlugin::make(),
            ])

            ->authMiddleware([
                Authenticate::class,
            ])
            // 8. حقن CSS مخصص لجعل السايدبار داكن تماماً مثل تصميم الطالب
            ->renderHook(
                'panels::styles.after',
                fn(): string => '<style>
                    .fi-sidebar { background-color: #030a13 !important; border-inline-start: 1px solid rgba(255,255,255,0.05) !important; }
                    .fi-sidebar-nav-label, .fi-sidebar-group-label { color: #94a3b8 !important; }
                    .fi-sidebar-item-button:hover { background-color: rgba(255, 255, 255, 0.05) !important; }
                    .fi-sidebar-item-active { background-color: rgba(255, 123, 0, 0.1) !important; border-right: 3px solid #FF7B00; }
                </style>',
            );
    }
}
