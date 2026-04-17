<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = ['user_id', 'ticket_number', 'name', 'email', 'subject', 'priority', 'message', 'status'];

    // توليد رقم تذكرة عشوائي فريد قبل الحفظ
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->ticket_number = 'BSRA-' . strtoupper(uniqid());
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
