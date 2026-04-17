<?php

namespace App\Filament\Widgets;

use App\Models\StudentProgress;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProgressStats extends BaseWidget
{
    protected function getStats(): array
    {
        $query = StudentProgress::query();

        // فلترة لو كان الحساب معلم
        if (auth()->user()->hasRole('Teacher')) {
            $query->whereHas('subject', fn($q) => $q->where('teacher_id', auth()->id()));
        }

        return [
            Stat::make('متوسط الإنجاز العام', round((clone $query)->avg('completion_percentage'), 1) . '%')
                ->description('نسبة إتمام المنهج لجميع الطلاب')
                ->descriptionIcon('heroicon-m-chart-pie')
                ->color('info'),

            Stat::make('المعدل العام للدرجات', round((clone $query)->avg('overall_score'), 1))
                ->description('متوسط جودة الفهم')
                ->color('success'),

            Stat::make('طلاب لم يبدأوا بعد', (clone $query)->where('completion_percentage', '<=', 5)->count())
                ->description('يحتاجون لتحفيز فوري')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger'),
        ];
    }
}
