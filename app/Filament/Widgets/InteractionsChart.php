<?php

namespace App\Filament\Widgets;

use App\Models\VideoInteraction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class InteractionsChart extends ChartWidget
{
    protected static ?string $heading = 'معدل التفاعل مع المحتوى';
    protected static ?string $maxHeight = '300px';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $teacherId = auth()->id();
        $isTeacher = auth()->user()->hasRole('Teacher');

        // جلب البيانات مع الفلترة حسب الرتبة
        $query = VideoInteraction::query()
            ->when($isTeacher, function ($q) use ($teacherId) {
                return $q->whereHas('video', fn($v) => $v->where('teacher_id', $teacherId));
            });

        $data = $query->select(
            DB::raw('COUNT(*) as count'),
            DB::raw('MONTH(created_at) as month')
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $values = [];
        $months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];

        for ($i = 1; $i <= 12; $i++) {
            $values[] = $data[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => $isTeacher ? 'مشاهدات فيديوهاتك' : 'مشاهدات المنصة بالكامل',
                    'data' => $values,
                    'borderColor' => $isTeacher ? '#00B4D8' : '#FF7B00', // لون أزرق للمعلم، برتقالي للأدمن
                    'backgroundColor' => $isTeacher ? 'rgba(0, 180, 216, 0.1)' : 'rgba(255, 123, 0, 0.1)',
                    'fill' => 'start',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
