<?php

namespace App\Filament\Widgets;

use App\Models\VideoInteraction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InteractionStats extends BaseWidget
{
    protected function getStats(): array
    {
        $query = VideoInteraction::query();

        if (auth()->user()->hasRole('Teacher')) {
            $query->whereHas('video', fn($q) => $q->where('teacher_id', auth()->id()));
        }

        return [
            Stat::make('إجمالي التفاعلات', (clone $query)->count())
                ->description('إجمالي المشاهدات التي تمت')
                ->descriptionIcon('heroicon-m-eye')
                ->color('info'),

            Stat::make('متوسط الإعادات (Replay)', round((clone $query)->avg('replay_count'), 1))
                ->description('قد تدل على محتوى دسم أو صعب')
                ->color('warning'),

            Stat::make('متوسط مرات التوقف', round((clone $query)->avg('pause_frequency'), 1))
                ->description('مؤشر على تقطع انتباه الطالب')
                ->color('danger'),
        ];
    }
}
