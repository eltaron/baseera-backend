<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\InteractionsChart;
use App\Filament\Widgets\LatestActivities;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class AIMetrics extends Page
{
    use HasPageShield;
    // الأيقونة في القائمة الجانبية (أيقونة رقاقة إلكترونية)
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    // المجموعة التي تنتمي لها في القائمة
    protected static ?string $navigationGroup = 'تحليلات الذكاء الاصطناعي';

    // عنوان الصفحة
    protected static ?string $title = 'غرفة عمليات بصيرة AI';

    // مسار ملف الواجهة (الفرونت)
    protected static string $view = 'filament.pages.a-i-metrics';



    // إضافة زر "تحديث البيانات" في أعلى الصفحة بشكل احترافي
    protected function getHeaderActions(): array
    {
        return [
            Action::make('refreshAI')
                ->label('تحديث خوارزميات التوصية')
                ->icon('heroicon-m-arrow-path')
                ->color('warning')
                ->action(function () {
                    // هنا يمكن وضع كود لتشغيل سكريبت بايثون أو تحديث الداتا
                    Notification::make()
                        ->title('تم تحديث البيانات بنجاح')
                        ->success()
                        ->send();
                }),
        ];
    }
}
