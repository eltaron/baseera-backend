<?php

namespace App\Filament\Resources\SubjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;

class UnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'units'; // العلاقة المحددة في موديل Subject

    protected static ?string $title = 'الوحدات الدراسية المضافة';

    protected static ?string $modelLabel = 'وحدة';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('تفاصيل الوحدة')
                    ->description('حدد عنوان الوحدة والصف الدراسي المستهدف.')
                    ->schema([
                        TextInput::make('title')
                            ->label('عنوان الوحدة')
                            ->placeholder('مثلاً: الوحدة الأولى: مفاهيم الطرح')
                            ->required()
                            ->maxLength(255),

                        Select::make('grade_id')
                            ->label('الصف الدراسي')
                            ->relationship('grade', 'name') // ربط مع جدول الصفوف
                            ->searchable()
                            ->preload()
                            ->required(),

                        // إخفاء الـ Teacher ID وتعبئته تلقائياً من المدرس الحالي
                        Forms\Components\Hidden::make('teacher_id')
                            ->default(auth()->id()),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان الوحدة')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('grade.name')
                    ->label('الصف')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('lessons_count')
                    ->label('عدد الدروس')
                    ->counts('lessons') // يحسب عدد الدروس داخل الوحدة تلقائياً
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('Y-m-d')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('grade')
                    ->label('فلترة بالصف')
                    ->relationship('grade', 'name'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('إضافة وحدة جديدة')
                    ->icon('heroicon-m-plus'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('لا توجد وحدات مضافة لهذه المادة بعد');
    }
}
