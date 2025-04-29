<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'note',
        'id_car',
        'is_public'
    ];

    // علاقة مع جدول السيارات إذا كانت الشكوى متعلقة بسيارة
    public function car()
    {
        return $this->belongsTo(Car::class, 'id_car');
    }

    // علاقة مع المستخدم إذا كان قد سجل الدخول
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
