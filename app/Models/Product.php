<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $primaryKey = 'product_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'product_name',
        'size',
        'category',
        'types',
        'description',
        'cost_price',
        'sell_price',
        'stock',
        'supplier_id',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class, 'product_id')->where('is_primary', true);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'product_id');
    }

    public function userReview($userId)
    {
        return $this->reviews()->where('user_id', $userId)->first();
    }

    // New methods for review functionality
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getRatingCountAttribute()
    {
        return $this->reviews()->count();
    }

    public function getStarRatingAttribute()
    {
        $avgRating = $this->average_rating;
        $fullStars = floor($avgRating);
        $hasHalfStar = ($avgRating - $fullStars) >= 0.5;

        $stars = str_repeat('★', $fullStars);
        $stars .= $hasHalfStar ? '½' : '';
        $stars .= str_repeat('☆', 5 - ceil($avgRating));

        return $stars;
    }
}