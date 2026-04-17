<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
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
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;

class ParentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('parent')
            ->path('parent') // رابط الوصول للوالدين هو /parent
            ->login()        // تفعيل صفحة تسجيل الدخول للوالدين
            ->profile()      // تمكين الأب/الأم من تعديل بياناتهم وصورهم

            // 1. الألوان: استخدمنا "النيلي/Indigo" كأزرق ملكي مريح للعين
            ->colors([
                'primary' => Color::Indigo,
                'gray' => Color::Slate,
            ])

            // 2. الهوية البصرية للوالدين
            ->font('Tajawal') // نفس خط الموقع العام
            ->brandName('بوابة ولي الأمر | بصيرة')
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('favicon/favicon.ico'))

            // 3. تجربة المستخدم (UX)
            ->maxContentWidth(MaxWidth::ScreenExtraLarge)
            ->databaseNotifications() // تمكين الأب من استقبال إشعارات المنصة (مثلاً: ابنه أتم درساً)

            // 4. المسارات البرمجية للموارد (يجب أن يكون لديك مجلد App/Filament/Parent)
            ->discoverResources(in: app_path('Filament/Parent/Resources'), for: 'App\\Filament\\Parent\\Resources')
            ->discoverPages(in: app_path('Filament/Parent/Pages'), for: 'App\\Filament\\Parent\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Parent/Widgets'), for: 'App\\Filament\\Parent\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class, // يظهر معلومات ولي الأمر في الصفحة الرئيسية
                // تم إزالة FilamentInfo لتجنب تشتيت ولي الأمر
                \App\Filament\Parent\Widgets\ChildSummaryWidget::class, // الإحصائيات (مربعات)
                \App\Filament\Parent\Widgets\ChildFocusChart::class,   // الرسم البياني (منحنى)
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

        ;
    }
}
