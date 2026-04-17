<?php

namespace App\Filament\Widgets;

use App\Models\ParentReport;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReportStats extends BaseWidget
{
    protected function getStats(): array
    {
        $query = ParentReport::query();

        if (auth()->user()->hasRole('Teacher')) {
            $query->where('teacher_id', auth()->id());
        }

        return [
            Stat::make('تقارير مرسلة اليوم', (clone $query)->whereDate('created_at', now())->count())
                ->description('تواصلك مع الأهل اليوم')
                ->descriptionIcon('heroicon-m-paper-airplane')
                ->color('success'),

            Stat::make('تنبيهات التدخل (أحمر)', (clone $query)->where('status_color', 'red')->count())
                ->description('حالات حرجة تم إبلاغ الأهل بها')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make('متوسط حالة الطلاب', function () use ($query) {
                $total = (clone $query)->count();
                $excellent = (clone $query)->where('status_color', 'green')->count();
                return $total > 0 ? round(($excellent / $total) * 100) . '% أخضر' : '0%';
            })->description('نسبة الرضا والأداء المرتفع')->color('info'),
        ];
    }
}
