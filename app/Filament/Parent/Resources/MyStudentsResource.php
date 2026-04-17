<?php

namespace App\Filament\Parent\Resources;

use App\Filament\Parent\Resources\MyStudentsResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Grid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions; // أضف هذا المسار

class MyStudentsResource extends Resource implements HasShieldPermissions // تنفيذ واجهة Shield
{
    // نستخدم موديل User الأساسي
    protected static ?string $model = User::class;

    // 1. تفعيل الصلاحيات المنفصلة لـ Shield لمنع تداخلها مع لوحة الإدارة
    protected static ?string $permissionPrefix = 'my_students';

    // 2. تفعيل البحث العالمي بذكاء
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationGroup = 'إدارة الأبناء';
    protected static ?string $label = 'ملف ابن';
    protected static ?string $pluralLabel = 'قائمة أبنائي';

    /**
     * تخصيص محتوى نتائج البحث السريعة (⌘ K) لولي الأمر
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'الصف' => match ($record->grade_level) {
                1 => 'الأول الابتدائي',
                2 => 'الثاني الابتدائي',
                3 => 'الثالث الابتدائي',
                4 => 'الرابع الابتدائي',
                5 => 'الخامس الابتدائي',
                6 => 'السادس الابتدائي',
                default => 'غير محدد',
            },
            'النمط' => $record->learningProfile->preferred_learning_style ?? 'بانتظار التحليل',
        ];
    }

    /**
     * فلترة أمنية: لا يظهر لولي الأمر إلا أبناؤه المسجلون تحت حسابه فقط
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('parent_id', auth()->id())
            ->where('role', 'student');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('بطاقة تعريف الطالب')
                    ->description('البيانات الأساسية والمرحلة الدراسية المسجل بها طفلك حالياً.')
                    ->icon('heroicon-m-user')
                    ->schema([
                        Grid::make(3)->schema([
                            Placeholder::make('child_name')
                                ->label('اسم الابن / الابنة')
                                ->content(fn($record): string => $record->name),

                            Placeholder::make('grade')
                                ->label('الصف الدراسي')
                                ->content(fn($record): string => match ($record->grade_level) {
                                    1 => 'الأول الابتدائي',
                                    2 => 'الثاني الابتدائي',
                                    3 => 'الثالث الابتدائي',
                                    4 => 'الرابع الابتدائي',
                                    5 => 'الخامس الابتدائي',
                                    6 => 'السادس الابتدائي',
                                    default => 'غير محدد',
                                }),

                            Placeholder::make('role_badge')
                                ->label('الحالة بالنظام')
                                ->content('طالب نشط في بصيرة'),
                        ]),
                    ]),

                Section::make('الخلاصة الذكية للملف (AI Summary)')
                    ->description('كيف يرى نظام "بصيرة" النمط التعليمي لطفلك بناءً على سلوكه أمام الكاميرا.')
                    ->icon('heroicon-m-sparkles')
                    ->schema([
                        Placeholder::make('learning_style')
                            ->label('نمط التعلم المستنتج')
                            ->content(fn($record) => $record->learningProfile->preferred_learning_style ?? 'تحليل السلوك جارٍ بانتظار مزيد من التفاعل...'),

                        Placeholder::make('level')
                            ->label('المستوى التعليمي المقدر')
                            ->content(fn($record) => $record->learningProfile ? strtoupper($record->learningProfile->current_level) : 'Beginner'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('الابن')
                    ->defaultImageUrl(fn($record) => "https://ui-avatars.com/api/?name=" . urlencode($record->name) . "&background=4f46e5&color=fff")
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->description(fn($record) => $record->email),

                Tables\Columns\TextColumn::make('grade_level')
                    ->label('الصف')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        1 => '1 الابتدائي',
                        2 => '2 الابتدائي',
                        3 => '3 الابتدائي',
                        4 => '4 الابتدائي',
                        5 => '5 الابتدائي',
                        6 => '6 الابتدائي',
                        default => 'N/A'
                    })
                    ->color('info'),

                Tables\Columns\TextColumn::make('learningProfile.preferred_learning_style')
                    ->label('نمط التعلم (AI)')
                    ->badge()
                    ->placeholder('جاري التحليل...')
                    ->color('indigo'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('منذ')
                    ->since()
                    ->color('gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('grade_level')
                    ->label('الصفوف الدراسية')
                    ->options([
                        1 => 'الأول',
                        2 => 'الثاني',
                        3 => 'الثالث',
                        4 => 'الرابع',
                        5 => 'الخامس',
                        6 => 'السادس'
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('رؤية الملف الكامل'),
            ])
            ->bulkActions([])
            ->emptyStateHeading('لم يتم العثور على أبناء مسجلين');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMyStudents::route('/'),
            'view' => Pages\ViewMyStudents::route('/{record}'),
        ];
    }

    // السماح فقط بصلاحيات المشاهدة داخل لوحة الأدوار (Roles)
    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
        ];
    }
}
