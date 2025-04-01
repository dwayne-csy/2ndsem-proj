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
                                </div>
                            @else
                                <div class="mb-3 text-muted">
                                    <i class="far fa-star"></i> No reviews yet
                                </div>
                            @endif
                            
                            <form action="{{ route('cart.add') }}" method="POST" class="mb-2">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $p->product_id }}">
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </form>
                            
                            <!-- View Reviews Button -->
                            @if($p->reviews->count() > 0)
                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#reviewsModal-{{ $p->product_id }}">
                                    <i class="fas fa-comment me-1"></i> View Reviews
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Reviews Modal for each product -->
                <div class="modal fade" id="reviewsModal-{{ $p->product_id }}" tabindex="-1" aria-labelledby="reviewsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="reviewsModalLabel">Reviews for {{ $p->product_name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @if($p->reviews->count() > 0)
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center">
                                            <h4 class="me-3">{{ number_format($avgRating, 1) }}</h4>
                                            <div>
                                                <div class="d-flex mb-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $fullStars)
                                                            <i class="fas fa-star text-warning"></i>
                                                        @elseif($i == $fullStars + 1 && $hasHalfStar)
                                                            <i class="fas fa-star-half-alt text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-warning"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <p class="mb-0">{{ $p->reviews->count() }} reviews</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="reviews-list">
                                        @foreach($p->reviews as $review)
                                            <div class="review-item mb-3 pb-3 border-bottom">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <strong>{{ $review->user->name ?? 'Anonymous' }}</strong>
                                                    <div>
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                                @if($review->comment)
                                                    <p class="mb-0">"{{ $review->comment }}"</p>
                                                @else
                                                    <p class="text-muted mb-0">No comment provided</p>
                                                @endif
                                                <small class="text-muted d-block mt-2">{{ $review->created_at->format('M d, Y') }}</small>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-center">No reviews yet for this product.</p>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
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
@endsection