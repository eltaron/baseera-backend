<?php

namespace App\Filament\Widgets;

use App\Models\Video;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VideoStats extends BaseWidget
{
    protected function getStats(): array
    {
        $query = Video::query();

        // لو معلم يظهر له إحصائياته هو بس
        if (auth()->user()->hasRole('Teacher')) {
            $query->where('teacher_id', auth()->id());
        }

        return [
            Stat::make('إجمالي الفيديوهات', $query->count())
                ->description('المحتوى التعليمي المرفوع')
                ->descriptionIcon('heroicon-m-video-camera')
                ->color('info'),

            Stat::make('فيديوهات مستوى متقدم', $query->where('difficulty', 'advanced')->count())
                ->description('تحتاج تركيزاً عالياً')
                ->color('danger'),

            Stat::make('إجمالي مدة المحتوى', round($query->sum('duration_seconds') / 3600, 1) . ' ساعة')
                ->description('إجمالي ساعات الشرح بالمنصة')
                ->color('success'),
        ];
    }
}
