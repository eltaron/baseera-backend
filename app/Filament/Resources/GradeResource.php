<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GradeResource\Pages;
use App\Filament\Resources\GradeResource\RelationManagers\UnitsRelationManager;
use App\Filament\Widgets\GradeStats;
use App\Models\Grade;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;

class GradeResource extends Resource
{
    protected static ?string $model = Grade::class;
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationGroup = 'الهيكل التعليمي';
    protected static ?string $label = 'صف دراسي';
    protected static ?string $pluralLabel = 'الصفوف الدراسية';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('المعلومات الأساسية')
                    ->description('تسمية المرحلة الدراسية بشكل دقيق.')
                    ->schema([
                        TextInput::make('name')
                            ->label('اسم الصف')
                            ->placeholder('مثلاً: الصف الأول الابتدائي')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('المرحلة الدراسية')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->size('lg'),

                // حساب عدد الوحدات في كل صف
                Tables\Columns\TextColumn::make('units_count')
                    ->label('إجمالي الوحدات')
                    ->counts('units')
                    ->badge()
                    ->color('info'),

                // حساب عدد الطلاب المنتمين لهذا الصف
                Tables\Columns\TextColumn::make('students_count')
                    ->label('عدد الطلاب')
                    ->getStateUsing(fn($record) => \App\Models\User::where('grade_level', $record->id)->count())
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-m-users'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('Y-m-d')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(!auth()->user()->hasRole('super_admin')), // تعديل الصفوف متاح فقط للأدمن الخارق
                Tables\Actions\DeleteAction::make()
                    ->hidden(!auth()->user()->hasRole('super_admin')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])->hidden(!auth()->user()->hasRole('super_admin')),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            UnitsRelationManager::class,
        ];
    }
    public static function getWidgets(): array
    {
        return [
            GradeStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGrades::route('/'),
            'create' => Pages\CreateGrade::route('/create'),
            'edit' => Pages\EditGrade::route('/{record}/edit'),
        ];
    }
}
