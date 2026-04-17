<?php

namespace App\Filament\Widgets;

use App\Models\Unit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UnitStats extends BaseWidget
{
    protected function getStats(): array
    {
        // استعلام أساسي نظيف
        $baseQuery = \App\Models\Unit::query();

        // فلترة المعلم إذا لزم الأمر
        if (auth()->user()->hasRole('Teacher')) {
            $baseQuery->where('teacher_id', auth()->id());
        }

        return [
            // 1. إحصائية العدد الإجمالي (نستخدم نسخة مستنسخة)
            Stat::make('إجمالي وحداتك الدراسية', (clone $baseQuery)->count())
                ->description('عدد الوحدات التي تديرها')
                ->descriptionIcon('heroicon-m-rectangle-group')
                ->color('info'),

            // 2. المادة الأكثر محتوى (نستخدم نسخة مستنسخة)
            Stat::make('أكثر مادة بها وحدات', function () use ($baseQuery) {
                $result = (clone $baseQuery)
                    ->join('subjects', 'units.subject_id', '=', 'subjects.id')
                    ->selectRaw('subjects.name as subject_name, count(*) as count')
                    ->groupBy('subject_name')
                    ->orderBy('count', 'desc')
                    ->first();

                return $result ? $result->subject_name : 'لا يوجد';
            })->color('warning'),

            // 3. إجمالي الدروس (نستخدم نسخة مستنسخة لجلب الـ IDs بشكل سليم)
            Stat::make('إجمالي الدروس التابعة لك', function () use ($baseQuery) {
                $unitIds = (clone $baseQuery)->pluck('id')->toArray();
                return \App\Models\Lesson::whereIn('unit_id', $unitIds)->count();
            })->color('success'),
        ];
    }
}
