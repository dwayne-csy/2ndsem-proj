@extends('layouts.app')

@section('content')
    <!-- StyleSphere Header with Enhanced Design -->
    <div class="header bg-gradient-primary py-7 py-lg-8" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container">
            <div class="header-body text-center mb-7">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10">
                        <!-- Animated StyleSphere Text -->
                        <h1 class="text-white display-3 animate__animated animate__fadeInDown" style="font-family: 'Poppins', sans-serif; font-weight: 700; text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);">
                            StyleSphere
                        </h1>
                        <!-- Subtitle with Animation -->
                        <p class="text-lead text-light animate__animated animate__fadeInUp" style="font-size: 1.5rem; font-family: 'Poppins', sans-serif; text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.2);">
                            Welcome to the Admin Dashboard
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Animated Waves Background -->
        <div class="wave-container">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#ffffff" fill-opacity="1" d="M0,160L48,149.3C96,139,192,117,288,128C384,139,480,181,576,192C672,203,768,181,864,160C960,139,1056,117,1152,112C1248,107,1344,117,1392,122.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </div>

    <!-- Admin Dashboard Links -->
    <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-transparent">
                        <h3 class="mb-0 text-center" style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #333;">Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-around flex-wrap">
                            <a href="{{ route('admin.supplier.index') }}" class="btn btn-info btn-lg mb-3 animate__animated animate__fadeInLeft" style="min-width: 200px;">
                                <i class="fas fa-truck mr-2"></i> Manage Suppliers
                            </a>
                            <a href="{{ route('admin.product.index') }}" class="btn btn-success btn-lg mb-3 animate__animated animate__fadeInUp" style="min-width: 200px;">
                                <i class="fas fa-box-open mr-2"></i> Manage Products
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-warning btn-lg mb-3 animate__animated animate__fadeInDown" style="min-width: 200px;">
                                <i class="fas fa-users mr-2"></i> Manage Users
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-danger btn-lg mb-3 animate__animated animate__fadeInRight" style="min-width: 200px;">
                                <i class="fas fa-shopping-cart mr-2"></i> Manage Orders
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS for Animations and Styling -->
    <style>
        /* Animate.css for animations */
        @import url('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');

        /* Google Fonts for Typography */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        /* Wave Animation */
        .wave-container {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .wave-container svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 150px;
        }

        /* Button Hover Effects */
        .btn-lg {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-lg:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
@endsection