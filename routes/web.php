<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;


//REGISTER
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
//LOGIN
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
//LOGOUT
Route::post('logout', [LoginController::class, 'logout'])->name('logout');



// Admin Routes (Protected with Auth & Admin Middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Product Routes
    Route::get('product', [ProductController::class, 'index'])->name('product.index');
    Route::get('product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('product/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('product/{product}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('product/{product}', [ProductController::class, 'destroy'])->name('product.destroy');

    //EXCEL
    Route::get('/product/import', [ProductController::class, 'showImportForm'])->name('product.import');
    Route::post('/product/import', [ProductController::class, 'import'])->name('product.import.submit');


    // Supplier Routes
    Route::get('supplier', [SupplierController::class, 'index'])->name('supplier.index');
    Route::get('supplier/create', [SupplierController::class, 'create'])->name('supplier.create');
    Route::post('supplier/store', [SupplierController::class, 'store'])->name('supplier.store');
    Route::get('supplier/{supplier}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
    Route::put('supplier/{supplier}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('supplier/{supplier}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
    
    //EXCEL
    Route::get('suppliers/import', [SupplierController::class, 'showImportForm'])->name('suppliers.import'); 
    Route::post('suppliers/import', [SupplierController::class, 'import'])->name('suppliers.import.process');
    
    // User Routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::post('users/{user}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');
    Route::post('users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');

    // Order Routes
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index'); // Fixed: Removed /admin prefix
    Route::post('orders/{id}/accept', [OrderController::class, 'accept'])->name('orders.accept'); // Fixed: Removed /admin prefix
    Route::post('orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel'); // Fixed: Removed /admin prefix
});
Route::middleware('auth')->group(function () {
    // PROFILE ROUTES
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // CART ROUTES
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // ORDER HISTORY ROUTE
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');


    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/get', [ReviewController::class, 'getReview'])->name('reviews.get');
    Route::get('/reviews/check', [ReviewController::class, 'check'])->name('reviews.check');
});

    
// Regular user route
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'user.status'])->group(function () {
    // Protected routes
});





