<?php

namespace App\Filament\Resources\VideoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions'; // اسم العلاقة في موديل Video

    protected static ?string $title = 'الأسئلة التفاعلية بعد الفيديو';

    protected static ?string $modelLabel = 'سؤال';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('إنشاء سؤال ذكي')
                    ->description('أضف السؤال وخيارات الإجابة وحدد الإجابة الصحيحة لتقييم فهم الطفل.')
                    ->icon('heroicon-m-question-mark-circle')
                    ->schema([
                        Textarea::make('question_text')
                            ->label('نص السؤال')
                            ->placeholder('مثلاً: كم يساوي حاصل طرح 10 من 5 بالاستلاف؟')
                            ->required()
                            ->columnSpanFull(),

                        // استخدام Repeater لإدارة مصفوفة الاختيارات (JSON)
                        Repeater::make('options')
                            ->label('خيارات الإجابة')
                            ->simple( // هذه الميزة تجعل الريبيتر يتعامل مع نصوص مباشرة (Array of Strings)
                                TextInput::make('option')->required(),
                            )
                            ->addActionLabel('أضف خياراً')
                            ->minItems(2)
                            ->maxItems(4) // أقصى حد 4 اختيارات كما هو متبع تربوياً
                            ->columnSpanFull(),

                        TextInput::make('correct_answer')
                            ->label('الإجابة الصحيحة (نصياً)')
                            ->placeholder('اكتب النص المطابق تماماً لأحد الخيارات أعلاه')
                            ->required()
                            ->helperText('يجب أن تطابق الإجابة المكتوبة هنا أحد الخيارات التي أدخلتها بالأعلى بدقة.'),

                        // إرسال الـ Teacher ID تلقائياً لضمان ملكية المدرس للسؤال
                        Forms\Components\Hidden::make('teacher_id')
                            ->default(auth()->id()),
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question_text')
            ->columns([
                Tables\Columns\TextColumn::make('question_text')
                    ->label('نص السؤال')
                    ->limit(50)
                    ->searchable()
                    ->tooltip(fn($record) => $record->question_text),

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
                    ->label('تاريخ الإضافة')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('إضافة سؤال جديد')
                    ->icon('heroicon-m-plus-circle')
                    ->modalHeading('إنشاء سؤال تفاعلي جديد للفيديو')
                    ->modalButton('حفظ السؤال'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('تعديل السؤال'),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('حذف السؤال')
                    ->modalDescription('هل أنت متأكد من حذف هذا السؤال؟ سيؤثر ذلك على تقارير الطلاب المرتبطة به.'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('لا يوجد أسئلة لهذا الفيديو')
            ->emptyStateDescription('اضغط على "إضافة سؤال" لبدء بناء التحدي للطلاب.');
    }
}
