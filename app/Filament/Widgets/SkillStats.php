<?php

namespace App\Filament\Widgets;

use App\Models\Skill;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SkillStats extends BaseWidget
{
    protected function getStats(): array
    {
        $query = Skill::query();

        if (auth()->user()->hasRole('Teacher')) {
            $query->where('teacher_id', auth()->id());
        }

        return [
            Stat::make('إجمالي المهارات', (clone $query)->count())
                ->description('المهارات التعليمية النشطة')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('info'),

            Stat::make('المادة الأكثر تركيزاً', function () use ($query) {
                $result = (clone $query)
                    ->join('subjects', 'skills.subject_id', '=', 'subjects.id')
                    ->selectRaw('subjects.name as subject_name, count(*) as count')
                    ->groupBy('subject_name')
                    ->orderBy('count', 'desc')
                    ->first();
                return $result ? $result->subject_name : 'لا يوجد';
            })->description('أكثر مادة تمت تغطيتها مهارياً')->color('warning'),

            Stat::make('الدروس المغطاة', \App\Models\Video::distinct('skill')->count())
                ->description('فيديوهات مرتبطة بمهارات فعلياً')
                ->color('success'),
        ];
    }
}
