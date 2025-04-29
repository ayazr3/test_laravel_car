<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'brand',
        'model',
        'year',
        'price',
        'currency',
        'description',
        'color',
        'images',
        'location',
        'is_featured', // true/false - للإعلانات المميزة
        'featured_status', // 'pending', 'approved', 'rejected'
        'rejection_reason',

    ];

    protected $casts = [
        'images' => 'array',
        'location' => 'array',
        'is_featured' => 'boolean',
    ];
    public function getImagesAttribute($value) {
        return json_decode($value,true)??[];
    }

    public function addImage($path) {
        $images =$this -> images;
        $images [] = $path;
        $this -> images = $images;
        $this -> save;
    }

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function ad()
    {
        return $this->hasOne(Ads::class);
    }

}
