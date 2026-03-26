<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    protected $fillable = ['unit_id', 'title'];
    public function video()
    {
        return $this->hasOne(Video::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
