@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Update Profile') }}</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="image" class="col-md-4 col-form-label text-md-end">
                                {{ __('Profile Image') }}
                            </label>
                            <div class="col-md-6">
                                <img src="{{ auth()->user()->image ? asset('storage/' . auth()->user()->image) : asset('images/default-avatar.jpg') }}" 
                                     class="rounded-circle mb-2" 
                                     width="100" 
                                     height="100">
                                <input id="image" 
                                       type="file" 
                                       class="form-control @error('image') is-invalid @enderror" 
                                       name="image">
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="full_name" class="col-md-4 col-form-label text-md-end">
                                {{ __('Full Name') }} *
                            </label>
                            <div class="col-md-6">
                                <input id="full_name" 
                                       type="text" 
                                       class="form-control @error('full_name') is-invalid @enderror" 
                                       name="full_name" 
                                       value="{{ old('full_name', auth()->user()->full_name) }}" 
                                       required>
                                @error('full_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="age" class="col-md-4 col-form-label text-md-end">
                                {{ __('Age') }}
                            </label>
                            <div class="col-md-6">
                                <input id="age" 
                                       type="number" 
                                       class="form-control @error('age') is-invalid @enderror" 
                                       name="age" 
                                       value="{{ old('age', auth()->user()->age) }}">
                                @error('age')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="sex" class="col-md-4 col-form-label text-md-end">
                                {{ __('Gender') }}
                            </label>
                            <div class="col-md-6">
                                <select id="sex" class="form-control @error('sex') is-invalid @enderror" name="sex">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('sex', auth()->user()->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sex', auth()->user()->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('sex', auth()->user()->sex) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('sex')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="contact_number" class="col-md-4 col-form-label text-md-end">
                                {{ __('Contact Number') }}
                            </label>
                            <div class="col-md-6">
                                <input id="contact_number" 
                                       type="text" 
                                       class="form-control @error('contact_number') is-invalid @enderror" 
                                       name="contact_number" 
                                       value="{{ old('contact_number', auth()->user()->contact_number) }}">
                                @error('contact_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="address" class="col-md-4 col-form-label text-md-end">
                                {{ __('Address') }}
                            </label>
                            <div class="col-md-6">
                                <textarea id="address" 
                                          class="form-control @error('address') is-invalid @enderror" 
                                          name="address">{{ old('address', auth()->user()->address) }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update Profile') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<!-- Font Awesome for the arrow icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
@endsection