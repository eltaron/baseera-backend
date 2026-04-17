<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubjectResource\Pages;
use App\Filament\Resources\SubjectResource\RelationManagers;
use App\Filament\Widgets\SubjectStats;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;

class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'الهيكل التعليمي';
    protected static ?string $label = 'مادة دراسية';
    protected static ?string $pluralLabel = 'المواد الدراسية';

    // 1. صلاحيات الوصول: المعلم يرى مواده فقط
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
                Section::make('هوية المادة')
                    ->description('أدخل مسمى المادة الدراسي المرتبط بالمنهج.')
                    ->schema([
                        TextInput::make('name')
                            ->label('اسم المادة')
                            ->placeholder('مثلاً: اللغة العربية، الرياضيات...')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\Hidden::make('teacher_id')
                            ->default(auth()->id()),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('المادة الدراسية')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->size('lg'),

                Tables\Columns\TextColumn::make('units_count')
                    ->label('عدد الوحدات')
                    ->counts('units') // يحسب عدد الوحدات تلقائياً من العلاقة
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('المعلم المسؤول')
                    ->icon('heroicon-m-user')
                    ->hidden(auth()->user()->hasRole('Teacher')), // إخفاء العمود عن المعلمين

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('Y-m-d')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('لا توجد مواد مسجلة حالياً')
            ->emptyStateIcon('heroicon-o-book-open');
    }

    public static function getRelations(): array
    {
        return [
            // سنضيف هنا لاحقاً الوحدات والمهارات المرتبطة بالمادة
            RelationManagers\UnitsRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            SubjectStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubjects::route('/'),
            'create' => Pages\CreateSubject::route('/create'),
            'edit' => Pages\EditSubject::route('/{record}/edit'),
        ];
    }
}
