<?php

namespace App\Models;

// استيراد المكتبات اللازمة للفيلمنت
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser // تنفيذ الواجهة هنا
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * الحقول القابلة للتعبئة
     */
    protected $fillable = ['name', 'email', 'password', 'role', 'parent_id', 'grade_level'];

    /**
     * الحقول المخفية
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * تحويل أنواع البيانات
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * ==========================================================
     * الجزء المسؤول عن حماية وصلاحيات دخول لوحات التحكم (Panels)
     * ==========================================================
     */
    public function canAccessPanel(Panel $panel): bool
    {

        // 1. صلاحيات دخول لوحة الآدمن (المسار /admin)
        if ($panel->getId() === 'admin') {
            // يسمح فقط للأدمن والمعلم بالدخول
            return in_array($this->role, ['admin', 'teacher']);
        }

        // 2. صلاحيات دخول لوحة ولي الأمر (المسار /parent)
        if ($panel->getId() === 'parent') {
            // يسمح فقط للمستخدم الذي يحمل رتبة parent بالدخول
            return in_array($this->role, ['admin', 'parent']);
        }

        return false;
    }


    /**
     * ============================
     * العلاقات البرمجية (Relations)
     * ============================
     */

    // علاقة ولي الأمر بالأبناء
    public function students()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    // علاقة الابن بولي أمره
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // علاقة الطالب بملفه التعليمي (الـ AI Profile)
    public function learningProfile()
    {
        return $this->hasOne(LearningProfile::class);
    }

    // علاقة الطالب بسجل تقدمه في المناهج
    public function progress()
    {
        return $this->hasMany(StudentProgress::class);
    }

    // علاقة الطالب (أو المعلم) بالتفاعلات مع الفيديوهات
    public function interactions()
    {
        return $this->hasMany(VideoInteraction::class);
    }
}
