<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Review extends Model
{
    use HasFactory;

    // Add constants for review status and rating range
    public const MAX_RATING = 5;
    public const MIN_RATING = 1;
    public const EDITABLE_DAYS = 30;
    public const DELETABLE_DAYS = 7;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'rating',
        'comment',
        'is_approved',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $appends = [
        'formatted_date',
        'is_editable',
        'is_deletable',
        'rating_stars', // Added for easy frontend display
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Deleted User',
            'email' => 'deleted@example.com', // Added default email
        ]);
    }

    public function product()
    {
        return $this->belongsTo(
            Product::class, 
            'product_id', 
            'product_id'
        )->withDefault([
            'product_name' => 'Deleted Product', // Changed from 'name' to match your schema
            'image_url' => '/images/default-product.png',
            'sell_price' => 0.00, // Added default price
        ]);
    }

    public function order()
    {
        return $this->belongsTo(Order::class)->withDefault([
            'order_number' => 'N/A',
            'total_amount' => 0.00, // Added default amount
            'status' => 'unknown', // Added default status
        ]);
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('M d, Y \a\t h:i A');
    }

    public function getIsEditableAttribute(): bool
    {
        return $this->created_at->diffInDays(now()) <= self::EDITABLE_DAYS;
    }

    public function getIsDeletableAttribute(): bool
    {
        return $this->created_at->diffInDays(now()) <= self::DELETABLE_DAYS;
    }

    // New accessor for star rating display
    public function getRatingStarsAttribute(): array
    {
        return [
            'filled' => $this->rating,
            'total' => self::MAX_RATING,
            'stars' => range(1, self::MAX_RATING)
        ];
    }

    // Scopes
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('is_approved', false);
    }

    public function scopeForOrder(Builder $query, $orderId): Builder
    {
        return $query->where('order_id', $orderId);
    }

    public function scopeForProduct(Builder $query, $productId): Builder
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByUser(Builder $query, $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecentFirst(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeHighestRated(Builder $query): Builder
    {
        return $query->orderBy('rating', 'desc');
    }

    // New scope for filtering by rating range
    public function scopeRatingBetween(Builder $query, int $min, int $max): Builder
    {
        return $query->whereBetween('rating', [
            max($min, self::MIN_RATING),
            min($max, self::MAX_RATING)
        ]);
    }

    // New scope for user reviews (non-admin)
    public function scopeUserReviews(Builder $query): Builder
    {
        return $query->whereHas('user', function($q) {
            $q->where('role', User::ROLE_USER);
        });
    }

    // New method to check if review belongs to user
    public function belongsToUser(int $userId): bool
    {
        return $this->user_id === $userId;
    }

    // New method to approve review
    public function approve(): bool
    {
        return $this->update(['is_approved' => true]);
    }

    // New method to reject review
    public function reject(): bool
    {
        return $this->update(['is_approved' => false]);
    }
}