<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Define role constants for better code maintainability
    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER = 'user';

    protected $fillable = [
        'name',
        'full_name',
        'email',
        'image',
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
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => 'string',
        'age' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $appends = ['image_url', 'formatted_created_at'];

    /**
     * Relationships
     */
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class)->latest();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Accessors
     */
    public function getImageUrlAttribute()
    {
        return $this->image 
            ? asset('storage/'.$this->image)
            : asset('images/default-profile.png');
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('M d, Y h:i A');
    }

    /**
     * Role Check Methods
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    /**
     * Security Methods
     */
    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Scope Methods
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', self::ROLE_ADMIN);
    }

    public function scopeRegularUsers($query)
    {
        return $query->where('role', self::ROLE_USER);
    }

    /**
     * Business Logic Methods
     */
    public function canPlaceOrder(): bool
    {
        // Example business rule - maybe users need verified email to order
        return $this->hasVerifiedEmail() && $this->isUser();
    }

    public function getInitialsAttribute(): string
    {
        $names = explode(' ', $this->full_name ?? $this->name);
        $initials = '';
        
        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }

        return substr($initials, 0, 2);
    }
}
