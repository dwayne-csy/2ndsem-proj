@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Product</h1>
        <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.product.update', $product->product_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Basic Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Name *</label>
                        <input type="text" name="product_name" class="form-control @error('product_name') is-invalid @enderror" 
                               value="{{ old('product_name', $product->product_name) }}" required>
                        @error('product_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Supplier *</label>
                        <select name="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror" required>
                            <option value="">Select Supplier</option>
                            @foreach($supplier as $supplierItem)
                                <option value="{{ $supplierItem->supplier_id }}" 
                                    {{ old('supplier_id', $product->supplier_id) == $supplierItem->supplier_id ? 'selected' : '' }}>
                                    {{ $supplierItem->brand_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Size *</label>
                        <select name="size" class="form-control @error('size') is-invalid @enderror" required>
                            @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                                <option value="{{ $size }}" 
                                    {{ old('size', $product->size) == $size ? 'selected' : '' }}>
                                    {{ $size }}
                                </option>
                            @endforeach
                        </select>
                        @error('size')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Category *</label>
                        <select name="category" class="form-control @error('category') is-invalid @enderror" required>
                            @foreach(['Mens', 'Womens', 'Kids'] as $category)
                                <option value="{{ $category }}" 
                                    {{ old('category', $product->category) == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Type *</label>
                        <select name="types" class="form-control @error('types') is-invalid @enderror" required>
                            @foreach(['T-shirt', 'Polo Shirt', 'Sweater', 'Hoodie', 'Jersey', 'Dress', 'Sweatshirt', 'Pants', 'Shorts'] as $type)
                                <option value="{{ $type }}" 
                                    {{ old('types', $product->types) == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        @error('types')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Description *</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                              rows="3" required>{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Pricing & Inventory</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Cost Price ($) *</label>
                        <input type="number" name="cost_price" class="form-control @error('cost_price') is-invalid @enderror" 
                               value="{{ old('cost_price', $product->cost_price) }}" step="0.01" min="0" required>
                        @error('cost_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Sell Price ($) *</label>
                        <input type="number" name="sell_price" class="form-control @error('sell_price') is-invalid @enderror" 
                               value="{{ old('sell_price', $product->sell_price) }}" step="0.01" min="0" required>
                        @error('sell_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Stock *</label>
                        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" 
                               value="{{ old('stock', $product->stock) }}" min="0" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Product Images</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Current Images</label>
                    <div class="row">
                        @foreach($product->images as $image)
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                         class="card-img-top" 
                                         alt="Product Image"
                                         style="height: 150px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <div class="form-check">
                                            <input type="checkbox" 
                                                   name="delete_images[]" 
                                                   value="{{ $image->id }}" 
                                                   id="delete_image_{{ $image->id }}" 
                                                   class="form-check-input">
                                            <label for="delete_image_{{ $image->id }}" class="form-check-label text-danger">
                                                Delete Image
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" 
                                                   name="primary_image" 
                                                   value="{{ $image->id }}" 
                                                   id="primary_image_{{ $image->id }}" 
                                                   class="form-check-input"
                                                   {{ $image->is_primary ? 'checked' : '' }}>
                                            <label for="primary_image_{{ $image->id }}" class="form-check-label">
                                                Set as Primary
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Add New Images</label>
                    <input type="file" name="images[]" class="form-control @error('images') is-invalid @enderror" multiple>
                    <small class="text-muted">You can upload multiple images (JPEG, PNG, JPG, GIF)</small>
                    @error('images')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('images.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Update Product
            </button>
            <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary btn-lg">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

@section('styles')
<style>
    .card-img-top {
        transition: transform 0.3s;
    }
    .card-img-top:hover {
        transform: scale(1.05);
    }
    .form-check-label {
        cursor: pointer;
    }
    .is-invalid {
        border-color: #dc3545;
    }
    .invalid-feedback {
        display: block;
        color: #dc3545;
    }
</style>
@endsection