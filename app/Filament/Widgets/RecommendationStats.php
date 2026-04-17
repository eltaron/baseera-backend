<?php

namespace App\Filament\Widgets;

use App\Models\Recommendation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RecommendationStats extends BaseWidget
{
    protected function getStats(): array
    {
        $query = Recommendation::query();

        if (auth()->user()->hasRole('Teacher')) {
            $query->whereHas('video', fn($q) => $q->where('teacher_id', auth()->id()));
        }

        return [
            Stat::make('إجمالي توصيات بصيرة', (clone $query)->count())
                ->description('إجمالي التوجيهات التي قدمها النظام')
                ->descriptionIcon('heroicon-m-light-bulb')
                ->color('info'),

            Stat::make('نسبة استجابة الطلاب', function () use ($query) {
                $total = (clone $query)->count();
                $viewed = (clone $query)->where('is_viewed', true)->count();
                return $total > 0 ? round(($viewed / $total) * 100, 1) . '%' : '0%';
            })->description('من قبلوا التوصيات وشاهدوها')->color('success'),

            Stat::make('توصيات قيد الانتظار', (clone $query)->where('is_viewed', false)->count())
                ->description('يحتاج الطلاب للتشجيع عليها')
                ->color('warning'),
        ];
    }
}
