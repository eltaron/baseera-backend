<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupportTicketResource\Pages;
use App\Models\SupportTicket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\FontWeight;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class SupportTicketResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = SupportTicket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'إدارة المنصة العامة';
    protected static ?string $label = 'تذكرة دعم';
    protected static ?string $pluralLabel = 'تذاكر الدعم الفني';

    // تفعيل البحث العالمي برقم التذكرة أو الاسم
    protected static ?string $recordTitleAttribute = 'ticket_number';

    /**
     * واجهة الإضافة والتعديل
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('معلومات البلاغ الفني')
                    ->description('تحديد تفاصيل المشكلة وأولويتها.')
                    ->icon('heroicon-m-information-circle')
                    ->schema([
                        Grid::make(3)->schema([
                            Forms\Components\TextInput::make('ticket_number')
                                ->label('رقم التذكرة')
                                ->disabled() // يتولد تلقائياً في الموديل
                                ->placeholder('سيتولد آلياً عند الحفظ')
                                ->dehydrated(false),

                            Forms\Components\Select::make('priority')
                                ->label('مستوى الأهمية')
                                ->options([
                                    'low' => 'منخفضة (استفسار عادي)',
                                    'medium' => 'متوسطة (عطل بسيط)',
                                    'high' => 'عالية جداً (توقف الخدمة) 🔥',
                                ])
                                ->required()
                                ->native(false),

                            Forms\Components\Select::make('status')
                                ->label('حالة التذكرة')
                                ->options([
                                    'open' => 'مفتوحة (جديدة)',
                                    'pending' => 'قيد المعالجة',
                                    'resolved' => 'تم الحل بنجاح ✅',
                                    'closed' => 'مغلقة 🔒',
                                ])
                                ->required()
                                ->native(false),
                        ]),

                        Forms\Components\TextInput::make('subject')
                            ->label('عنوان المشكلة / الموضوع')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('message')
                            ->label('شرح تفصيلي للمشكلة')
                            ->rows(5)
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('بيانات مقدم الطلب')
                    ->collapsed() // مخفي افتراضياً لتوفير مساحة
                    ->schema([
                        Forms\Components\TextInput::make('name')->label('الاسم')->required(),
                        Forms\Components\TextInput::make('email')->label('البريد الإلكتروني')->email()->required(),
                        Forms\Components\Select::make('user_id')
                            ->label('الحساب المرتبط بالنظام')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->placeholder('زائر غير مسجل'),
                    ])->columns(2),
            ]);
    }

    /**
     * واجهة جدول البيانات الرئيسي
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->label('الرقم المرجعي')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->fontFamily('mono')
                    ->copyable()
                    ->copyMessage('تم نسخ رقم التذكرة'),

                Tables\Columns\TextColumn::make('name')
                    ->label('صاحب الطلب')
                    ->description(fn($record) => $record->email)
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject')
                    ->label('الموضوع')
                    ->limit(30)
                    ->tooltip(fn($record) => $record->subject),

                Tables\Columns\TextColumn::make('priority')
                    ->label('الأهمية')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'low' => 'عادية',
                        'medium' => 'متوسطة',
                        'high' => 'عاجلة 🔥',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'low' => 'success',
                        'medium' => 'warning',
                        'high' => 'danger',
                    }),

                Tables\Columns\SelectColumn::make('status')
                    ->label('الحالة الحالية')
                    ->options([
                        'open' => 'مفتوحة',
                        'pending' => 'معالجة',
                        'resolved' => 'محلولة',
                        'closed' => 'مغلقة',
                    ])
                    ->selectablePlaceholder(false),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('منذ')
                    ->dateTime('Y-m-d H:i')
                    ->since()
                    ->color('gray')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->label('حسب الحالة')->options(['open' => 'مفتوح', 'resolved' => 'تم الحل']),
                Tables\Filters\SelectFilter::make('priority')->label('حسب الأولوية')->options(['high' => 'عاجل جدا', 'low' => 'منخفض']),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض'), // عرض باستخدام infolist تحت
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * عرض التذكرة بشكل "بطاقة تقنية" للقراءة فقط (إضافة ميزة رائعة للمناقشة)
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('ملخص البلاغ الفني')
                    ->schema([
                        Infolists\Components\TextEntry::make('ticket_number')->label('رقم التذكرة')->weight('bold')->color('primary'),
                        Infolists\Components\TextEntry::make('status')->label('الحالة الحالية')->badge()->color('info'),
                        Infolists\Components\TextEntry::make('priority')->label('مستوى الخطورة')->badge()->color('danger'),
                        Infolists\Components\TextEntry::make('name')->label('المرسل'),
                        Infolists\Components\TextEntry::make('email')->label('الإيميل')->copyable(),
                        Infolists\Components\TextEntry::make('created_at')->label('تاريخ الإرسال')->dateTime(),
                        Infolists\Components\TextEntry::make('subject')->label('موضوع التذكرة')->columnSpanFull()->size('lg')->weight('black'),
                        Infolists\Components\TextEntry::make('message')
                            ->label('تفاصيل العطل المرسلة من المستخدم')
                            ->columnSpanFull()
                            ->prose()
                            ->extraAttributes(['class' => 'p-6 bg-slate-50 border rounded-2xl']),
                    ])->columns(3),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupportTickets::route('/'),
            // صفحة view ستستخدم الـ Infolist الذي برمجناه بالأعلى
            'view' => Pages\ViewSupportTicket::route('/{record}'),
        ];
    }

    // السماح بكل الصلاحيات للادمن في Shield
    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }
}
