<?php

namespace App\Filament\Widgets;

use App\Models\Question;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QuestionStats extends BaseWidget
{
    protected function getStats(): array
    {
        $query = Question::query();

        if (auth()->user()->hasRole('Teacher')) {
            $query->where('teacher_id', auth()->id());
        }

        return [
            Stat::make('إجمالي الأسئلة في بنكك', $query->count())
                ->icon('heroicon-o-chat-bubble-bottom-center-text')
                ->color('info'),

            Stat::make(
                'متوسط الأسئلة لكل فيديو',
                $query->count() > 0 ? round($query->count() / \App\Models\Video::count(), 1) : 0
            )->color('success'),

            Stat::make(
                'الفيديوهات المغطاة بأسئلة',
                $query->distinct('video_id')->count()
            )->color('warning'),
        ];
    }
}
