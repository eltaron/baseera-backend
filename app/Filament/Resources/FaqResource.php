<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationGroup = 'إدارة المنصة العامة';
    protected static ?string $label = 'سؤال شائع';
    protected static ?string $pluralLabel = 'الأسئلة الشائعة (FAQ)';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('تفاصيل السؤال والجواب')
                    ->schema([
                        Forms\Components\TextInput::make('question')
                            ->label('السؤال')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('answer')
                            ->label('الإجابة المفصلة')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('إعدادات الظهور')
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')
                            ->label('ترتيب العرض')
                            ->numeric()
                            ->default(0),

                        Forms\Components\Toggle::make('is_active')
                            ->label('تفعيل الظهور في الصفحة الرئيسية')
                            ->default(true)
                            ->onColor('success'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order') // تفعيل ميزة السحب والافلات للترتيب
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->label('السؤال')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('نشط'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('الترتيب')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('Y-m-d')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
