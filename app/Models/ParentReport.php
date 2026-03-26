<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentReport extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'summary_text', 'status_color', 'report_date'];
}
