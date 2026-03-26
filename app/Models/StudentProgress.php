<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProgress extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'subject_id', 'completed_lessons_count', 'overall_score', 'completion_percentage'];
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
