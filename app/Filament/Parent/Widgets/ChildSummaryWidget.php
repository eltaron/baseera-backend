<?php

namespace App\Filament\Parent\Widgets;

use App\Models\StudentProgress;
use App\Models\BehavioralAnalysis;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ChildSummaryWidget extends BaseWidget
{
    /**
     * حساب وعرض البطاقات الإحصائية للوالدين
     */
    protected function getStats(): array
    {
        // 1. تحديد هويات الأبناء التابعين لولي الأمر
        $childIds = User::where('parent_id', auth()->id())
            ->where('role', 'student')
            ->pluck('id');

        // إذا لم يكن هناك أبناء مسجلين، نعيد مصفوفة فارغة
        if ($childIds->isEmpty()) {
            return [];
        }

        // 2. حساب المتوسطات من الجداول المختلفة
        $avgProgress = StudentProgress::whereIn('user_id', $childIds)->avg('completion_percentage') ?? 0;
        $avgScore = StudentProgress::whereIn('user_id', $childIds)->avg('overall_score') ?? 0;
        $avgFocus = BehavioralAnalysis::whereIn('user_id', $childIds)->avg('focus_level') ?? 0;

        return [
            // بطاقة (1): متوسط إنجاز المناهج
            Stat::make('متوسط تقدم الأبناء', round($avgProgress, 1) . '%')
                ->description('نسبة إتمام الدروس المقررة')
                ->descriptionIcon('heroicon-m-chart-pie')
                ->chart([$avgProgress - 10, $avgProgress - 5, $avgProgress]) // محاكاة نمو بسيط
                ->color('info'),

            // بطاقة (2): جودة التعلم (الدرجات)
            Stat::make('جودة الاستيعاب العام', round($avgScore, 1) . ' نقطة')
                ->description('بناءً على نتائج التحديات والأسئلة')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),

            // بطاقة (3): تحليل التركيز من الذكاء الاصطناعي
            Stat::make('متوسط الانتباه الذكي', round($avgFocus, 1) . '%')
                ->description('قياس التركيز أثناء مشاهدة الفيديو')
                ->descriptionIcon('heroicon-m-eye')
                ->color(match (true) {
                    $avgFocus >= 70 => 'success',
                    $avgFocus >= 50 => 'warning',
                    default => 'danger'
                }),
        ];
    }
}
