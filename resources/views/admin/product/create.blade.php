@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Create New Product</h1>
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

    <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Basic Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Name *</label>
                        <input type="text" name="product_name" class="form-control @error('product_name') is-invalid @enderror" 
                               value="{{ old('product_name') }}" required>
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
                                    {{ old('supplier_id') == $supplierItem->supplier_id ? 'selected' : '' }}>
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
                                    {{ old('size') == $size ? 'selected' : '' }}>
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
                                    {{ old('category') == $category ? 'selected' : '' }}>
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
                                    {{ old('types') == $type ? 'selected' : '' }}>
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
                              rows="3" required>{{ old('description') }}</textarea>
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
                               value="{{ old('cost_price') }}" step="0.01" min="0" required>
                        @error('cost_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Sell Price ($) *</label>
                        <input type="number" name="sell_price" class="form-control @error('sell_price') is-invalid @enderror" 
                               value="{{ old('sell_price') }}" step="0.01" min="0" required>
                        @error('sell_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Stock *</label>
                        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" 
                               value="{{ old('stock') }}" min="0" required>
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
                    <label class="form-label">Upload Product Images *</label>
                    <input type="file" name="images[]" class="form-control @error('images') is-invalid @enderror" multiple required>
                    <small class="text-muted">You can upload multiple images (JPEG, PNG, JPG, GIF). First image will be set as primary.</small>
                    @error('images')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('images.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Image Preview</label>
                    <div class="row" id="image-preview-container">
                        <!-- Preview will appear here -->
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-plus-circle"></i> Create Product
            </button>
            <button type="reset" class="btn btn-outline-secondary btn-lg">
                <i class="fas fa-undo"></i> Reset Form
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image preview functionality
        const imageInput = document.querySelector('input[name="images[]"]');
        const previewContainer = document.getElementById('image-preview-container');
        
        imageInput.addEventListener('change', function() {
            previewContainer.innerHTML = '';
            
            if (this.files) {
                Array.from(this.files).forEach((file, index) => {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-md-3 mb-3';
                        
                        const card = document.createElement('div');
                        card.className = 'card';
                        
                        const img = document.createElement('img');
                        img.className = 'card-img-top';
                        img.src = e.target.result;
                        img.alt = 'Preview ' + (index + 1);
                        img.style.height = '150px';
                        img.style.objectFit = 'cover';
                        
                        const cardBody = document.createElement('div');
                        cardBody.className = 'card-body p-2 text-center';
                        cardBody.textContent = file.name;
                        
                        card.appendChild(img);
                        card.appendChild(cardBody);
                        col.appendChild(card);
                        previewContainer.appendChild(col);
                    }
                    
                    reader.readAsDataURL(file);
                });
            }
        });
    });
</script>
@endsection

@section('styles')
<style>
    .card-img-top {
        transition: transform 0.3s;
    }
    .card-img-top:hover {
        transform: scale(1.05);
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