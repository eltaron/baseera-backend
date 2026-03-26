<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\BehavioralAnalysis;
use App\Models\VideoInteraction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('إجمالي الطلاب', User::where('role', 'student')->count())
                ->description('الطلاب المسجلين في المنصة')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]), // رسم بياني وهمي للتوضيح

            Stat::make('متوسط مستوى التركيز', round(BehavioralAnalysis::avg('focus_level') ?? 0, 1) . '%')
                ->description('بناءً على تحليل الكاميرا والـ AI')
                ->color('warning'),

            Stat::make('فيديوهات تم مشاهدتها', VideoInteraction::count())
                ->description('إجمالي التفاعلات التعليمية')
                ->descriptionIcon('heroicon-m-play-circle')
                ->color('info'),
        ];
    }
}
