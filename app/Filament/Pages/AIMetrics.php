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

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $navigationGroup = 'تحليلات الذكاء الاصطناعي';
    protected static ?string $title = 'غرفة عمليات بصيرة AI';
    protected static string $view = 'filament.pages.a-i-metrics';

    // ربط الودجات الأساسية بالصفحة
    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            InteractionsChart::class,
            LatestActivities::class,
        ];
    }

    // عمليات التحكم في النظام
    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateReport')
                ->label('توليد تقرير أداء ذكي')
                ->icon('heroicon-m-document-chart-bar')
                ->color('info')
                ->requiresConfirmation()
                ->action(fn() => Notification::make()
                    ->title('جاري استخراج تقرير الأداء الـ AI...')
                    ->info()
                    ->send()),

            Action::make('retrainModel')
                ->label('إعادة معايرة خوارزمية السلوك')
                ->icon('heroicon-m-command-line')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('تنبيه أمان!')
                ->modalDescription('هذا الإجراء سيقوم بإعادة تحليل كافة سلوكيات الطلاب الماضية لتحديث دقة الـ Learning Profiles. هل تريد الاستمرار؟')
                ->action(function () {
                    Notification::make()
                        ->title('بدأت عملية إعادة التدريب (Simulation Mode)')
                        ->warning()
                        ->send();
                }),
        ];
    }
}
