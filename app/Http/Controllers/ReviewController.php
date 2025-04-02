<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Store or update a review
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => [
                'required',
                Rule::exists('orders', 'id')->where(function ($query) {
                    $query->where('user_id', auth()->id())
                        ->where('status', 'accepted');
                }),
            ],
            'product_id' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = DB::table('order_lines')
                        ->where('order_id', $request->order_id)
                        ->where('product_id', $value)
                        ->exists();
                    
                    if (!$exists) {
                        $fail('The selected product was not ordered in this order.');
                    }
                },
            ],
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $order = Order::find($validated['order_id']);
        if ($order->created_at->diffInDays(now()) > 30) {
            return response()->json([
                'success' => false,
                'message' => 'This order is too old to be reviewed.',
            ], 422);
        }

        $review = Review::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'order_id' => $validated['order_id'],
                'product_id' => $validated['product_id'],
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]
        );

        return response()->json([
            'success' => true,
            'review' => $review,
            'message' => $review->wasRecentlyCreated 
                ? 'Review submitted successfully!' 
                : 'Review updated successfully!',
        ]);
    }

    /**
     * Check if review exists for given order and product
     */
    public function check(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => [
                'required',
                Rule::exists('orders', 'id')
                    ->where('user_id', auth()->id()),
            ],
            'product_id' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = DB::table('order_lines')
                        ->where('order_id', $request->order_id)
                        ->where('product_id', $value)
                        ->exists();
                    
                    if (!$exists) {
                        $fail('The selected product was not ordered in this order.');
                    }
                },
            ],
        ]);

        $review = Review::with(['product' => function($query) {
                $query->select('product_id', 'name', 'image_url');
            }])
            ->where([
                'user_id' => auth()->id(),
                'order_id' => $validated['order_id'],
                'product_id' => $validated['product_id'],
            ])
            ->first();

        return response()->json([
            'success' => true,
            'review' => $review,
            'product' => $review ? $review->product : null,
        ]);
    }

    /**
     * Delete a review (user version)
     */
    public function destroy(Review $review): JsonResponse
    {
        abort_unless(
            auth()->id() === $review->user_id,
            403,
            'Unauthorized action.'
        );
    
        if ($review->created_at->diffInDays(now()) > 7) {
            return response()->json([
                'success' => false,
                'message' => 'Reviews can only be deleted within 7 days of submission.',
            ], 403);
        }
    
        $review->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully!',
        ]);
    }

    /**
     * Check if user can review a product
     */
    public function canReview(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => [
                'required',
                Rule::exists('orders', 'id')
                    ->where('user_id', auth()->id()),
            ],
            'product_id' => 'required|exists:products,product_id',
        ]);

        $canReview = !Review::where([
            'user_id' => auth()->id(),
            'order_id' => $validated['order_id'],
            'product_id' => $validated['product_id'],
        ])->exists();

        $productInOrder = DB::table('order_lines')
            ->where('order_id', $validated['order_id'])
            ->where('product_id', $validated['product_id'])
            ->exists();

        return response()->json([
            'success' => true,
            'can_review' => $canReview && $productInOrder,
            'product_in_order' => $productInOrder,
        ]);
    }
}