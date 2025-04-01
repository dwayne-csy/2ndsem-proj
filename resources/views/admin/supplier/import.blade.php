@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('admin.supplier.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Suppliers
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Import Suppliers</h2>
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

            <form action="{{ route('admin.suppliers.import.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="file">Select Excel File</label>
                    <input type="file" class="form-control-file" id="file" name="file" required>
                    <small class="form-text text-muted">
                        Supported formats: .xlsx, .xls, .csv
                    </small>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-file-import"></i> Import
                </button>
            </form>

            <div class="mt-4">
                <h5>File Format Example:</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>brand_name</th>
                            <th>email</th>
                            <th>phone</th>
                            <th>address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Example Brand</td>
                            <td>supplier@example.com</td>
                            <td>1234567890</td>
                            <td>123 Main Street</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection