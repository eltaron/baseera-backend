<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\BehavioralAnalysis;
use App\Models\VideoInteraction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $isTeacher = auth()->user()->hasRole('Teacher'); // التحقق من الرتبة
        $teacherId = auth()->id();

        // استعلام التفاعلات (مرتبط بالفيديو الذي يخص المعلم)
        $interactionQuery = VideoInteraction::query();
        $analysisQuery = BehavioralAnalysis::query();
        $studentQuery = User::where('role', 'student');

        if ($isTeacher) {
            // المعلم يرى فقط تفاعلات فيديوهاته
            $interactionQuery->whereHas('video', fn($q) => $q->where('teacher_id', $teacherId));
            $analysisQuery->whereHas('video', fn($q) => $q->where('teacher_id', $teacherId));
            // المعلم يرى عدد الطلاب الذين تفاعلوا مع محتواه
            $studentQuery->whereHas('interactions.video', fn($q) => $q->where('teacher_id', $teacherId));
        }

        return [
            Stat::make($isTeacher ? 'طلابي النشطين' : 'إجمالي الطلاب', $studentQuery->count())
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->chart([3, 10, 5, 12, 8, 15, 20]),

            Stat::make('متوسط تركيز طلابي', round($analysisQuery->avg('focus_level') ?? 0, 1) . '%')
                ->description($isTeacher ? 'أداء طلابك في فيديوهاتك' : 'بناءً على تحليلات المنصة')
                // ->descriptionIcon('heroicon-m-brain-chip') // تأكد من وجود أيقونة متاحة
                ->color('warning'),

            Stat::make('مشاهدات محتواي', $interactionQuery->count())
                ->description('إجمالي التفاعلات التعليمية')
                ->descriptionIcon('heroicon-m-play-circle')
                ->color('info'),
        ];
    }
}
