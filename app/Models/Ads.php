<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    use HasFactory;
    protected $fillable = [
        'fullname',
        'image',
        'url',
        'hit',
        'start_date',
        'end_date',
        'location',
        'email',
        'phone',
        'is_public', // active, expired, etc.
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'location' => 'array',
        'images' => 'array',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    }
