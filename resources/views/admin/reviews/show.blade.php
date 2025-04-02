@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-lg my-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h2 class="h5 mb-0 fw-bold">
                <i class="fas fa-star me-2"></i> Review Details
            </h2>
            <div>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Review Details -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h3 class="h6 mb-0">Review Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h5 class="fw-bold">Product</h5>
                                    <div class="d-flex align-items-center mt-3">
                                        <img src="{{ $review->product->image_url }}" 
                                             alt="{{ $review->product->product_name }}"
                                             class="rounded me-3" width="80" height="80" style="object-fit: cover;">
                                        <div>
                                            <h6 class="mb-1">{{ $review->product->product_name }}</h6>
                                            <p class="small text-muted mb-1">
                                                <span class="badge bg-primary">{{ $review->product->category }}</span>
                                                <span class="badge bg-secondary ms-1">{{ $review->product->types }}</span>
                                            </p>
                                            <p class="small text-muted mb-0">${{ number_format($review->product->sell_price, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="fw-bold">Customer</h5>
                                    <div class="d-flex align-items-center mt-3">
                                        <div class="avatar me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            {{ substr($review->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-1">{{ $review->user->name }}</h6>
                                            <p class="small text-muted mb-1">{{ $review->user->email }}</p>
                                            <p class="small text-muted mb-0">Member since {{ $review->user->created_at->format('M Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h5 class="fw-bold">Rating</h5>
                                        <div class="star-rating fs-4">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                            @endfor
                                            <span class="ms-2 fw-bold">{{ $review->rating }}/5</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h5 class="fw-bold">Review Date</h5>
                                        <p>{{ $review->created_at->format('F j, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <h5 class="fw-bold">Customer Feedback</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ $review->comment ?? 'No comment provided' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Review Actions & Meta -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h3 class="h6 mb-0">Review Actions</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if(!$review->is_approved)
                                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100 mb-2">
                                        <i class="fas fa-check-circle me-2"></i> Approve Review
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.reviews.reject', $review) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-warning w-100 mb-2">
                                        <i class="fas fa-times-circle me-2"></i> Reject Review
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash-alt me-2"></i> Delete Review
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Order Information -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h3 class="h6 mb-0">Order Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h5 class="fw-bold">Order #{{ $review->order->id }}</h5>
                                <p class="mb-1">
                                    <span class="badge bg-{{ $review->order->status === 'accepted' ? 'success' : 'warning' }}">
                                        {{ ucfirst($review->order->status) }}
                                    </span>
                                </p>
                                <p class="small text-muted mb-0">
                                    Placed on {{ $review->order->created_at->format('M j, Y') }}
                                </p>
                            </div>
                            <div class="mb-3">
                                <h5 class="fw-bold">Total Amount</h5>
                                <p>${{ number_format($review->order->total_amount, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Similar Reviews -->
            @if($similarReviews->count() > 0)
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h3 class="h6 mb-0">Other Reviews for This Product</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Rating</th>
                                    <th>Comment</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($similarReviews as $similar)
                                <tr>
                                    <td>{{ $similar->user->name }}</td>
                                    <td>
                                        <div class="star-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $similar->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                            @endfor
                                        </div>
                                    </td>
                                    <td>{{ Str::limit($similar->comment, 30) }}</td>
                                    <td>{{ $similar->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge rounded-pill bg-{{ $similar->is_approved ? 'success' : 'warning' }}">
                                            {{ $similar->is_approved ? 'Approved' : 'Pending' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .star-rating {
        color: #ffc107;
    }
    .avatar {
        font-size: 1.25rem;
        font-weight: bold;
    }
    .card-header {
        background-color: #f8f9fa;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endpush