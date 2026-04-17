<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BehavioralAnalysisResource\Pages;
use App\Filament\Widgets\BehavioralStats;
use App\Models\BehavioralAnalysis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;

class BehavioralAnalysisResource extends Resource
{
    protected static ?string $model = BehavioralAnalysis::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass-circle';
    protected static ?string $navigationGroup = 'تقارير وتحليلات الـ AI';
    protected static ?string $label = 'تحليل سلوكي';
    protected static ?string $pluralLabel = 'غرفة مراقبة السلوك';

    // 1. صلاحيات الوصول: المعلم يراقب فقط سلوكيات الطلاب في "دروسه هو فقط"
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
                Section::make('النتائج اللحظية للذكاء الاصطناعي')
                    ->description('مخرجات الكاميرا وتحليل السلوك المرئي (هذه البيانات تُنشأ آلياً بواسطة الـ Model).')
                    ->icon('heroicon-m-eye')
                    ->schema([
                        Select::make('user_id')
                            ->label('الطالب')
                            ->relationship('user', 'name')
                            ->disabled(),

                        Select::make('video_id')
                            ->label('الدرس/الفيديو')
                            ->relationship('video', 'title')
                            ->disabled(),

                        TextInput::make('focus_level')
                            ->label('مستوى التركيز (%)')
                            ->suffix('%')
                            ->numeric(),

                        TextInput::make('confusion_level')
                            ->label('درجة الحيرة (%)')
                            ->suffix('%')
                            ->numeric(),

                        TextInput::make('boredom_level')
                            ->label('معدل الملل (%)')
                            ->suffix('%')
                            ->numeric(),

                        TextInput::make('detected_learning_style')
                            ->label('نمط التعلم المستنتج')
                            // ->icon('heroicon-m-user-circle')
                            ->placeholder('لم يتم الاستنتاج بعد'),
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

                Tables\Columns\TextColumn::make('video.title')
                    ->label('الدرس المرتبط')
                    ->limit(25),

                // مستوى التركيز (أخضر كلما زاد)
                Tables\Columns\TextColumn::make('focus_level')
                    ->label('التركيز')
                    ->badge()
                    ->color(fn($state) => $state > 70 ? 'success' : ($state > 40 ? 'warning' : 'danger'))
                    ->formatStateUsing(fn($state) => "%$state"),

                // درجة الحيرة (أحمر كلما زاد)
                Tables\Columns\TextColumn::make('confusion_level')
                    ->label('الحيرة')
                    ->badge()
                    ->color(fn($state) => $state > 50 ? 'danger' : ($state > 20 ? 'warning' : 'success'))
                    ->formatStateUsing(fn($state) => "%$state"),

                // معدل الملل
                Tables\Columns\TextColumn::make('boredom_level')
                    ->label('الملل')
                    ->badge()
                    ->color(fn($state) => $state > 50 ? 'danger' : 'gray')
                    ->formatStateUsing(fn($state) => "%$state"),

                Tables\Columns\TextColumn::make('detected_learning_style')
                    ->label('نمط التعلم (AI)')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('توقيت التحليل')
                    ->dateTime('H:i | Y-m-d')
                    ->since()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('video')
                    ->relationship('video', 'title')
                    ->label('فلترة بالدرس'),

                Tables\Filters\Filter::make('high_confusion')
                    ->label('طلاب يحتاجون مساعدة (حيرة عالية)')
                    ->query(fn(Builder $query): Builder => $query->where('confusion_level', '>', 50)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->hidden(!auth()->user()->hasRole('super_admin')), // حذف سجلات الـ AI متاح للأدمن فقط
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])->hidden(!auth()->user()->hasRole('super_admin')),
            ])
            ->defaultSort('created_at', 'desc');
    }
    public static function getWidgets(): array
    {
        return [
            BehavioralStats::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBehavioralAnalyses::route('/'),
            'create' => Pages\CreateBehavioralAnalysis::route('/create'),
            'edit' => Pages\EditBehavioralAnalysis::route('/{record}/edit'),
        ];
    }
}
