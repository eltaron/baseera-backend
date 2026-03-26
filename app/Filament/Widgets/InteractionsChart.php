<?php

namespace App\Filament\Widgets;

use App\Models\VideoInteraction;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class InteractionsChart extends ChartWidget
{
    protected static ?string $heading = 'معدل التفاعل الشهري';
    protected static ?string $maxHeight = '300px';
    protected int | string | array $columnSpan = 'full'; // جعل الجدول يأخذ عرض الصفحة كاملاً

    protected function getData(): array
    {
        // محاكاة بيانات حقيقية: نجمع عدد التفاعلات لكل شهر في السنة الحالية
        $data = VideoInteraction::selectRaw('COUNT(*) as count, MONTH(created_at) as month')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // تجهيز مصفوفة لـ 12 شهر لضمان ظهور الرسم كاملاً
        $values = [];
        $months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];

        for ($i = 1; $i <= 12; $i++) {
            $values[] = $data[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'فيديوهات مشاهَدة',
                    'data' => $values,
                    'fill' => 'start',
                    'borderColor' => 'rgb(255, 123, 0)', // لون بصيرة البرتقالي
                    'backgroundColor' => 'rgba(255, 123, 0, 0.1)',
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line'; // نوع الرسم بياني خطي
    }
}
