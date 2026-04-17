<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $fillable = ['lesson_id', 'title', 'video_url', 'skill', 'difficulty', 'duration_seconds', 'teacher_id'];
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
