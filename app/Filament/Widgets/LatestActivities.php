<?php

namespace App\Filament\Widgets;

use App\Models\VideoInteraction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestActivities extends BaseWidget
{
    protected static ?string $heading = 'آخر نشاطات الطلاب';
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $teacherId = auth()->id();
        $isTeacher = auth()->user()->hasRole('Teacher');

        return $table
            ->query(
                VideoInteraction::query()
                    ->when($isTeacher, function (Builder $query) use ($teacherId) {
                        // فلترة الجدول للمُعلم
                        return $query->whereHas('video', fn($v) => $v->where('teacher_id', $teacherId));
                    })
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('اسم الطالب')
                    ->searchable(),

                Tables\Columns\TextColumn::make('video.title')
                    ->label('الدرس المشاهَد')
                    ->limit(40),

                Tables\Columns\BadgeColumn::make('video.difficulty')
                    ->label('المستوى')
                    ->colors([
                        'success' => 'beginner',
                        'warning' => 'intermediate',
                        'danger' => 'advanced',
                    ]),

                Tables\Columns\TextColumn::make('watch_time_seconds')
                    ->label('المدة')
                    ->formatStateUsing(fn($state) => floor($state / 60) . ":" . ($state % 60) . " د")
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('وقت النشاط')
                    ->dateTime('H:i')
                    ->since()
                    ->color('gray'),
            ]);
    }
}
