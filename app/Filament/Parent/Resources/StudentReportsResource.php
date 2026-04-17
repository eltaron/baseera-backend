<?php

namespace App\Filament\Parent\Resources;

use App\Filament\Parent\Resources\StudentReportsResource\Pages;
use App\Models\ParentReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Grid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions; // أضف هذا المسار

class StudentReportsResource extends Resource implements HasShieldPermissions // تفعيل حماية Shield
{
    protected static ?string $model = ParentReport::class;

    // 1. تفعيل الصلاحيات المنفصلة لـ Shield
    protected static ?string $permissionPrefix = 'student_reports';

    // 2. تفعيل البحث العالمي بلمسة ذكية (⌘ K)
    protected static ?string $recordTitleAttribute = 'summary_text';

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'التواصل والمتابعة';
    protected static ?string $label = 'تقرير معلم';
    protected static ?string $pluralLabel = 'تقارير المعلمين';

    /**
     * تخصيص عنوان نتائج البحث (البحث عن طريق اسم الطالب بدلاً من النص)
     */
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return "رسالة تربوية تخص: " . $record->user->name;
    }

    /**
     * إظهار معاينة للحالة وتاريخ الرسالة في نتائج البحث السريعة
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'التقييم' => match ($record->status_color) {
                'green' => 'مستقر وممتاز ✅',
                'yellow' => 'يحتاج انتباه ⚠️',
                'red' => 'عاجل وهام 🚨',
                default => 'تقييم عام',
            },
            'بتاريخ' => $record->report_date,
        ];
    }

    /**
     * فلترة أمنية مشددة: ولي الأمر يستلم رسائل "أبنائه فقط"
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
                Section::make('نص الرسالة التربوية')
                    ->description('اقرأ ملاحظات المعلم وتوصياته لتحسين مستوى طفلك.')
                    ->icon('heroicon-m-envelope-open')
                    ->schema([
                        Grid::make(3)->schema([
                            Placeholder::make('child_name')
                                ->label('بخصوص الطالب')
                                ->content(fn($record): string => $record->user->name),

                            Placeholder::make('teacher_name')
                                ->label('المرسل (المعلم المشرف)')
                                ->content(fn($record): string => $record->teacher->name ?? 'إدارة بصيرة آلياً'),

                            Placeholder::make('rep_date')
                                ->label('تاريخ الإرسال')
                                ->content(fn($record): string => $record->report_date),
                        ]),

                        Placeholder::make('summary_text')
                            ->label('الملخص العام للأداء التعليمي')
                            ->content(fn($record): string => $record->summary_text)
                            ->extraAttributes([
                                'class' => 'p-5 bg-indigo-50/30 rounded-2xl border border-indigo-100 text-lg leading-relaxed text-slate-800'
                            ]),

                        // بطاقة شرح دلالة الألوان في تقرير بصيرة
                        Placeholder::make('status_info')
                            ->label('تحليل بصيرة لحالة الطالب')
                            ->content(fn($record) => match ($record->status_color) {
                                'green' => '🟢 الحالة ممتازة: تشير الإحصائيات أن طفلك مستوعب للمادة بشكل كامل.',
                                'yellow' => '🟡 يحتاج انتباه: يوجد بعض التعثرات البسيطة في التحليلات، يفضل المتابعة مع المدرس.',
                                'red' => '🔴 تنبيه تدخل: خوارزميات النظام رصدت "حيرة مستمرة"، يحتاج الطالب لدعم فوري منك.',
                                default => 'تقييم بصري عام'
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('اسم الابن')
                    ->weight('bold')
                    ->sortable()
                    ->searchable()
                    ->icon('heroicon-m-user-circle')
                    ->iconColor('indigo'),

                Tables\Columns\TextColumn::make('status_color')
                    ->label('التقييم المرئي')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'green' => 'مستقر',
                        'yellow' => 'يحتاج دعم',
                        'red' => 'تنبيه تدخّل',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'green' => 'success',
                        'yellow' => 'warning',
                        'red' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('summary_text')
                    ->label('مختصر الرسالة')
                    ->limit(45)
                    ->color('gray')
                    ->tooltip(fn($record) => $record->summary_text),

                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('بواسطة')
                    ->placeholder('تحليل النظام')
                    ->icon('heroicon-m-user-badge'),

                Tables\Columns\TextColumn::make('report_date')
                    ->label('بتاريخ')
                    ->date('Y-m-d')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_color')
                    ->label('تصفية بالحالات العاجلة')
                    ->options([
                        'green' => 'الأخضر (مستقر)',
                        'yellow' => 'الأصفر (متوسط)',
                        'red' => 'الأحمر (تنبيه هام)',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('قراءة الرسالة')
                    ->color('indigo')
                    ->icon('heroicon-o-eye'),
            ])
            ->bulkActions([])
            ->defaultSort('report_date', 'desc')
            ->emptyStateHeading('لم تصل تقارير جديدة من المدرسين');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudentReports::route('/'),
            'view' => Pages\ViewStudentReports::route('/{record}'),
        ];
    }

    // تزويد Shield بقائمة الصلاحيات الأساسية المطلوبة لولي الأمر
    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
        ];
    }
}
