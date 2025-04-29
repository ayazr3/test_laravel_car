<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'images',
        'role',
        'location',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'images' =>'array',
        'location' => 'array'
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

    /**
         * Get all of the comments for the User
         *
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function cars(): HasMany
        {
            return $this->hasMany(Car::class);
        }
        public function ads(): HasMany
        {
            return $this->hasMany(Ads::class);
        }

        protected static function booted()
        {
            static::updated(function ($user) {
                if ($user->isDirty('status') && $user->status === false) {
                    // إذا كان المستخدم هو نفسه الذي تم تحديث حالته
                    if (Auth::check() && Auth::id() === $user->id) {
                        Auth::logout();
                    } else {
                        // إذا كان مستخدم آخر، نقوم بإنهاء جلسته إذا كان مسجل دخول
                        $user->tokens()->delete();
                        \Session::getHandler()->destroy($user->getRememberToken());
                    }
                }
            });
        }

}
