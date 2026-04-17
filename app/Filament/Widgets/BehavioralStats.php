<?php

namespace App\Filament\Widgets;

use App\Models\BehavioralAnalysis;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BehavioralStats extends BaseWidget
{
    protected function getStats(): array
    {
        $query = BehavioralAnalysis::query();

        if (auth()->user()->hasRole('Teacher')) {
            $query->whereHas('video', fn($q) => $q->where('teacher_id', auth()->id()));
        }

        return [
            Stat::make('متوسط التركيز في موادك', round((clone $query)->avg('focus_level'), 1) . '%')
                ->description('صحة العملية التعليمية')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),

            Stat::make('تنبيهات الحيرة', (clone $query)->where('confusion_level', '>', 50)->count())
                ->description('طلاب يواجهون صعوبة حالياً')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make('النمط الأكثر انتشاراً', function () use ($query) {
                return (clone $query)->whereNotNull('detected_learning_style')
                    ->groupBy('detected_learning_style')
                    ->orderByRaw('COUNT(*) DESC')
                    ->limit(1)
                    ->pluck('detected_learning_style')
                    ->first() ?? 'قيد التحليل';
            })->description('أسلوب التعلم المفضل لطلابك')->color('info'),
        ];
    }
}
