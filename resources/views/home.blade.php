@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Welcome to StyleSphere</h1>

    <!-- Navigation and Cart Section -->
    <div class="d-flex justify-content-between mb-4">
        <!-- Hamburger Profile Menu -->
        <div>
            <div class="dropdown">
                <button class="btn btn-outline-secondary" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user-edit me-2"></i>Edit Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('orders.history') }}"><i class="fas fa-history me-2"></i>Order History</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Shopping Cart -->
        <div>
            <a href="{{ route('cart.index') }}" class="btn btn-outline-info position-relative">
                <i class="fas fa-shopping-bag"></i>
                @if(auth()->user()->cart->count() > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ auth()->user()->cart->count() }}
                </span>
                @endif
            </a>
        </div>
    </div>

    <div class="row">
        @if($product->total() > 0)
            @foreach ($product as $p)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0">
                        <!-- Product Image Carousel -->
                        @if($p->images->count() > 0)
                        <div id="productCarousel-{{ $p->product_id }}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($p->images as $key => $image)
                                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                         class="d-block w-100 card-img-top" 
                                         style="height: 300px; object-fit: cover;" 
                                         alt="{{ $p->product_name }}">
                                </div>
                                @endforeach
                            </div>
                            @if($p->images->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel-{{ $p->product_id }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel-{{ $p->product_id }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            @endif
                        </div>
                        @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                        @endif

                        <div class="card-body">
                            <h5 class="card-title">{{ $p->product_name }}</h5>
                            <p class="card-text"><strong>Supplier:</strong> {{ $p->supplier->brand_name ?? 'No Supplier' }}</p>
                            <p class="card-text"><strong>Price:</strong> ${{ number_format($p->sell_price, 2) }}</p>
                            <p class="card-text"><strong>Stock:</strong> {{ $p->stock }}</p>
                            
                            <!-- Product Rating Display -->
                            @if($p->reviews->count() > 0)
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-1">
                                        @php
                                            $avgRating = $p->reviews->avg('rating');
                                            $fullStars = floor($avgRating);
                                            $hasHalfStar = ($avgRating - $fullStars) >= 0.5;
                                        @endphp
                                        
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $fullStars)
                                                <i class="fas fa-star text-warning"></i>
                                            @elseif($i == $fullStars + 1 && $hasHalfStar)
                                                <i class="fas fa-star-half-alt text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                        <span class="ms-2">{{ number_format($avgRating, 1) }} ({{ $p->reviews->count() }} reviews)</span>
                                    </div>
                                    
                                    <!-- Show user's review if exists -->
                                    @if(auth()->check() && $p->userReview(auth()->id()))
                                        @php $userReview = $p->userReview(auth()->id()); @endphp
                                        <div class="bg-light p-2 rounded">
                                            <div class="d-flex justify-content-between">
                                                <strong>Your Review:</strong>
                                                <div>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $userReview->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            @if($userReview->comment)
                                                <p class="mb-0 mt-1">"{{ $userReview->comment }}"</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            <form action="{{ route('cart.add') }}" method="POST" class="mb-2">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $p->product_id }}">
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </form>
                            
                            <!-- Review Button (only for authenticated users) -->
                            @auth
                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#reviewModal-{{ $p->product_id }}">
                                    <i class="fas fa-star me-1"></i>
                                    {{ $p->userReview(auth()->id()) ? 'Edit Review' : 'Add Review' }}
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- Review Modal for each product -->
                <div class="modal fade" id="reviewModal-{{ $p->product_id }}" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="reviewModalLabel">Review {{ $p->product_name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $p->product_id }}">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Rating</label>
                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="far fa-star star" data-rating="{{ $i }}" style="cursor: pointer; font-size: 1.5rem; margin-right: 5px;"></i>
                                            @endfor
                                            <input type="hidden" name="rating" id="rating-{{ $p->product_id }}" value="{{ $p->userReview(auth()->id())->rating ?? 0 }}">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="comment-{{ $p->product_id }}" class="form-label">Review (optional)</label>
                                        <textarea class="form-control" id="comment-{{ $p->product_id }}" name="comment" rows="3">{{ $p->userReview(auth()->id())->comment ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit Review</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <p class="text-center">No products available.</p>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($product->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $product->links() }}
    </div>
    @endif
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize star rating functionality for each product
        document.querySelectorAll('.rating-stars .star').forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                const modalId = this.closest('.modal').id;
                const productId = modalId.split('-')[1];
                const ratingInput = document.getElementById(`rating-${productId}`);
                
                // Update selected rating
                ratingInput.value = rating;
                
                // Update star display
                const stars = this.parentElement.querySelectorAll('.star');
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.remove('far');
                        s.classList.add('fas', 'text-warning');
                    } else {
                        s.classList.remove('fas', 'text-warning');
                        s.classList.add('far');
                    }
                });
            });
            
            // Initialize stars if editing existing review
            const modalId = star.closest('.modal').id;
            const productId = modalId.split('-')[1];
            const ratingInput = document.getElementById(`rating-${productId}`);
            if (ratingInput.value > 0) {
                const rating = parseInt(ratingInput.value);
                if (parseInt(star.getAttribute('data-rating')) <= rating) {
                    star.classList.remove('far');
                    star.classList.add('fas', 'text-warning');
                }
            }
        });
    });
</script>
@endsection