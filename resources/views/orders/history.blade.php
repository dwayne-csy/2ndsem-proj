@extends('layouts.app')

@section('content')
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-primary">Order History</h1>
        <div class="bg-primary p-2 rounded">
            <span class="text-white fw-bold">Total Orders: {{ $orders->total() }}</span>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h3 class="text-muted">No Orders Yet</h3>
                <p class="text-muted">Your order history will appear here once you make purchases</p>
                <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                </a>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Order #</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="pe-4 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr class="align-middle">
                                <td class="ps-4 fw-bold">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium">{{ $order->created_at->format('M d, Y') }}</span>
                                        <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-secondary rounded-pill me-2">{{ $order->orderLines->sum('quantity') }}</span>
                                        <span>items</span>
                                    </div>
                                </td>
                                <td class="fw-bold text-success">${{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge rounded-pill py-2 px-3 
                                        @if($order->status === 'pending') bg-warning text-dark
                                        @elseif($order->status === 'accepted') bg-success
                                        @else bg-danger
                                        @endif">
                                        <i class="fas 
                                            @if($order->status === 'pending') fa-clock
                                            @elseif($order->status === 'accepted') fa-check-circle
                                            @else fa-times-circle
                                            @endif me-1"></i>
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="fas fa-eye me-1"></i> Details
                                        </a>
                                        @if($order->status === 'accepted')
                                            @php
                                                // Get first product in order that hasn't been reviewed by current user
                                                $unreviewedProduct = $order->orderLines->first(function($line) {
                                                    return !$line->product->reviews->where('user_id', auth()->id())->count();
                                                })->product ?? null;
                                                
                                                // Or get first product if you want to allow multiple reviews per product
                                                // $unreviewedProduct = $order->orderLines->first()->product ?? null;
                                            @endphp
                                            
                                            @if($unreviewedProduct)
                                                <button class="btn btn-sm btn-success rounded-pill px-3 review-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#reviewModal"
                                                        data-order-id="{{ $order->id }}"
                                                        data-product-id="{{ $unreviewedProduct->product_id }}">
                                                    <i class="fas fa-star me-1"></i> Review
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-info rounded-pill px-3" disabled>
                                                    <i class="fas fa-check me-1"></i> Reviewed
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
            </div>
            <div>
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="reviewModalLabel">Rate Your Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reviewForm" method="POST" action="{{ route('reviews.store') }}">
                @csrf
                <input type="hidden" name="order_id" id="order_id">
                <input type="hidden" name="product_id" id="product_id">
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <h6 class="mb-3">How was your experience with this product?</h6>
                        <div class="rating-stars mb-2">
                            <i class="far fa-star fa-2x star" data-rating="1"></i>
                            <i class="far fa-star fa-2x star" data-rating="2"></i>
                            <i class="far fa-star fa-2x star" data-rating="3"></i>
                            <i class="far fa-star fa-2x star" data-rating="4"></i>
                            <i class="far fa-star fa-2x star" data-rating="5"></i>
                        </div>
                        <small class="text-muted" id="rating-text">Tap to rate</small>
                        <input type="hidden" name="rating" id="rating" value="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Your Review (Optional)</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="Share your thoughts about this product..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .rating-stars {
        font-size: 2rem;
        display: inline-block;
    }
    .rating-stars .star {
        cursor: pointer;
        margin: 0 5px;
        transition: all 0.2s;
        color: #e4e5e9;
    }
    .rating-stars .star.selected,
    .rating-stars .star.hovered {
        color: #ffc107;
    }
    .rating-stars .star.hovered {
        transform: scale(1.1);
    }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-5px); }
        40%, 80% { transform: translateX(5px); }
    }
</style>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reviewModal = document.getElementById('reviewModal');
        
        // When modal opens
        reviewModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            document.getElementById('order_id').value = button.getAttribute('data-order-id');
            document.getElementById('product_id').value = button.getAttribute('data-product-id');
            resetStars();
            
            // Check for existing review
            fetch(`/reviews/check?order_id=${button.getAttribute('data-order-id')}&product_id=${button.getAttribute('data-product-id')}`)
                .then(response => response.json())
                .then(data => {
                    if (data.review) {
                        // Populate existing review
                        setRating(data.review.rating);
                        document.getElementById('comment').value = data.review.comment || '';
                    }
                });
        });

        function resetStars() {
            document.querySelectorAll('.star').forEach(star => {
                star.classList.remove('selected', 'hovered', 'fas');
                star.classList.add('far');
            });
            document.getElementById('rating').value = "0";
            document.getElementById('rating-text').textContent = "Tap to rate";
            document.getElementById('rating-text').classList.remove('text-danger');
        }

        function setRating(rating) {
            const stars = document.querySelectorAll('.star');
            const ratingText = document.getElementById('rating-text');
            const messages = ["Tap to rate", "Poor", "Fair", "Good", "Very Good", "Excellent"];
            
            stars.forEach((star, index) => {
                const starRating = parseInt(star.getAttribute('data-rating'));
                if (starRating <= rating) {
                    star.classList.add('fas', 'selected');
                    star.classList.remove('far');
                } else {
                    star.classList.remove('fas', 'selected');
                    star.classList.add('far');
                }
            });
            
            document.getElementById('rating').value = rating;
            ratingText.textContent = messages[rating];
        }

        // Star rating click handler
        document.querySelector('.rating-stars').addEventListener('click', function(e) {
            if (e.target.classList.contains('star')) {
                const rating = parseInt(e.target.getAttribute('data-rating'));
                setRating(rating);
            }
        });

        // Hover effects
        document.querySelector('.rating-stars').addEventListener('mouseover', function(e) {
            if (e.target.classList.contains('star')) {
                const hoverRating = parseInt(e.target.getAttribute('data-rating'));
                document.querySelectorAll('.star').forEach(star => {
                    const starRating = parseInt(star.getAttribute('data-rating'));
                    star.classList.toggle('hovered', starRating <= hoverRating);
                });
            }
        });

        document.querySelector('.rating-stars').addEventListener('mouseout', function() {
            document.querySelectorAll('.star').forEach(star => {
                star.classList.remove('hovered');
            });
        });

        // Form validation
        document.getElementById('reviewForm').addEventListener('submit', function(e) {
            if (document.getElementById('rating').value === "0") {
                e.preventDefault();
                const ratingText = document.getElementById('rating-text');
                ratingText.textContent = "Please select a rating";
                ratingText.classList.add('text-danger');
                
                document.querySelectorAll('.star').forEach(star => {
                    star.style.animation = 'shake 0.5s';
                    star.addEventListener('animationend', () => {
                        star.style.animation = '';
                    });
                });
            }
        });
    });
</script>
@endsection
@endsection