<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'message',
        'status', // unread, read, replied
    ];

    // لتحديد الحالة الافتراضية عند الإنشاء
    protected $attributes = [
        'status' => 'unread',
    ];
}
