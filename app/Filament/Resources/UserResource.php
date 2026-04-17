<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Widgets\UserStats;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'إدارة الوصول';
    protected static ?string $label = 'مستخدم';
    protected static ?string $pluralLabel = 'جميع المستخدمين';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('معلومات الحساب الأساسية')
                    ->description('أدخل البيانات الشخصية وبيانات الدخول للسيستم.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('الاسم الكامل')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        TextInput::make('password')
                            ->label('كلمة المرور')
                            ->password()
                            ->dehydrated(fn($state) => filled($state)) // لا ترسل القيمة للداتابيز إذا كانت فارغة عند التعديل
                            ->required(fn(string $context): bool => $context === 'create') // مطلوبة فقط عند الإنشاء
                            ->revealable(), // ميزة إظهار الباسورد بعين

                        Select::make('role')
                            ->label('نوع الحساب / الرتبة')
                            ->options([
                                'admin' => 'مدير النظام (Admin)',
                                'teacher' => 'معلم / مشرف أكاديمي',
                                'parent' => 'ولي أمر',
                                'student' => 'طالب / طفل',
                            ])
                            ->required()
                            ->reactive() // لجعل الحقول الأخرى تتفاعل معه لحظياً
                            ->native(false),
                    ]),

                Section::make('البيانات التعليمية (خاصة بالطلاب)')
                    ->description('تعبئة هذه البيانات ضروري فقط في حال اختيار رتبة "طالب".')
                    // تظهر فقط إذا كانت الرتبة المختارة هي طالب
                    ->hidden(fn(Forms\Get $get) => $get('role') !== 'student')
                    ->columns(2)
                    ->schema([
                        Select::make('parent_id')
                            ->label('ولي الأمر المرتبط')
                            ->relationship('parent', 'name', fn(Builder $query) => $query->where('role', 'parent'))
                            ->searchable()
                            ->preload(),

                        Select::make('grade_level')
                            ->label('الصف الدراسي الحالي')
                            ->options([
                                1 => 'الأول الابتدائي',
                                2 => 'الثاني الابتدائي',
                                3 => 'الثالث الابتدائي',
                                4 => 'الرابع الابتدائي',
                                5 => 'الخامس الابتدائي',
                                6 => 'السادس الابتدائي',
                            ])
                            ->native(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable()
                    ->description(fn(User $record): string => $record->email),

                Tables\Columns\TextColumn::make('role')
                    ->label('الرتبة')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'danger',
                        'teacher' => 'success',
                        'parent' => 'warning',
                        'student' => 'info',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'admin' => 'مدير',
                        'teacher' => 'معلم',
                        'parent' => 'ولي أمر',
                        'student' => 'طالب',
                    }),

                Tables\Columns\TextColumn::make('grade_level')
                    ->label('الصف')
                    ->placeholder('N/A')
                    ->formatStateUsing(fn($state) => $state ? "الصف $state" : '')
                    ->badge()
                    ->color('gray')
                    ->alignCenter(),

                // إظهار عدد الطلاب تحت ولي الأمر
                Tables\Columns\TextColumn::make('students_count')
                    ->label('عدد الأبناء')
                    ->counts('students')
                    ->visible(fn($record) => $record?->role === 'parent')
                    ->badge(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ التسجيل')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('نوع المستخدم')
                    ->options([
                        'admin' => 'مديرين',
                        'teacher' => 'معلمين',
                        'parent' => 'أولياء أمور',
                        'student' => 'طلاب',
                    ]),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getWidgets(): array
    {
        return [
            UserStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
