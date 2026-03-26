<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'video_id', 'reason', 'is_viewed'];
    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
