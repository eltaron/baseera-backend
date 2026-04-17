<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportCard extends Model
{
    protected $fillable = [
        'title',
        'description',
        'icon',
        'button_text',
        'button_url',
        'border_color',
        'sort_order',
        'is_active'
    ];
}
