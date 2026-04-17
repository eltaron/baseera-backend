<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BehavioralAnalysis extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'video_id', 'focus_level', 'confusion_level', 'boredom_level', 'detected_learning_style'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
