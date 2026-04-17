<?php

namespace App\Filament\Parent\Widgets;

use App\Models\BehavioralAnalysis;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChildFocusChart extends ChartWidget
{
    // عنوان الرسم البياني بلغة تربوية
    protected static ?string $heading = 'مؤشر انتباه الأبناء خلال الأسبوع الحالي';
    protected int | string | array $columnSpan = 'full';
    // وصف صغير يظهر تحت العنوان
    protected static ?string $maxHeight = '300px';

    /**
     * جلب وتحليل البيانات لعرضها في الرسم البياني
     */
    protected function getData(): array
    {
        // 1. تحديد هويات الأبناء التابعين لولي الأمر المسجل حالياً
        $childIds = User::where('parent_id', auth()->id())
            ->where('role', 'student')
            ->pluck('id');

        // 2. جلب متوسط التركيز لكل يوم في آخر 7 أيام
        $data = BehavioralAnalysis::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('AVG(focus_level) as average_focus')
        )
            ->whereIn('user_id', $childIds)
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // 3. تحضير المصفوفات للعرض (Labels & Values)
        $labels = [];
        $values = [];

        // التأكد من تغطية الـ 7 أيام حتى لو لم توجد داتا (لجعل الرسم متصلاً)
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->translatedFormat('l'); // اسم اليوم بالعربي

            // البحث عن قيمة في الداتا المستخرجة لهذا اليوم
            $record = $data->firstWhere('date', $day);
            $values[] = $record ? round($record->average_focus, 1) : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'نسبة التركيز الذكي (%)',
                    'data' => $values,
                    'borderColor' => '#4f46e5', // لون نيلي (Indigo) يناسب هوية ولي الأمر
                    'backgroundColor' => 'rgba(79, 70, 229, 0.1)', // هالة لونية تحت الخط
                    'fill' => 'start',
                    'tension' => 0.4, // انحناء ناعم للخط يعطي شكل تكنولوجي حديث
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line'; // خط بياني لتوضيح "مسار" الانتباه صعوداً وهبوطاً
    }
}
