<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $fillable = ['subject_id', 'grade_id', 'title'];
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
