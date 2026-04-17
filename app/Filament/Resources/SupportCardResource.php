<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupportCardResource\Pages;
use App\Models\SupportCard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;

class SupportCardResource extends Resource
{
    protected static ?string $model = SupportCard::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';
    protected static ?string $navigationGroup = 'إدارة المنصة العامة';
    protected static ?string $label = 'كارت دعم';
    protected static ?string $pluralLabel = 'كروت مركز الدعم';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('محتوى الكارت')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('عنوان الكارت')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('icon')
                            ->label('كود أيقونة FontAwesome')
                            ->helperText('مثال: fa-microchip أو fa-user-shield')
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('وصف الخدمة')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('الزر والإجراءات')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('button_text')
                            ->label('نص الزر')
                            ->default('تواصل الآن')
                            ->required(),

                        Forms\Components\TextInput::make('button_url')
                            ->label('رابط الزر (أو Anchor)')
                            ->helperText('استخدم #contact لفتح تذكرة دعم أو ضع رابط خارجي.')
                            ->required(),

                        Forms\Components\ColorPicker::make('border_color')
                            ->label('لون التمييز (Border & Icon)')
                            ->default('#FF7B00')
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('تفعيل الظهور')
                            ->default(true)
                            ->onColor('success'),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('ترتيب العرض')
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('العنوان')->searchable(),
                Tables\Columns\ColorColumn::make('border_color')->label('اللون المعرف'),
                Tables\Columns\IconColumn::make('is_active')->label('الحالة')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->label('الترتيب')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupportCards::route('/'),
            'create' => Pages\CreateSupportCard::route('/create'),
            'edit' => Pages\EditSupportCard::route('/{record}/edit'),
        ];
    }
}
