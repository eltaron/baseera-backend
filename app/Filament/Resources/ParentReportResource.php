<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParentReportResource\Pages;
use App\Filament\Widgets\ReportStats;
use App\Models\ParentReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class ParentReportResource extends Resource
{
    protected static ?string $model = ParentReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'تقارير وتحليلات الـ AI';
    protected static ?string $label = 'تقرير لولي الأمر';
    protected static ?string $pluralLabel = 'تقارير أولياء الأمور';

    // 1. المعلم يرى فقط التقارير التي أرسلها هو لطلابه
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
                Section::make('إنشاء تقرير تربوي')
                    ->description('اكتب ملخصاً بسيطاً للأب والأم حول حالة طفلهم اليوم.')
                    ->schema([
                        Select::make('user_id')
                            ->label('الطالب المستهدف')
                            ->relationship('user', 'name', fn(Builder $query) => $query->where('role', 'student'))
                            ->searchable()
                            ->required(),

                        Select::make('status_color')
                            ->label('الحالة العامة (الإشارة)')
                            ->options([
                                'green' => 'مستقر / ممتاز (أخضر)',
                                'yellow' => 'يحتاج انتباه (أصفر)',
                                'red' => 'يواجه صعوبة كبيرة (أحمر)',
                            ])
                            ->required()
                            ->native(false),

                        DatePicker::make('report_date')
                            ->label('تاريخ التقرير')
                            ->default(now())
                            ->required(),

                        Textarea::make('summary_text')
                            ->label('الملخص التربوي (بلغة غير تقنية)')
                            ->placeholder('مثلاً: أحمد أبلى بلاءً حسناً اليوم في الجمع، ولكنه يحتاج لمراجعة بسيطة على الطرح بالاستلاف.')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),

                        Forms\Components\Hidden::make('teacher_id')
                            ->default(auth()->id()),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('اسم الطالب')
                    ->searchable()
                    ->sortable()
                    ->description(fn($record) => "ولي الأمر: " . ($record->user->parent->name ?? 'غير محدد')),

                Tables\Columns\TextColumn::make('status_color')
                    ->label('التقييم البصري')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'green' => 'ممتاز',
                        'yellow' => 'متوسط',
                        'red' => 'ضعيف/قلق',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'green' => 'success',
                        'yellow' => 'warning',
                        'red' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('summary_text')
                    ->label('خلاصة التقرير')
                    ->limit(50)
                    ->tooltip(fn($record) => $record->summary_text),

                Tables\Columns\TextColumn::make('report_date')
                    ->label('بتاريخ')
                    ->date('Y-m-d')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('وقت الإرسال')
                    ->dateTime('H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_color')
                    ->label('تصفية بالحالة')
                    ->options([
                        'green' => 'الأخضر (الممتاز)',
                        'yellow' => 'الأصفر (المتوسط)',
                        'red' => 'الأحمر (المتعثر)',
                    ]),
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
            ->defaultSort('report_date', 'desc');
    }
    public static function getWidgets(): array
    {
        return [
            ReportStats::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListParentReports::route('/'),
            'create' => Pages\CreateParentReport::route('/create'),
            'edit' => Pages\EditParentReport::route('/{record}/edit'),
        ];
    }
}
