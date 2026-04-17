<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $fillable = ['subject_id', 'grade_id', 'title', 'teacher_id'];
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
}
