<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LearningProfileResource\Pages;
use App\Filament\Resources\LearningProfileResource\RelationManagers;
use App\Models\LearningProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LearningProfileResource extends Resource
{
    protected static ?string $model = LearningProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('current_level')
                    ->required()
                    ->maxLength(255)
                    ->default('beginner'),
                Forms\Components\Textarea::make('strengths')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('weaknesses')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('preferred_learning_style')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_level')
                    ->searchable(),
                Tables\Columns\TextColumn::make('preferred_learning_style')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLearningProfiles::route('/'),
            'create' => Pages\CreateLearningProfile::route('/create'),
            'edit' => Pages\EditLearningProfile::route('/{record}/edit'),
        ];
    }
}
