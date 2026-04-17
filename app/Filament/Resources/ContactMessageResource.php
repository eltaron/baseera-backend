<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope-open';
    protected static ?string $navigationGroup = 'إدارة المنصة العامة';
    protected static ?string $label = 'رسالة تواصل';
    protected static ?string $pluralLabel = 'بريد الوارد (تواصل معنا)';



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم المرسل')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->searchable(),

                Tables\Columns\SelectColumn::make('status')
                    ->label('حالة الرسالة')
                    ->options([
                        'unread' => 'غير مقروءة',
                        'read' => 'تمت القراءة',
                        'replied' => 'تم الرد',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الاستلام')
                    ->dateTime('Y-m-d H:i')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('تصفية بالحالة')
                    ->options([
                        'unread' => 'رسائل جديدة',
                        'read' => 'تمت قراءتها',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض الرسالة الكاملة'), // استخدام عرض وليس تعديل
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    /**
     * استخدام الـ Infolist لعرض الرسالة بشكل شيك بدون تعديل
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('محتوى الرسالة الواردة')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')->label('اسم صاحب الطلب'),
                        Infolists\Components\TextEntry::make('email')->label('البريد للتواصل')->copyable(),
                        Infolists\Components\TextEntry::make('created_at')->label('وقت الوصول')->dateTime(),
                        Infolists\Components\TextEntry::make('message')
                            ->label('نص الرسالة والاستفسار')
                            ->columnSpanFull()
                            ->prose() // جعل النص مريحاً للقراءة
                            ->extraAttributes(['class' => ' p-4 rounded-xl']),
                    ])->columns(3),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            // لا حاجة لصفحة create هنا لأن الإضافة من الموقع الخارجي فقط
            'view' => Pages\ViewContactMessage::route('/{record}'),
        ];
    }
}
