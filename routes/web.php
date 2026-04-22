<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductManagerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserManagerController;

Route::get('/', [ProductController::class, 'index']);

// Product Routes
Route::get('/product/{id}', [ProductController::class, 'show']);

// Order Routes
Route::get('/cart', [OrderController::class, 'showCart']);
Route::get('/checkout', [OrderController::class, 'showCheckout']);
Route::post('/process-payment', [OrderController::class, 'processPayment']);
Route::get('/order-success', [OrderController::class, 'showOrderSuccess']);
Route::get('/orders', [OrderController::class, 'listOrders']);

// Wishlist Route
Route::get('/wishlist', function () {
    return view('wishlist');
});

// Register Routes
Route::get('/register', [RegisterController::class, 'showRegisterForm']);
Route::post('/register', [RegisterController::class, 'register']);

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login']);

// Logout
Route::get('/logout', [LoginController::class, 'logout']);

// Dashboard (chỉ cho admin)
Route::get('/dashboard', [DashboardController::class, 'index']);

// Báo cáo thống kê
Route::get('/report', [ReportController::class, 'showReport']);

// Product Manager Routes (Admin)
Route::get('/product-manager', [ProductManagerController::class, 'index']);
Route::get('/product-create', [ProductManagerController::class, 'create']);
Route::post('/product-store', [ProductManagerController::class, 'store']);
Route::get('/product-edit/{id}', [ProductManagerController::class, 'show']);
Route::post('/product-update/{id}', [ProductManagerController::class, 'update']);
Route::post('/product-delete/{id}', [ProductManagerController::class, 'delete']);

// User Manager Routes (Admin)
Route::get('/user-manager', [UserManagerController::class, 'index']);
Route::get('/user-create', [UserManagerController::class, 'create']);
Route::post('/user-store', [UserManagerController::class, 'store']);
Route::get('/user-edit/{id}', [UserManagerController::class, 'show']);
Route::post('/user-update/{id}', [UserManagerController::class, 'update']);
Route::post('/user-delete/{id}', [UserManagerController::class, 'delete']);


// Category Manager Routes (Admin)
Route::get('/category-manager', [ProductManagerController::class, 'categoryIndex']);
Route::post('/category-store', [ProductManagerController::class, 'categoryStore']);
Route::get('/category-edit/{id}', [ProductManagerController::class, 'categoryEdit']);
Route::post('/category-update/{id}', [ProductManagerController::class, 'categoryUpdate']);
Route::get('/category-delete/{id}', [ProductManagerController::class, 'categoryDelete']);
