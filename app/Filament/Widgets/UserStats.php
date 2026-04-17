<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('إجمالي المستخدمين', User::count())
                ->description('كامل المسجلين بالمنصة')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('عدد المعلمين', User::where('role', 'teacher')->count())
                ->description('مشرفين أكاديميين نشطين')
                ->color('success'),

            Stat::make('إجمالي الطلاب', User::where('role', 'student')->count())
                ->description('أبطال "بصيرة" الصغار')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('warning'),
        ];
    }
}
