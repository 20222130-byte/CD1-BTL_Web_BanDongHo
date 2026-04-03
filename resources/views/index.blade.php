@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Admin Dashboard Button -->
@if (session('logged_in') && session('role') === 'admin')
<div class="alert alert-warning alert-dismissible d-flex justify-content-between align-items-center mb-4" role="alert">
    <div>
        <i class="bi bi-shield-check"></i> <strong>Bạn là Admin</strong> - Truy cập bảng điều khiển để quản lý hệ thống
    </div>
    <a href="/dashboard" class="btn btn-warning">
        <i class="bi bi-speedometer2"></i> Vào Dashboard
    </a>
</div>
@endif

<div class="mb-4">
    <h2 class="text-center">Danh Sách Sản Phẩm Đồng Hồ</h2>
</div>

<div class="row">
    @for ($i = 1; $i <= 6; $i++)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm hover-card" style="transition: transform 0.3s;">
                <img src="https://via.placeholder.com/300x200?text=Đồng+Hồ+{{ $i }}" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title text-primary fw-bold">Đồng Hồ {{ $i }}</h5>
                    <p class="card-text text-muted">Chất lượng premium, bảo hành 2 năm</p>
                    <p class="card-text text-danger"><strong>Giá: {{ number_format(rand(1000000,5000000), 0, ',', '.') }} VNĐ</strong></p>
                    <button class="btn btn-primary w-100">Xem Chi Tiết</button>
                </div>
            </div>
        </div>
    @endfor
</div>

<style>
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
    }
</style>

@endsection