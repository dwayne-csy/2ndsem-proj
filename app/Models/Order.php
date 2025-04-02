<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderLines()
    {
        return $this->hasMany(OrderLine::class);
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isAccepted()
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function accept()
    {
        if (!$this->isPending()) {
            return false;
        }

        DB::transaction(function () {
            $this->update(['status' => self::STATUS_ACCEPTED]);
            
            foreach ($this->orderLines as $orderLine) {
                $product = $orderLine->product;
                
                if ($product && $product->stock >= $orderLine->quantity) {
                    $product->decrement('stock', $orderLine->quantity);
                } else {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }
            }
        });

        return true;
    }

    public function cancel($restoreStock = false)
    {
        if (!$this->isPending() && !$this->isAccepted()) {
            return false;
        }

        DB::transaction(function () use ($restoreStock) {
            $previousStatus = $this->status;
            $this->update(['status' => self::STATUS_CANCELLED]);

            if ($restoreStock && $previousStatus === self::STATUS_ACCEPTED) {
                foreach ($this->orderLines as $orderLine) {
                    if ($orderLine->product) {
                        $orderLine->product->increment('stock', $orderLine->quantity);
                    }
                }
            }
        });

        return true;
    }

    public function totalItems()
    {
        return $this->orderLines->sum('quantity');
    }

    public function scopeUserHistory($query)
    {
        return $query->where('user_id', auth()->id())
                    ->with(['orderLines.product.reviews' => function($q) {
                        $q->where('user_id', auth()->id());
                    }])
                    ->latest();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // New method to check if order can be reviewed
    public function canBeReviewed()
    {
        return $this->status === self::STATUS_ACCEPTED && 
               $this->orderLines->contains(function($line) {
                   return !$line->product->reviews->where('user_id', auth()->id())->count();
               });
    }
}