<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['video_id', 'question_text', 'options', 'correct_answer', 'teacher_id'];
    protected $casts = [
        'options' => 'array',
    ];
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
