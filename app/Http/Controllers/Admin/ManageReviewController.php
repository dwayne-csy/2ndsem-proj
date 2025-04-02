<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ManageReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of user reviews with filters
     */
    public function index(Request $request)
    {
        $reviews = Review::userReviews()
            ->with(['user', 'product', 'order'])
            ->when($request->filled('search'), function($query) use ($request) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->whereHas('product', function($q) use ($search) {
                        $q->where('product_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('comment', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('rating'), function($query) use ($request) {
                $query->where('rating', $request->input('rating'));
            })
            ->recentFirst()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reviews.index', [
            'reviews' => $reviews,
            'totalReviews' => Review::userReviews()->count(),
            'averageRating' => round(Review::userReviews()->avg('rating'), 1),
        ]);
    }

    /**
     * Display review details
     */
    public function show(Review $review)
    {
        if ($review->user->isAdmin()) {
            abort(403, 'Admin reviews cannot be accessed through this interface');
        }

        $review->load(['user', 'product', 'order']);

        return view('admin.reviews.show', [
            'review' => $review,
            'similarReviews' => Review::where('product_id', $review->product_id)
                ->where('id', '!=', $review->id)
                ->limit(5)
                ->get()
        ]);
    }

    /**
     * Delete a review
     */
    public function destroy(Review $review)
    {
        if ($review->user->isAdmin()) {
            return redirect()->route('admin.reviews.index')
                ->with('error', 'Cannot delete admin reviews');
        }

        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully');
    }
}