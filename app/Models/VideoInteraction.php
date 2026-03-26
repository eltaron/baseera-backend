<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoInteraction extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'video_id', 'watch_time_seconds', 'replay_count', 'pause_frequency'];
    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
