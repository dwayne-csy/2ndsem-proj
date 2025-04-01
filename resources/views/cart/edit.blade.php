@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Cart Product</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('cart.update', $cartProduct->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="product_name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="product_name" value="{{ $cartProduct->product->name }}" disabled>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="{{ $cartProduct->quantity }}" min="1" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="text" class="form-control" id="price" value="${{ number_format($cartProduct->product->price, 2) }}" disabled>
        </div>

        <div class="mb-3">
            <label for="total" class="form-label">Total</label>
            <input type="text" class="form-control" id="total" value="${{ number_format($cartProduct->quantity * $cartProduct->product->price, 2) }}" disabled>
        </div>

        <button type="submit" class="btn btn-primary">Update Cart</button>
        <a href="{{ route('cart.index') }}" class="btn btn-secondary">Back to Cart</a>
    </form>
</div>
@endsection
