<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BehavioralAnalysisResource\Pages;
use App\Filament\Resources\BehavioralAnalysisResource\RelationManagers;
use App\Models\BehavioralAnalysis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BehavioralAnalysisResource extends Resource
{
    protected static ?string $model = BehavioralAnalysis::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('video_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('focus_level')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('confusion_level')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('boredom_level')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('detected_learning_style')
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
                Tables\Columns\TextColumn::make('video_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('focus_level')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('confusion_level')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('boredom_level')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('detected_learning_style')
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
            'index' => Pages\ListBehavioralAnalyses::route('/'),
            'create' => Pages\CreateBehavioralAnalysis::route('/create'),
            'edit' => Pages\EditBehavioralAnalysis::route('/{record}/edit'),
        ];
    }
}
