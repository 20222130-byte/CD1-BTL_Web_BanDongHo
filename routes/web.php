<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('index');
});

// Register Routes
Route::get('/register', [RegisterController::class, 'showRegisterForm']);
Route::post('/register', [RegisterController::class, 'register']);

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login']);

// Logout
Route::get('/logout', [LoginController::class, 'logout']);

// Dashboard (chi cho admin)
Route::get('/dashboard', function () {
    if (!session('logged_in') || session('role') !== 'admin') {
        return redirect('/')->with('error', 'Bạn không có quyền truy cập');
    }
    return view('dashboard');
});


