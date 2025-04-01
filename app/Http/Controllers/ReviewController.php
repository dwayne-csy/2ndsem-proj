<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => [
                'required',
                Rule::exists('orders', 'id')->where(function ($query) {
                    $query->where('user_id', auth()->id());
                })
            ],
            'product_id' => [
                'required',
                Rule::exists('order_items', 'product_id')->where('order_id', $request->order_id)
            ],
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $review = Review::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'order_id' => $request->order_id,
                'product_id' => $request->product_id
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment
            ]
        );

        return response()->json([
            'success' => true,
            'review' => $review,
            'message' => $review->wasRecentlyCreated ? 
                'Review submitted successfully!' : 
                'Review updated successfully!'
        ]);
    }

    public function check(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id'
        ]);
    
        $review = Review::where([
            'user_id' => auth()->id(),
            'order_id' => $request->order_id,
            'product_id' => $request->product_id
        ])->first();
    
        return response()->json([
            'review' => $review
        ]);
    }
}