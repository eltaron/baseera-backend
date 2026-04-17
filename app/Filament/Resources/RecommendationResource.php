<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecommendationResource\Pages;
use App\Filament\Widgets\RecommendationStats;
use App\Models\Recommendation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Builder;

class RecommendationResource extends Resource
{
    protected static ?string $model = Recommendation::class;

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb'; // أيقونة المصباح للإلهام والتوصية
    protected static ?string $navigationGroup = 'تقارير وتحليلات الـ AI';
    protected static ?string $label = 'توصية ذكية';
    protected static ?string $pluralLabel = 'مقترحات التعلم';

    // 1. نظام الصلاحيات: المعلم يرى التوصيات المرتبطة بفيديوهاته فقط
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        if (auth()->user()->hasRole('Teacher')) {
            return $query->whereHas('video', fn($q) => $q->where('teacher_id', auth()->id()));
        }
        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('تفاصيل التوصية الأكاديمية')
                    ->description('مراجعة المقترح الذي قدمه نظام الذكارة الاصطناعي للطالب.')
                    ->schema([
                        Select::make('user_id')
                            ->label('الطالب')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('video_id')
                            ->label('المحتوى المُقترح')
                            ->relationship('video', 'title')
                            ->required(),

                        Textarea::make('reason')
                            ->label('سبب التوصية (Insight)')
                            ->placeholder('مثلاً: الطالب يحتاج تقوية في مهارة س بناءً على تعثره في اختبار ص')
                            ->required()
                            ->columnSpanFull(),

                        Toggle::make('is_viewed')
                            ->label('هل اطلع الطالب عليها؟')
                            ->onIcon('heroicon-m-eye')
                            ->offIcon('heroicon-m-eye-slash')
                            ->inline(false)
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
                    ->description(fn($record) => "الصف: " . ($record->user->grade_level ?? 'N/A')),

                Tables\Columns\TextColumn::make('video.title')
                    ->label('المحتوى المقترح')
                    ->icon('heroicon-m-sparkles')
                    ->iconColor('warning')
                    ->limit(25),

                Tables\Columns\TextColumn::make('reason')
                    ->label('السبب الذكي')
                    ->limit(40)
                    ->tooltip(fn($record) => $record->reason),

                Tables\Columns\IconColumn::make('is_viewed')
                    ->label('تفاعل الطالب')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ التوصية')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('video')
                    ->label('حسب الفيديو')
                    ->relationship('video', 'title'),

                Tables\Filters\TernaryFilter::make('is_viewed')
                    ->label('اكتملت المشاهدة؟'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            RecommendationStats::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecommendations::route('/'),
            'create' => Pages\CreateRecommendation::route('/create'),
            'edit' => Pages\EditRecommendation::route('/{record}/edit'),
        ];
    }
}
