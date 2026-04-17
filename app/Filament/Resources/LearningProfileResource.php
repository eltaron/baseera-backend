<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LearningProfileResource\Pages;
use App\Filament\Widgets\ProfileStats;
use App\Models\LearningProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;

class LearningProfileResource extends Resource
{
    protected static ?string $model = LearningProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification'; // أيقونة بطاقة التعريف
    protected static ?string $navigationGroup = 'تقارير وتحليلات الـ AI';
    protected static ?string $label = 'ملف تعليمي';
    protected static ?string $pluralLabel = 'ملفات تعلم الطلاب';

    // 1. صلاحيات الوصول: المعلم يرى ملفات الطلاب الذين يتفاعلون مع مواده فقط
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        if (auth()->user()->hasRole('Teacher')) {
            return $query->whereHas('user', function ($studentQuery) {
                $studentQuery->whereHas('interactions.video', function ($v) {
                    $v->where('teacher_id', auth()->id());
                });
            });
        }
        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('الملخص الذكي للطالب')
                    ->description('توضيح نمط التعلم والمستوى الحالي كما تم استنتاجه.')
                    ->schema([
                        Select::make('user_id')
                            ->label('الطالب')
                            ->relationship('user', 'name', fn(Builder $query) => $query->where('role', 'student'))
                            ->searchable()
                            ->preload()
                            ->disabled() // الملف يُحدث آلياً
                            ->required(),

                        Select::make('current_level')
                            ->label('المستوى التعليمي الحالي')
                            ->options([
                                'beginner' => 'مبتدئ',
                                'intermediate' => 'متوسط',
                                'advanced' => 'متقدم',
                            ])
                            ->required(),

                        TextInput::make('preferred_learning_style')
                            ->label('نمط التعلم المفضل (AI المستنتج)')
                            ->placeholder('مثلاً: بصري (Visual)')
                            ->maxLength(255),
                    ])->columns(3),

                Section::make('تحليل القدرات')
                    ->description('نقاط القوة والضعف المرصودة من خلال التفاعل مع الفيديوهات والاختبارات.')
                    ->schema([
                        TagsInput::make('strengths')
                            ->label('نقاط القوة')
                            ->placeholder('أضف مهارة متقنة')
                            ->color('success'),

                        TagsInput::make('weaknesses')
                            ->label('نقاط تحتاج لتطوير')
                            ->placeholder('أضف مهارة تحتاج لدعم')
                            ->color('danger'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('الطالب')
                    ->searchable()
                    ->sortable()
                    ->description(fn($record) => "الصف الدراسي: {$record->user->grade_level}"),

                Tables\Columns\TextColumn::make('current_level')
                    ->label('المستوى')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'beginner' => 'مبتدئ',
                        'intermediate' => 'متوسط',
                        'advanced' => 'متقدم',
                        default => $state
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'beginner' => 'gray',
                        'intermediate' => 'warning',
                        'advanced' => 'success',
                        default => 'primary'
                    }),

                Tables\Columns\TextColumn::make('preferred_learning_style')
                    ->label('نمط التعلم')
                    ->icon('heroicon-m-user-circle')
                    ->color('info')
                    ->searchable(),

                // عرض نقاط القوة بشكل مصغر في الجدول
                Tables\Columns\TextColumn::make('strengths')
                    ->label('أهم القوى')
                    ->listWithLineBreaks()
                    ->limitList(1)
                    ->bulleted()
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخر تحديث للملف')
                    ->dateTime('Y-m-d')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('current_level')
                    ->label('حسب المستوى')
                    ->options([
                        'beginner' => 'مبتدئين',
                        'intermediate' => 'متوسطين',
                        'advanced' => 'متقدمين',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getWidgets(): array
    {
        return [
            ProfileStats::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLearningProfiles::route('/'),
            'create' => Pages\CreateLearningProfile::route('/create'),
            'edit' => Pages\EditLearningProfile::route('/{record}/edit'),
        ];
    }
}
