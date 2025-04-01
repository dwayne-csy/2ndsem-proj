<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    // Define order statuses
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

    // Status check methods
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

    /**
     * Accept the order and decrease product stocks
     */
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

    /**
     * Cancel the order and optionally restore stocks
     */
    public function cancel($restoreStock = false)
    {
        if (!$this->isPending() && !$this->isAccepted()) {
            return false;
        }

        DB::transaction(function () use ($restoreStock) {
            $previousStatus = $this->status;
            $this->update(['status' => self::STATUS_CANCELLED]);
            
            // Restore stock if cancelling an accepted order and restoreStock is true
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

    /**
     * Calculate the total items in the order
     */
    public function totalItems()
    {
        return $this->orderLines->sum('quantity');
    }

    /**
     * Scope for user's order history
     */
    public function scopeUserHistory($query)
    {
        return $query->where('user_id', auth()->id())
                    ->with(['orderLines.product'])
                    ->latest();
    }
}