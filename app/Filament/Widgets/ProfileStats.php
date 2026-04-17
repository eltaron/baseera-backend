<?php

namespace App\Filament\Widgets;

use App\Models\LearningProfile;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProfileStats extends BaseWidget
{
    protected function getStats(): array
    {
        $query = LearningProfile::query();

        // فلترة لو كان الحساب معلم (عبر جلب ملفات طلاب مواده)
        if (auth()->user()->hasRole('Teacher')) {
            $query->whereHas('user', function ($studentQuery) {
                $studentQuery->whereHas('interactions.video', fn($v) => $v->where('teacher_id', auth()->id()));
            });
        }

        return [
            Stat::make('طلاب في مستوى متقدم', (clone $query)->where('current_level', 'advanced')->count())
                ->description('الذين تخطوا كافة الصعاب')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),

            Stat::make('المستوى السائد للطلاب', function () use ($query) {
                $dominant = (clone $query)->selectRaw('current_level, count(*) as count')
                    ->groupBy('current_level')
                    ->orderBy('count', 'desc')
                    ->first();

                $levels = ['beginner' => 'مبتدئ', 'intermediate' => 'متوسط', 'advanced' => 'متقدم'];
                return $dominant ? $levels[$dominant->current_level] : 'غير محدد';
            })->description('أغلبية الطلاب يقعون هنا')->color('warning'),

            Stat::make('الملفات النشطة المكتملة', (clone $query)->whereNotNull('preferred_learning_style')->count())
                ->description('ملفات تم استنتاج نمطها بالـ AI')
                ->color('info'),
        ];
    }
}
