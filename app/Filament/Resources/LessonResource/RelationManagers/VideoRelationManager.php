<?php

namespace App\Filament\Resources\LessonResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;

class VideoRelationManager extends RelationManager
{
    protected static string $relationship = 'video'; // اسم العلاقة في موديل Lesson

    protected static ?string $title = 'الفيديو التعليمي المرتبط';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('عنوان الفيديو')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('video_url')
                            ->label('رابط الفيديو (YouTube/Drive)')
                            ->url()
                            ->required(),

                        Select::make('difficulty')
                            ->label('مستوى الصعوبة')
                            ->options([
                                'beginner' => 'مبتدئ',
                                'intermediate' => 'متوسط',
                                'advanced' => 'متقدم',
                            ])
                            ->required(),

                        TextInput::make('skill')
                            ->label('المهارة المستهدفة')
                            ->placeholder('مثلاً: مهارة الاستلاف الرقمي')
                            ->required(),

                        // إخفاء الـ Teacher ID وتعبئته من المدرس الحالي
                        Forms\Components\Hidden::make('teacher_id')
                            ->default(auth()->id()),
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->icon('heroicon-m-play-circle'),

                Tables\Columns\BadgeColumn::make('difficulty')
                    ->label('الصعوبة')
                    ->colors([
                        'success' => 'beginner',
                        'warning' => 'intermediate',
                        'danger' => 'advanced',
                    ]),

                Tables\Columns\TextColumn::make('video_url')
                    ->label('الرابط')
                    ->copyable() // ميزة نسخ الرابط بضغطة زر
                    ->limit(30),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('إضافة فيديو للدرس')
                    // التأكد من عدم إضافة أكثر من فيديو لدرس واحد لو كانت العلاقة 1-to-1
                    ->visible(fn(RelationManager $livewire): bool => $livewire->getOwnerRecord()->video === null),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
