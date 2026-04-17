<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentProgressResource\Pages;
use App\Filament\Widgets\ProgressStats;
use App\Models\StudentProgress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;

class StudentProgressResource extends Resource
{
    protected static ?string $model = StudentProgress::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationGroup = 'تقارير وتحليلات الـ AI';
    protected static ?string $label = 'تقدم طالب';
    protected static ?string $pluralLabel = 'سجل التقدم العام';

    // 1. صلاحيات الوصول: المعلم يرى فقط تقدم الطلاب في مواده هو
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        if (auth()->user()->hasRole('Teacher')) {
            return $query->whereHas('subject', fn($q) => $q->where('teacher_id', auth()->id()));
        }
        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('مؤشرات الأداء الدراسي')
                    ->description('توضيح حالة الطالب الدراسية في مادة محددة.')
                    ->icon('heroicon-m-academic-cap')
                    ->schema([
                        Select::make('user_id')
                            ->label('الطالب المستهدف')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),

                        Select::make('subject_id')
                            ->label('المادة العلمية')
                            ->relationship('subject', 'name')
                            ->required(),

                        TextInput::make('completed_lessons_count')
                            ->label('عدد الدروس المنجزة')
                            ->numeric()
                            ->default(0),

                        TextInput::make('overall_score')
                            ->label('الدرجة الإجمالية')
                            ->numeric()
                            ->suffix('نقطة'),

                        TextInput::make('completion_percentage')
                            ->label('نسبة إتمام المنهج (%)')
                            ->numeric()
                            ->prefix('%')
                            ->maxValue(100),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('اسم الطالب')
                    ->searchable()
                    ->sortable()
                    ->description(fn(StudentProgress $record) => "الصف: {$record->user->grade_level}"),

                Tables\Columns\TextColumn::make('subject.name')
                    ->label('المادة')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('completed_lessons_count')
                    ->label('الدروس المنجزة')
                    ->icon('heroicon-m-check-circle')
                    ->alignCenter(),

                // الدرجة الإجمالية بلون حسب القيمة
                Tables\Columns\TextColumn::make('overall_score')
                    ->label('التقييم العام')
                    ->badge()
                    ->color(fn($state) => $state >= 75 ? 'success' : 'warning')
                    ->sortable(),

                // نسبة الإنجاز مع ظهور كـ Badge دائري
                Tables\Columns\TextColumn::make('completion_percentage')
                    ->label('نسبة الإتمام')
                    ->formatStateUsing(fn($state) => "%$state")
                    ->badge()
                    ->color(fn($state) => match (true) {
                        $state >= 90 => 'success',
                        $state >= 50 => 'info',
                        default => 'danger'
                    }),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخر تحديث')
                    ->dateTime('Y-m-d')
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('subject')
                    ->label('المادة')
                    ->relationship('subject', 'name'),

                Tables\Filters\Filter::make('completed')
                    ->label('طلاب أتموا المنهج (>90%)')
                    ->query(fn(Builder $query): Builder => $query->where('completion_percentage', '>=', 90)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('completion_percentage', 'desc');
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getWidgets(): array
    {
        return [
            ProgressStats::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudentProgress::route('/'),
            'create' => Pages\CreateStudentProgress::route('/create'),
            'edit' => Pages\EditStudentProgress::route('/{record}/edit'),
        ];
    }
}
