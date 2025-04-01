@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Import Products</h2>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.product.import.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="supplier">Select Supplier</label>
                    <select class="form-control" id="supplier" name="supplier_id" required>
                        <option value="">-- Select Supplier --</option>
                        @foreach($supplier as $supplier)
                            <option value="{{ $supplier->supplier_id }}">{{ $supplier->brand_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
        <label for="file">Select Excel File</label>
        <input type="file" class="form-control-file" id="file" name="file" required>
        <small class="form-text text-muted">
            Supported formats: .xlsx, .xls, .csv
        </small>
    </div>
                <button type="submit" class="btn btn-primary mt-3">
                    <i class="fas fa-file-import"></i> Import
                </button>
            </form>

            <div class="mt-4">
                <h5>File Format Example (for selected supplier):</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>product_name</th>
                            <th>size</th>
                            <th>category</th>
                            <th>types</th>
                            <th>description</th>
                            <th>cost_price</th>
                            <th>sell_price</th>
                            <th>stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Dino Shirt</td>
                            <td>M</td>
                            <td>Men</td>
                            <td>T-Shirt</td>
                            <td>100% Cotton</td>
                            <td>50.00</td>
                            <td>79.99</td>
                            <td>100</td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="alert alert-info mt-3">
                    <strong>Note:</strong> 
                    <ul>
                        <li>All imported products will be assigned to the selected supplier</li>
                        <li>Sell price must be greater than or equal to cost price</li>
                        <li>Ensure categories match your existing product categories</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection