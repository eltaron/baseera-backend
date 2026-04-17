<?php

namespace App\Filament\Resources\UnitResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;

class LessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'lessons'; // اسم العلاقة في موديل Unit

    protected static ?string $title = 'الدروس التابعة لهذه الوحدة';

    protected static ?string $modelLabel = 'درس';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('إضافة درس جديد')
                    ->description('قم بتسمية الدرس التابع لهذه الوحدة مباشرة.')
                    ->schema([
                        TextInput::make('title')
                            ->label('عنوان الدرس')
                            ->placeholder('مثلاً: طرح عددين ضمن الرقم 100')
                            ->required()
                            ->maxLength(255),

                        // إرسال الـ Teacher ID تلقائياً لضمان ملكية المدرس للدرس
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
                    ->label('عنوان الدرس')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // عمود احترافي يوضح هل الدرس فيه فيديو أم لا
                Tables\Columns\IconColumn::make('video_exists')
                    ->label('الفيديو المرتبط')
                    ->boolean()
                    ->getStateUsing(fn($record) => $record->video()->exists())
                    ->trueIcon('heroicon-o-video-camera')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('Y-m-d')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('إضافة درس للوحدة')
                    ->icon('heroicon-m-plus-circle'),
            ])
            ->actions([
                // رابط سريع لنقل المعلم لصفحة الفيديو التابعة لهذا الدرس
                Tables\Actions\Action::make('go_to_video')
                    ->label('إدارة الفيديو')
                    ->icon('heroicon-m-play-circle')
                    ->color('info')
                    ->url(fn($record) => $record->video ? \App\Filament\Resources\VideoResource::getUrl('edit', ['record' => $record->video->id]) : null)
                    ->visible(fn($record) => $record->video !== null),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('هذه الوحدة لا تحتوي على دروس حالياً')
            ->emptyStateDescription('اضغط على "إضافة درس للوحدة" لبدء بناء المحتوى.');
    }
}
