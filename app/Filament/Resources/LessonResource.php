<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LessonResource\Pages;
use App\Filament\Resources\LessonResource\RelationManagers\VideoRelationManager;
use App\Filament\Widgets\LessonStats;
use App\Models\Lesson;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;
    protected static ?string $recordTitleAttribute = 'title';
    // تغيير الأيقونة والمجموعة
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'المحتوى التعليمي';
    protected static ?string $label = 'درس';
    protected static ?string $pluralLabel = 'الدروس';
    // أضف هذه الدالة داخل LessonResource.php
    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'الوحدة' => $record->unit->title,
            'المادة' => $record->unit->subject->name,
        ];
    }
    // 1. التحكم في استعلام البيانات بناءً على الرتبة (Admin vs Teacher)
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // إذا كان المستخدم معلم، أظهر له دروسه فقط
        if (auth()->user()->hasRole('Teacher')) {
            return $query->where('teacher_id', auth()->id());
        }

        // إذا كان أدمن يرى كل شيء
        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('معلومات الدرس الأساسية')
                    ->description('قم بتحديد الوحدة والعنوان لهذا الدرس')
                    ->icon('heroicon-m-information-circle')
                    ->columns(2)
                    ->schema([
                        // اختيار الوحدة من قائمة منسدلة بدلاً من كتابة رقم
                        Select::make('unit_id')
                            ->label('الوحدة الدراسية')
                            ->relationship('unit', 'title') // ربط العلاقة
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('title')
                            ->label('عنوان الدرس')
                            ->placeholder('مثلاً: مقدمة في الضرب')
                            ->required()
                            ->maxLength(255),

                        // إخفاء حقل المعلم وتعبئته تلقائياً للمعلم، وإظهاره للأدمن للاختيار
                        Select::make('teacher_id')
                            ->label('المعلم المسؤول')
                            ->relationship('teacher', 'name')
                            ->default(auth()->id())
                            ->disabled(!auth()->user()->hasRole('panel_user')) // تعديل حسب اسم رتبة الأدمن لديك
                            ->dehydrated() // لضمان إرسال القيمة للداتابيز حتى لو الحقل disabled
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان الدرس')
                    ->searchable() // تفعيل البحث
                    ->sortable()
                    ->description(fn(Lesson $record): string => "الوحدة: {$record->unit->title}"),

                Tables\Columns\TextColumn::make('unit.subject.name')
                    ->label('المادة')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('المعلم')
                    ->hidden(auth()->user()->hasRole('Teacher')), // إخفاء العمود إذا كان المعلم هو من يشاهد

                Tables\Columns\TextColumn::make('video.title')
                    ->label('الفيديو المرتبط')
                    ->placeholder('لا يوجد فيديو حالياً')
                    ->icon('heroicon-m-play-circle'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // فلترة مميزة حسب الوحدة
                SelectFilter::make('unit')
                    ->label('تصفية حسب الوحدة')
                    ->relationship('unit', 'title')
                    ->searchable(),

                // فلترة حسب المادة (عبر علاقة متداخلة)
                SelectFilter::make('subject')
                    ->label('تصفية حسب المادة')
                    ->relationship('unit.subject', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(), // إضافة زر عرض
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->hidden(!auth()->user()->hasRole(['super_admin', 'Teacher'])), // قيود إضافية للحذف
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('لا توجد دروس حالياً')
            ->emptyStateDescription('ابدأ بإضافة أول درس لمنهجك الدراسي الآن.');
    }
    public static function getWidgets(): array
    {
        return [
            LessonStats::class,
        ];
    }
    public static function getRelations(): array
    {
        return [
            VideoRelationManager::class, // إضافة الكلاس الجديد هنا
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
        ];
    }
}
