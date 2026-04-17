<?php

namespace App\Filament\Widgets;

use App\Models\Lesson;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LessonStats extends BaseWidget
{
    protected function getStats(): array
    {
        $query = Lesson::query();

        // فلترة الإحصائيات أيضاً للمعلم
        if (auth()->user()->hasRole('Teacher')) {
            $query->where('teacher_id', auth()->id());
        }

        return [
            Stat::make('إجمالي الدروس', $query->count())
                ->description('الدروس المتاحة في حسابك')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('success'),

            Stat::make('الدروس التي تحتوي فيديوهات', $query->has('video')->count())
                ->color('warning'),
        ];
    }
}
