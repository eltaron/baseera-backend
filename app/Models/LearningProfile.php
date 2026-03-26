<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningProfile extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'current_level', 'strengths', 'weaknesses', 'preferred_learning_style'];
    protected $casts = ['strengths' => 'array', 'weaknesses' => 'array'];
}
