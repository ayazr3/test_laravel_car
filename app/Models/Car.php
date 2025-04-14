<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
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

    ];

    protected $casts = [
        'images' => 'array',
        'location' => 'array',
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
}
