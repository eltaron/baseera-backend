<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    // الحقول القابلة للتعبئة
    protected $fillable = [
        'question',
        'answer',
        'sort_order',
        'is_active',
    ];

    // التحويلات التلقائية (Casting)
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
