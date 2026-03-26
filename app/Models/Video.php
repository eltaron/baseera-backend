<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $fillable = ['lesson_id', 'title', 'video_url', 'skill', 'difficulty', 'duration_seconds'];
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
