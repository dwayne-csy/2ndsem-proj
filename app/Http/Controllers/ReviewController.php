<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500'
        ]);
    
        // Check if order belongs to user and is accepted
        $order = Order::where('id', $validated['order_id'])
                    ->where('user_id', auth()->id())
                    ->where('status', Order::STATUS_ACCEPTED)
                    ->firstOrFail();
    
        // Check if review already exists
        if ($order->review) {
            return back()->with('error', 'You have already reviewed this order');
        }
    
        // Create review
        auth()->user()->reviews()->create($validated);
    
        return back()->with('success', 'Thank you for your review!');
    }
}
