<?php

namespace App\Filament\Widgets;

use App\Models\QuizResult;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QuizStats extends BaseWidget
{
    protected function getStats(): array
    {
        $query = QuizResult::query();

        // فلترة البيانات لو كان الداخل معلم
        if (auth()->user()->hasRole('Teacher')) {
            $query->whereHas('video', fn($q) => $q->where('teacher_id', auth()->id()));
        }

        return [
            Stat::make('متوسط الدقة العام', round((clone $query)->avg('accuracy_percentage'), 1) . '%')
                ->description('أداء الطلاب في اختباراتك')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('success'),

            Stat::make('أسرع زمن استجابة', round((clone $query)->min('average_response_time'), 2) . ' ث')
                ->description('يدل على قوة الاستيعاب')
                ->color('info'),

            Stat::make('طلاب تحت الملاحظة', (clone $query)->where('accuracy_percentage', '<', 50)->count())
                ->description('حققوا أقل من 50% في الاختبار')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
