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
use App\Filament\Widgets\StatsOverview; // 1. استيراد الوجت
use App\Filament\Widgets\InteractionsChart; // 2. استيراد الرسم البياني
use App\Filament\Widgets\LatestActivities; // 3. استيراد أحدث النشاطات
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
            // ضبط ألوان الهوية بصيرة
            ->colors([
                'primary' => Color::rgb('rgb(255, 123, 0)'), // البرتقالي #FF7B00
                'gray' => Color::Slate,
            ])
            ->font('Tajawal')

            // الترويسة والبراندينج
            ->brandName('منصة بصيرة الذكية')
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('favicon/favicon.ico'))

            // تحسين تجربة المستخدم
            // ->sidebarCollapsible() // جعل السايدبار قابل للطي ليعطي مساحة للرسوم البيانية
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->profile()
            ->maxContentWidth(MaxWidth::Full) // جعل المحتوى يأخذ العرض الكامل

            ->globalSearch(true) // التأكد من تفعيل السيرش العام

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])

            // تسجيل الـ Widgets المناسبة فقط لتظهر في الداشبورد الرئيسي
            ->widgets([
                StatsOverview::class,      // المربعات العلوية بالأرقام
                InteractionsChart::class,  // الرسم البياني لتفاعل الطلاب
                LatestActivities::class,   // جدول آخر العمليات التي تمت
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
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            // حقن CSS مخصص لجعل السايدبار داكن ومطابق لهوية الطالب
            ->renderHook(
                'panels::styles.after',
                fn(): string => '<style>
                /* 1. السايدبار المظلم الكامل */
                .fi-sidebar { background-color: #030a13 !important; border-inline-start: 1px solid rgba(255,255,255,0.05) !important; }
                .fi-sidebar-nav-label, .fi-sidebar-group-label { color: #94a3b8 !important; font-weight: 700 !important; }

                /* 2. شكل العناصر النشطة باللون البرتقالي */
                .fi-sidebar-item-button:hover { background-color: rgba(255, 255, 255, 0.05) !important; }
                .fi-sidebar-item-active { background-color: rgba(255, 123, 0, 0.1) !important; border-inline-start: 4px solid #FF7B00; border-radius: 0 8px 8px 0 !important; }
                .fi-sidebar-item-active span, .fi-sidebar-item-active svg { color: #FF7B00 !important; }

                /* 3. تصميم القوائم المنسدلة (Submenus) بشكل شجري كالتصميم الأصلي */
                .fi-sidebar-group { border-inline-start: 1px solid rgba(255,255,255,0.1);  }
                .fi-sidebar-item-button { transition: all 0.2s ease-in-out; }

                /* 4. الهيدر المودرن (السيرش والإشعارات) */
                .fi-topbar { background-color: rgba(255, 255, 255, 0.8) !important; backdrop-filter: blur(10px); }
                // .fi-global-search-field input { background-color: #F1F5F9 !important; border-radius: 12px !important; border: none !important; }

                /* أيقونة الإشعارات */
                .fi-topbar-database-notifications-btn svg { color: #64748B !important; }
                .fi-topbar-database-notifications-btn:hover svg { color: #FF7B00 !important; }

                /* 5. زر البروفايل دائري وأنيق */
                .fi-topbar-user-menu img { border: 2px solid #FF7B00; padding: 2px; }

                </style>',
            );
    }
}
