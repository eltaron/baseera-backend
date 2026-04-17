<?php

namespace App\Filament\Resources\ContactMessageResource\Pages;

use App\Filament\Resources\ContactMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    /**
     * تخصيص ترويسة الصفحة
     */
    public function getTitle(): string
    {
        return "تفاصيل رسالة: " . $this->record->name;
    }

    /**
     * الأزرار الموجودة في أعلى صفحة العرض
     */
    protected function getHeaderActions(): array
    {
        return [
            // زر لحذف الرسالة إذا كانت سبام أو غير مرغوبة
            Actions\DeleteAction::make()
                ->label('حذف هذه الرسالة')
                ->icon('heroicon-o-trash'),

            // زر للرجوع لصندوق الوارد
            Actions\Action::make('back')
                ->label('العودة للبريد')
                ->color('gray')
                ->url(static::$resource::getUrl('index'))
                ->icon('heroicon-m-arrow-right-circle'),
        ];
    }

    /**
     * [لمسة احترافية]
     * تنفيذ كود معين بمجرد فتح الصفحة (مثل جعل الحالة "تمت القراءة")
     */
    protected function afterFill(): void
    {
        // إذا كانت الرسالة "غير مقروءة"، حولها لـ "مقروءة" فوراً
        if ($this->record->status === 'unread') {
            $this->record->update([
                'status' => 'read'
            ]);
        }
    }
}
