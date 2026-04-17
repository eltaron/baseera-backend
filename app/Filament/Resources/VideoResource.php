<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoResource\Pages;
use App\Filament\Resources\VideoResource\RelationManagers;
use App\Filament\Widgets\VideoStats;
use App\Models\Video;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;
    protected static ?string $recordTitleAttribute = 'title';

    // تحسين الهوية البصرية
    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'المحتوى التعليمي';
    protected static ?string $label = 'فيديو تعليمي';
    protected static ?string $pluralLabel = 'الفيديوهات';
    protected static ?int $navigationSort = 2;
    // 1. نظام الصلاحيات: المعلم يرى فيديوهاته فقط، والأدمن يرى الكل
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        if (auth()->user()->hasRole('Teacher')) {
            return $query->where('teacher_id', auth()->id());
        }
        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('تفاصيل الفيديو الذكي')
                    ->description('قم بربط الفيديو بالدرس وتحديد المهارة ومستوى الصعوبة')
                    ->icon('heroicon-m-play-circle')
                    ->columns(2)
                    ->schema([
                        // اختيار الدرس بالاسم وليس بالرقم
                        Select::make('lesson_id')
                            ->label('الدرس المرتبط')
                            ->relationship('lesson', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('title')
                            ->label('عنوان الفيديو')
                            ->placeholder('مثلاً: شرح مفهوم الاستلاف بصرياً')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('video_url')
                            ->label('رابط الفيديو')
                            ->placeholder('URL من YouTube أو Drive')
                            ->url() // التأكد من صحة الرابط
                            ->required(),

                        TextInput::make('skill')
                            ->label('المهارة التي يغطيها')
                            ->placeholder('مثلاً: الطرح الرقمي')
                            ->required(),

                        Select::make('difficulty')
                            ->label('مستوى الصعوبة للـ AI')
                            ->options([
                                'beginner' => 'مبتدئ',
                                'intermediate' => 'متوسط',
                                'advanced' => 'متقدم',
                            ])
                            ->native(false)
                            ->required(),

                        TextInput::make('duration_seconds')
                            ->label('مدة الفيديو (بالثواني)')
                            ->numeric()
                            ->helperText('ستستخدم هذه المدة في حساب % Watch Time للذكاء الاصطناعي')
                            ->prefix('ثانية')
                            ->minValue(1),

                        // إخفاء الـ Teacher ID وتعبئته تلقائياً من المستخدم الحالي
                        Forms\Components\Hidden::make('teacher_id')
                            ->default(auth()->id()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان الفيديو')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn(Video $record): string => "الدرس: {$record->lesson->title}"),

                Tables\Columns\TextColumn::make('video_url')
                    ->label('الرابط')
                    ->icon('heroicon-m-link')
                    ->color('primary')
                    ->limit(20)
                    ->copyable() // إمكانية النسخ بضغطة زر
                    ->openUrlInNewTab(), // يفتح الفيديو في صفحة جديدة عند الضغط

                Tables\Columns\TextColumn::make('skill')
                    ->label('المهارة')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('difficulty')
                    ->label('الصعوبة')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'beginner' => 'مبتدئ',
                        'intermediate' => 'متوسط',
                        'advanced' => 'متقدم',
                    })
                    ->colors([
                        'success' => 'beginner',
                        'warning' => 'intermediate',
                        'danger' => 'advanced',
                    ]),

                Tables\Columns\TextColumn::make('duration_seconds')
                    ->label('المدة')
                    ->formatStateUsing(fn($state) => floor($state / 60) . ":" . ($state % 60) . " دقيقة")
                    ->sortable(),

                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('المعلم')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الرفع')
                    ->dateTime('Y-m-d')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('difficulty')
                    ->label('مستوى الصعوبة')
                    ->options([
                        'beginner' => 'مبتدئ',
                        'intermediate' => 'متوسط',
                        'advanced' => 'متقدم',
                    ]),
                SelectFilter::make('lesson')
                    ->label('حسب الدرس')
                    ->relationship('lesson', 'title'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('لم يتم رفع أي فيديوهات بعد');
    }

    public static function getRelations(): array
    {
        return [
            // سنضيف هنا لاحقاً Relation Manager للأسئلة المرتبطة بالفيديو
            RelationManagers\QuestionsRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            VideoStats::class,
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}
