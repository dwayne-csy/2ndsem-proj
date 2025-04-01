@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h1>
            <p class="text-muted mb-0">Placed on {{ $order->created_at->format('F j, Y \a\t h:i A') }}</p>
        </div>
        <a href="{{ route('orders.history') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Orders
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Order Summary</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Status:</span>
                        <span class="badge rounded-pill py-2 px-3 
                            @if($order->status === 'pending') bg-warning text-dark
                            @elseif($order->status === 'accepted') bg-success
                            @else bg-danger
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Items:</span>
                        <span class="fw-medium">{{ $order->totalItems() }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Order Total:</span>
                        <span class="fw-bold text-success">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Shipping Information</h5>
                    <p class="mb-1">{{ $order->user->name }}</p>
                    <p class="mb-1 text-muted">User email: {{ $order->user->email }}</p>
                    <!-- Add more shipping details if you have them -->
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="fw-bold mb-0">Order Items</h5>
        </div>
        <div class="card-body">
            @foreach($order->orderLines as $line)
            <div class="row align-items-center mb-3 pb-3 border-bottom">
                <div class="col-md-2">
                    @if($line->product->images->count() > 0)
                    <img src="{{ asset('storage/' . $line->product->images->first()->image_path) }}" 
                         class="img-fluid rounded" 
                         alt="{{ $line->product->name }}">
                    @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                        <i class="fas fa-image fa-2x text-muted"></i>
                    </div>
                    @endif
                </div>
                <div class="col-md-5">
                    <h6 class="fw-bold mb-1">{{ $line->product->product_name }}</h6>
                    <small class="text-muted">SKU: {{ $line->product->sku ?? 'N/A' }}</small>
                </div>
                <div class="col-md-2 text-center">
                    <span class="fw-medium">×{{ $line->quantity }}</span>
                </div>
                <div class="col-md-3 text-end">
                    <span class="fw-bold">${{ number_format($line->unit_price * $line->quantity, 2) }}</span>
                </div>
            </div>
            @endforeach
            
            <div class="row justify-content-end mt-4">
                <div class="col-md-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal:</span>
                        <span>${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Shipping:</span>
                        <span>$0.00</span> <!-- Update if you have shipping costs -->
                    </div>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total:</span>
                        <span class="text-success">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection