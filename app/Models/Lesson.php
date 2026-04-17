<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    protected $fillable = ['unit_id', 'title', 'teacher_id'];
    public function video()
    {
        return $this->hasOne(Video::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
