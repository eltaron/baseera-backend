<?php

namespace App\Filament\Parent\Resources;

use App\Filament\Parent\Resources\ChildProgressResource\Pages;
use App\Models\StudentProgress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions; // أضف هذا المسار

class ChildProgressResource extends Resource implements HasShieldPermissions // تنفيذ الواجهة لـ Shield
{
    protected static ?string $model = StudentProgress::class;

    // 1. تفعيل الصلاحيات المنفصلة لـ Shield
    protected static ?string $permissionPrefix = 'child_progress';

    // 2. تفعيل البحث العالمي (البحث برقم السجل أو التفاصيل)
    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static ?string $navigationGroup = 'متابعة أداء الأبناء';
    protected static ?string $label = 'نسبة إنجاز';
    protected static ?string $pluralLabel = 'سجل تقدم الأبناء';

    /**
     * جعل البحث العالمي يظهر اسم الطالب واسم المادة
     */
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return "إنجاز المادة: " . $record->subject->name . " (لطالب: " . $record->user->name . ")";
    }

    /**
     * إظهار نسبة المئوية للتقدم في نتائج البحث السريعة
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'النسبة المكتملة' => "%" . $record->completion_percentage,
            'آخر نشاط' => $record->updated_at->diffForHumans(),
        ];
    }

    /**
     * الفلترة الأمنية: عرض تقدم الأبناء المنتمين لولي الأمر الحالي فقط
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('user', function (Builder $query) {
                $query->where('parent_id', auth()->id());
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('بطاقة إنجاز المادة')
                    ->description('توضح هذه البطاقة المستوى العام والمجهود المبذول من قبل الطفل في مادة محددة.')
                    ->icon('heroicon-m-clipboard-document-check')
                    ->schema([
                        Placeholder::make('child_name')
                            ->label('الطالب')
                            ->content(fn($record): string => $record->user->name),

                        Placeholder::make('subject_name')
                            ->label('المادة العلمية')
                            ->content(fn($record): string => $record->subject->name),

                        Placeholder::make('lessons_count')
                            ->label('عدد الدروس المكتملة')
                            ->content(fn($record): string => $record->completed_lessons_count . " درس"),

                        Placeholder::make('final_score')
                            ->label('الدرجة الإجمالية للفهم')
                            ->content(fn($record): string => $record->overall_score . " نقطة"),

                        Placeholder::make('perc')
                            ->label('إجمالي إتمام المنهج')
                            ->content(fn($record): string => "%" . $record->completion_percentage),

                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('اسم الطفل')
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->icon('heroicon-m-user')
                    ->iconColor('indigo'),

                Tables\Columns\TextColumn::make('subject.name')
                    ->label('المادة')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('completed_lessons_count')
                    ->label('دروس مكتملة')
                    ->icon('heroicon-m-check-badge')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('completion_percentage')
                    ->label('مدى الإنجاز')
                    ->formatStateUsing(fn($state) => "%$state")
                    ->badge()
                    ->color(fn(string $state): string => match (true) {
                        (int)$state >= 80 => 'success',
                        (int)$state >= 40 => 'info',
                        default => 'danger',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('overall_score')
                    ->label('جودة الفهم')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخر نشاط')
                    ->dateTime('Y-m-d')
                    ->since()
                    ->color('gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('حسب الطفل')
                    ->relationship('user', 'name', fn(Builder $query) => $query->where('parent_id', auth()->id())),

                Tables\Filters\TernaryFilter::make('is_completed')
                    ->label('هل تم إنهاء المادة؟')
                    ->queries(
                        true: fn(Builder $query) => $query->where('completion_percentage', '>=', 95),
                        false: fn(Builder $query) => $query->where('completion_percentage', '<', 95),
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('رؤية التفاصيل'),
            ])
            ->bulkActions([])
            ->emptyStateHeading('لم يبدأ أي من أبنائكم الدراسة في مادة معينة حتى الآن')
            ->defaultSort('completion_percentage', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChildProgress::route('/'),
            'view' => Pages\ViewChildProgress::route('/{record}'),
        ];
    }

    // السماح فقط بصلاحيات العرض في صفحة الأدوار
    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
        ];
    }
}
