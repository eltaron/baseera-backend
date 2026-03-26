<?php

namespace App\Filament\Widgets;

use App\Models\VideoInteraction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestActivities extends BaseWidget
{
    protected static ?string $heading = 'آخر نشاطات الطلاب';
    protected int | string | array $columnSpan = 'full'; // جعل الجدول يأخذ عرض الصفحة كاملاً

    public function table(Table $table): Table
    {
        return $table
            ->query(
                VideoInteraction::query()->latest()->limit(10) // جلب آخر 10 تفاعلات
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('اسم الطالب')
                    ->searchable(),

                Tables\Columns\TextColumn::make('video.title')
                    ->label('الدرس المشاهَد')
                    ->limit(30),

                Tables\Columns\BadgeColumn::make('video.difficulty')
                    ->label('مستوى الصعوبة')
                    ->colors([
                        'success' => 'beginner',
                        'warning' => 'intermediate',
                        'danger' => 'advanced',
                    ]),

                Tables\Columns\TextColumn::make('watch_time_seconds')
                    ->label('مدة المشاهدة (ثانية)')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('وقت النشاط')
                    ->dateTime('Y-m-d H:i')
                    ->since() // يظهرها بشكل "منذ 5 دقائق"
                    ->color('gray'),
            ]);
    }
}
