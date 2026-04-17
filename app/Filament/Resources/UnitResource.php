<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitResource\Pages;
use App\Filament\Resources\UnitResource\RelationManagers;
use App\Filament\Widgets\UnitStats;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $navigationGroup = 'الهيكل التعليمي';
    protected static ?string $label = 'وحدة دراسية';
    protected static ?string $pluralLabel = 'الوحدات الدراسية';

    // 1. صلاحيات المعلم: يشاهد وحداته فقط
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
                Section::make('تصنيف الوحدة')
                    ->description('حدد المادة والصف الدراسي الذي تتبعه هذه الوحدة.')
                    ->columns(2)
                    ->schema([
                        Select::make('subject_id')
                            ->label('المادة الدراسية')
                            ->relationship('subject', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('grade_id')
                            ->label('الصف الدراسي')
                            ->relationship('grade', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('title')
                            ->label('عنوان الوحدة')
                            ->placeholder('مثلاً: الوحدة الأولى: الجمع والطرح')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Hidden::make('teacher_id')
                            ->default(auth()->id()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان الوحدة')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('subject.name')
                    ->label('المادة')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('grade.name')
                    ->label('الصف')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('lessons_count')
                    ->label('عدد الدروس')
                    ->counts('lessons')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('المعلم')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->hidden(auth()->user()->hasRole('Teacher')),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('Y-m-d')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('subject')
                    ->label('تصفية بالمادة')
                    ->relationship('subject', 'name'),

                Tables\Filters\SelectFilter::make('grade')
                    ->label('تصفية بالصف')
                    ->relationship('grade', 'name'),
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
            ->emptyStateHeading('لا توجد وحدات تعليمية');
    }

    public static function getRelations(): array
    {
        return [
            // سنضيف هنا Relation Manager للدروس لكي يتم إضافتها من داخل الوحدة
            RelationManagers\LessonsRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            UnitStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUnits::route('/'),
            'create' => Pages\CreateUnit::route('/create'),
            'edit' => Pages\EditUnit::route('/{record}/edit'),
        ];
    }
}
