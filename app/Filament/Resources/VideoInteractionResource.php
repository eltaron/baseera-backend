<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoInteractionResource\Pages;
use App\Filament\Resources\VideoInteractionResource\RelationManagers;
use App\Models\VideoInteraction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VideoInteractionResource extends Resource
{
    protected static ?string $model = VideoInteraction::class;

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
                Forms\Components\TextInput::make('watch_time_seconds')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('replay_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('pause_frequency')
                    ->required()
                    ->numeric()
                    ->default(0),
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
                Tables\Columns\TextColumn::make('watch_time_seconds')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('replay_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pause_frequency')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListVideoInteractions::route('/'),
            'create' => Pages\CreateVideoInteraction::route('/create'),
            'edit' => Pages\EditVideoInteraction::route('/{record}/edit'),
        ];
    }
}
