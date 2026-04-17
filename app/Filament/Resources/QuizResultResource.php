<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizResultResource\Pages;
use App\Filament\Widgets\QuizStats;
use App\Models\QuizResult;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;

class QuizResultResource extends Resource
{
    protected static ?string $model = QuizResult::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'تقارير وتحليلات الـ AI';
    protected static ?string $label = 'نتيجة اختبار';
    protected static ?string $pluralLabel = 'نتائج الاختبارات';

    // 1. صلاحيات المعلم: يشوف فقط نتائج الطلاب في "فيديوهاته هو فقط"
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('Teacher')) {
            return $query->whereHas('video', function ($q) {
                $q->where('teacher_id', auth()->id());
            });
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('تفاصيل النتيجة')
                    ->description('مراجعة دقة وسرعة إجابة الطالب.')
                    ->schema([
                        Select::make('user_id')
                            ->label('الطالب')
                            ->relationship('user', 'name')
                            ->disabled() // النتيجة لا يجب تعديلها يدوياً غالباً
                            ->required(),

                        Select::make('video_id')
                            ->label('الفيديو المختبر')
                            ->relationship('video', 'title')
                            ->disabled()
                            ->required(),

                        TextInput::make('accuracy_percentage')
                            ->label('نسبة الدقة (%)')
                            ->numeric()
                            ->suffix('%')
                            ->required(),

                        TextInput::make('average_response_time')
                            ->label('متوسط زمن الإجابة')
                            ->numeric()
                            ->suffix('ثانية')
                            ->required(),
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
                    ->description(fn(QuizResult $record): string => "الصف: " . ($record->user->grade_level ?? 'N/A')),

                Tables\Columns\TextColumn::make('video.title')
                    ->label('الدرس/الفيديو')
                    ->searchable()
                    ->limit(30),

                // عرض الدقة بشكل ملون واحترافي
                Tables\Columns\TextColumn::make('accuracy_percentage')
                    ->label('الدقة')
                    ->numeric()
                    ->suffix('%')
                    ->badge()
                    ->color(fn(string $state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 50 => 'warning',
                        default => 'danger',
                    })
                    ->sortable(),

                // عرض سرعة الاستجابة مع أيقونة
                Tables\Columns\TextColumn::make('average_response_time')
                    ->label('سرعة الإجابة')
                    ->suffix(' ثانية')
                    ->icon('heroicon-m-clock')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الاختبار')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('video')
                    ->label('تصفية بالدرس')
                    ->relationship('video', 'title'),

                Tables\Filters\Filter::make('low_accuracy')
                    ->label('طلاب يحتاجون دعم (< 50%)')
                    ->query(fn(Builder $query): Builder => $query->where('accuracy_percentage', '<', 50)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
    public static function getWidgets(): array
    {
        return [
            QuizStats::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizResults::route('/'),
            'create' => Pages\CreateQuizResult::route('/create'),
            'edit' => Pages\EditQuizResult::route('/{record}/edit'),
        ];
    }
}
