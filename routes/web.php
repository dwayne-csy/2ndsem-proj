<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ManageReviewController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;

Route::get('/', function () {
    return view('landing');
});

Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

// REGISTER
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// LOGIN
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

// LOGOUT
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Product Routes
    Route::resource('product', ProductController::class)->except(['create', 'store']);
    Route::get('/product/import', [ProductController::class, 'showImportForm'])->name('product.import');
    Route::post('/product/import', [ProductController::class, 'import'])->name('product.import.submit');

    // Supplier Routes
    Route::resource('supplier', SupplierController::class);
    Route::get('suppliers/import', [SupplierController::class, 'showImportForm'])->name('suppliers.import'); 
    Route::post('suppliers/import', [SupplierController::class, 'import'])->name('suppliers.import.process');
    
    // User Routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::post('users/{user}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');
    Route::post('users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');

    // Order Routes
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('orders/{id}/accept', [OrderController::class, 'accept'])->name('orders.accept');
    Route::post('orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Review Routes
    Route::get('reviews', [ManageReviewController::class, 'index'])->name('reviews.index');
    Route::get('reviews/{review}', [ManageReviewController::class, 'show'])->name('reviews.show');
    Route::delete('reviews/{review}', [ManageReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // Orders
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // User Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/check', [ReviewController::class, 'check'])->name('reviews.check');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('user.reviews.destroy');
    Route::get('/reviews/can-review', [ReviewController::class, 'canReview'])->name('reviews.can-review');
});

// Home Route
Route::get('/home', [HomeController::class, 'index'])->name('home');