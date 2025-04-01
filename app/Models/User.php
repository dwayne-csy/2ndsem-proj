<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'full_name',
        'email',
        'image',  // Changed from profile_image to image
        'password',
        'role',
        'age',
        'sex',
        'contact_number',
        'address'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => 'string',
        'age' => 'integer',
    ];

    protected $appends = ['image_url'];  // Changed from profile_image_url to image_url

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image 
            ? asset('storage/'.$this->image)
            : asset('images/default-profile.png');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}