<?php

namespace App\Filament\Parent\Resources;

use App\Filament\Parent\Resources\ChildAIAnalyticsResource\Pages;
use App\Models\BehavioralAnalysis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions; // أضف هذا المسار

class ChildAIAnalyticsResource extends Resource implements HasShieldPermissions // تنفيذ الواجهة لـ Shield
{
    protected static ?string $model = BehavioralAnalysis::class;

    // 1. تفعيل الصلاحيات المنفصلة لـ Shield
    protected static ?string $permissionPrefix = 'child_ai_analytics';

    // 2. تفعيل البحث العالمي (البحث برقم السجل أو تفاصيل مخفية)
    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationGroup = 'متابعة أداء الأبناء';
    protected static ?string $label = 'تحليل انتباه';
    protected static ?string $pluralLabel = 'تقارير ذكاء بصيرة';

    /**
     * جعل البحث العالمي يظهر اسم الابن بدلاً من الـ ID
     */
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return "تحليل ذكاء: " . $record->user->name;
    }

    /**
     * إظهار تفاصيل إضافية في نتائج البحث (اسم المادة/الدرس)
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'الدرس' => $record->video->title,
            'التوقيت' => $record->created_at->format('Y-m-d'),
        ];
    }

    /**
     * حماية أمنية: الأب يرى فقط بيانات أبنائه
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
                Section::make('خلاصة ذكاء بصيرة (AI Insight)')
                    ->description('توضيح حالة الطفل التربوية والنفسية أثناء الدرس كما رصدتها خوارزميات المنصة.')
                    ->icon('heroicon-m-academic-cap')
                    ->schema([
                        Forms\Components\Placeholder::make('student_name')
                            ->label('الطالب')
                            ->content(fn($record): string => $record->user->name),

                        Forms\Components\Placeholder::make('lesson_title')
                            ->label('الدرس المشاهَد')
                            ->content(fn($record): string => $record->video->title),

                        TextInput::make('focus_level')
                            ->label('مستوى التركيز الحالي')
                            ->suffix('%')
                            ->readOnly(),

                        TextInput::make('detected_learning_style')
                            ->label('النمط المفضل المستنتج')
                            ->readOnly()
                            ->placeholder('قيد التحليل الآن'),

                        Textarea::make('ai_summary')
                            ->label('ملاحظة تربوية للمنصة')
                            ->default('يقوم محرك بصيرة برصد علامات الحيرة وتعديل المحتوى لطفلك لضمان أقصى استفادة.')
                            ->readOnly()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('الابن / الابنة')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-m-user-circle')
                    ->iconColor('indigo'),

                Tables\Columns\TextColumn::make('video.title')
                    ->label('الدرس المُحلل')
                    ->limit(30)
                    ->color('gray'),

                Tables\Columns\TextColumn::make('focus_level')
                    ->label('نسبة الانتباه')
                    ->badge()
                    ->color(fn(string $state): string => match (true) {
                        (int)$state >= 80 => 'success',
                        (int)$state >= 50 => 'warning',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn($state) => "%$state"),

                Tables\Columns\TextColumn::make('confusion_level')
                    ->label('حيرة الطفل')
                    ->badge()
                    ->color(fn(string $state): string => (int)$state > 40 ? 'danger' : 'gray')
                    ->formatStateUsing(fn(string $state): string => (int)$state > 40 ? 'ملحوظة' : 'طبيعية'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ التحليل')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->since()
                    ->color('gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('اختيار الابن')
                    ->relationship('user', 'name', fn(Builder $query) => $query->where('parent_id', auth()->id())),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض التفاصيل'),
            ])
            ->bulkActions([])
            ->emptyStateHeading('لا توجد تقارير ذكاء اصطناعي حالياً لأبنائكم');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChildAIAnalytics::route('/'),
            'view' => Pages\ViewChildAIAnalytics::route('/{record}'),
        ];
    }

    // ربط هذا المورد بسياسة Shield البرمجية
    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
        ];
    }
}
