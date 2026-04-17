<?php

namespace App\Filament\Resources\GradeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class UnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'units'; // العلاقة في موديل Grade

    protected static ?string $title = 'توزيع المواد والوحدات في هذا الصف';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('عنوان الوحدة')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('subject_id')
                    ->label('المادة')
                    ->relationship('subject', 'name')
                    ->required(),

                Forms\Components\Select::make('teacher_id')
                    ->label('المعلم المسؤول في هذا الصف')
                    ->relationship('teacher', 'name')
                    ->searchable()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('الوحدة الدراسية')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('subject.name')
                    ->label('المادة')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('المعلم المشرف')
                    ->icon('heroicon-m-user-badge')
                    ->color('success'),

                Tables\Columns\TextColumn::make('lessons_count')
                    ->label('عدد الدروس')
                    ->counts('lessons')
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('subject')
                    ->relationship('subject', 'name'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('إضافة وحدة جديدة لهذا الصف'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
