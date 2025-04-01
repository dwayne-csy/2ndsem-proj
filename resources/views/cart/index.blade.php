@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Back Button and Title Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h1 class="mb-0">Your Cart</h1>
        <div></div> <!-- Empty div for spacing balance -->
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($cartItems->isEmpty())
        <p>Your cart is empty.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $cartProduct)
                    <tr>
                        <td>
                            {{ $cartProduct->product->product_name }} (ID: {{ $cartProduct->product->product_id }})
                            @if($cartProduct->product->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $cartProduct->product->images->first()->image_path) }}" 
                                     class="img-thumbnail" 
                                     width="80"
                                     style="object-fit: cover; height: 80px;">
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('cart.update', $cartProduct->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="number" name="quantity" value="{{ $cartProduct->quantity }}" min="1" class="form-control" style="width: 80px;">
                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                            </form>
                        </td>
                        <td>${{ number_format($cartProduct->product->sell_price, 2) }}</td>
                        <td>${{ number_format($cartProduct->quantity * $cartProduct->product->sell_price, 2) }}</td>
                        <td>
                            <form action="{{ route('cart.remove', $cartProduct->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-between">
            <h3>Total: ${{ number_format($cartItems->sum(function($cartProduct) { 
                return $cartProduct->quantity * $cartProduct->product->sell_price; 
            }), 2) }}</h3>
            <form action="{{ route('cart.checkout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success btn-lg">Checkout</button>
            </form>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<!-- Font Awesome for the arrow icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection