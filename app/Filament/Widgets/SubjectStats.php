<?php

namespace App\Filament\Widgets;

use App\Models\Subject;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SubjectStats extends BaseWidget
{
    protected function getStats(): array
    {
        $query = Subject::query();

        if (auth()->user()->hasRole('Teacher')) {
            $query->where('teacher_id', auth()->id());
        }

        return [
            Stat::make('إجمالي موادك', $query->count())
                ->description('المواد التي تديرها حالياً')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),

            Stat::make(
                'إجمالي المهارات',
                \App\Models\Skill::whereIn('subject_id', $query->pluck('id'))->count()
            )->description('المهارات الموزعة عبر المواد')
                ->color('warning'),
        ];
    }
}
