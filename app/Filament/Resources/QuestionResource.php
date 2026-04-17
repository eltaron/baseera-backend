<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Widgets\QuestionStats;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Builder;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationGroup = 'المحتوى التعليمي';
    protected static ?string $label = 'سؤال تقييمي';
    protected static ?string $pluralLabel = 'بنك الأسئلة';
    protected static ?string $recordTitleAttribute = 'question_text';
    protected static ?int $navigationSort = 3;

    // 1. صلاحيات المعلم (يشوف أسئلته فقط)
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
                Section::make('سياق السؤال')
                    ->description('اربط السؤال بالفيديو المناسب وحدد هويته.')
                    ->columns(2)
                    ->schema([
                        Select::make('video_id')
                            ->label('الفيديو التعليمي')
                            ->relationship('video', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Hidden::make('teacher_id')
                            ->default(auth()->id()),
                    ]),

                Section::make('محتوى السؤال والخيارات')
                    ->description('اكتب نص السؤال وخيارات الإجابة بدقة.')
                    ->schema([
                        Textarea::make('question_text')
                            ->label('نص السؤال')
                            ->placeholder('اكتب السؤال هنا...')
                            ->required()
                            ->columnSpanFull(),

                        // الـ Simple Repeater ليتوافق مع المصفوفة ["a", "b"]
                        Repeater::make('options')
                            ->label('خيارات الإجابة')
                            ->simple( // هذه الميزة تجعل الريبيتر يتعامل مع نصوص مباشرة (Array of Strings)
                                TextInput::make('option')->required(),
                            )
                            ->addActionLabel('أضف خياراً')
                            ->minItems(2)
                            ->maxItems(4)
                            ->columnSpanFull(),

                        TextInput::make('correct_answer')
                            ->label('الإجابة الصحيحة')
                            ->helperText('اكتب النص المطابق تماماً للخيار الصحيح.')
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('video.title')
                    ->label('الفيديو المرتبط')
                    ->limit(30)
                    ->sortable()
                    ->description(fn(Question $record): string => "المادة: " . ($record->video->lesson->unit->subject->name ?? 'N/A')),

                Tables\Columns\TextColumn::make('question_text')
                    ->label('السؤال')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('options')
                    ->label('عدد الخيارات')
                    ->badge()
                    ->color('gray')
                    ->getStateUsing(function ($record) {
                        // نتحقق من البيانات المخزنة فعلياً في السجل
                        $options = $record->options;

                        if (is_array($options)) {
                            // لو البيانات كانت نصوص بسيطة (مثل الداتا اللي ورتهالي)
                            // نرجع طول المصفوفة
                            return count($options) . " خيارات";
                        }

                        return "0 خيارات";
                    }),
                Tables\Columns\TextColumn::make('correct_answer')
                    ->label('الإجابة الصحيحة')
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-m-check-badge'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('Y-m-d')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('video')
                    ->label('تصفية بالفيديو')
                    ->relationship('video', 'title')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            QuestionStats::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
