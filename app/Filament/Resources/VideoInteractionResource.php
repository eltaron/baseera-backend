<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoInteractionResource\Pages;
use App\Filament\Widgets\InteractionStats;
use App\Models\VideoInteraction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;

class VideoInteractionResource extends Resource
{
    protected static ?string $model = VideoInteraction::class;

    protected static ?string $navigationIcon = 'heroicon-o-cursor-arrow-ripple';
    protected static ?string $navigationGroup = 'تقارير وتحليلات الـ AI';
    protected static ?string $label = 'تفاعل فيديو';
    protected static ?string $pluralLabel = 'سجلات التفاعل';

    // 1. صلاحيات المدرس: يرى التفاعلات المرتبطة بفيديوهاته هو فقط
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
                Section::make('بيانات التفاعل الخام')
                    ->description('مراجعة السلوك التقني للطالب أثناء المشاهدة.')
                    ->schema([
                        Select::make('user_id')
                            ->label('اسم الطالب')
                            ->relationship('user', 'name')
                            ->disabled()
                            ->required(),

                        Select::make('video_id')
                            ->label('الفيديو المستهدف')
                            ->relationship('video', 'title')
                            ->disabled()
                            ->required(),

                        TextInput::make('watch_time_seconds')
                            ->label('وقت المشاهدة الفعلي')
                            ->suffix('ثانية')
                            ->numeric(),

                        TextInput::make('replay_count')
                            ->label('مرات الإعادة (Replay)')
                            ->numeric()
                        // ->icon('heroicon-m-arrow-path')
                        ,

                        TextInput::make('pause_frequency')
                            ->label('مرات التوقف (Pause)')
                            ->numeric()
                        // ->icon('heroicon-m-pause-circle')
                        ,
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
                    ->sortable(),

                Tables\Columns\TextColumn::make('video.title')
                    ->label('الدرس')
                    ->limit(25),

                // عرض وقت المشاهدة بشكل دقيق
                Tables\Columns\TextColumn::make('watch_time_seconds')
                    ->label('المشاهدة')
                    ->formatStateUsing(fn($state) => floor($state / 60) . ":" . ($state % 60) . " د")
                    ->sortable(),

                // مؤشر مرئي لمرات التوقف (إذا كان عالياً يظهر بلون تنبيهي)
                Tables\Columns\TextColumn::make('pause_frequency')
                    ->label('مرات التوقف')
                    ->badge()
                    ->color(fn($state) => $state > 5 ? 'danger' : 'gray')
                    ->alignCenter(),

                // مؤشر مرئي لمرات الإعادة (قد تدل على عدم الفهم أو الحيرة)
                Tables\Columns\TextColumn::make('replay_count')
                    ->label('مرات الإعادة')
                    ->badge()
                    ->color(fn($state) => $state > 3 ? 'warning' : 'success')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('وقت النشاط')
                    ->dateTime('H:i | Y-m-d')
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('video')
                    ->relationship('video', 'title')
                    ->label('حسب الفيديو'),
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
            InteractionStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVideoInteractions::route('/'),
            'create' => Pages\CreateVideoInteraction::route('/create'),
            'edit' => Pages\EditVideoInteraction::route('/{record}/edit'),
        ];
    }
}
