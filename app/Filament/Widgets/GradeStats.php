<?php

namespace App\Filament\Widgets;

use App\Models\Grade;
use App\Models\User;
use App\Models\Unit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GradeStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('عدد مراحل المنهج', Grade::count())
                ->description('إجمالي الصفوف التعليمية')
                ->descriptionIcon('heroicon-m-identification')
                ->color('info'),

            Stat::make('إجمالي وحدات المنهج', Unit::count())
                ->description('المحتوى الدراسي الكامل')
                ->color('warning'),

            Stat::make('الطلاب في المدارس', User::where('role', 'student')->count())
                ->description('إجمالي المسجلين في كافة الصفوف')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),
        ];
    }
}
