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

<!-- Search and Filter Form -->
<div class="mb-4">
    <form method="GET" action="/" class="d-flex justify-content-center align-items-center gap-3">
        <div class="input-group" style="max-width: 300px;">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-search"></i> Tìm Kiếm
            </button>
        </div>
        <select name="category" class="form-select" style="max-width: 200px;" onchange="this.form.submit()">
            <option value="">Tất cả danh mục</option>
            @if(isset($categories))
                @foreach($categories as $category)
                    <option value="{{ $category->category_id }}" {{ request('category') == $category->category_id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                @endforeach
            @endif
        </select>
    </form>
</div>

<div class="row">
    @forelse ($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm hover-card" style="transition: transform 0.3s;">
                @if($product->image_url)
                    <img src="{{ Str::startsWith($product->image_url, 'http') ? $product->image_url : asset($product->image_url) }}" class="card-img-top" alt="{{ $product->product_name }}" style="height: 250px; object-fit: cover;">
                @else
                    <img src="https://via.placeholder.com/400x300?text={{ urlencode($product->product_name) }}" class="card-img-top" alt="{{ $product->product_name }}" style="height: 250px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <h5 class="card-title text-primary fw-bold">{{ $product->product_name }}</h5>
                    <p class="card-text text-muted">{{ $product->description ?? 'Chất lượng premium, bảo hành 2 năm' }}</p>
                    <p class="card-text text-danger"><strong>Giá: {{ number_format($product->price, 0, ',', '.') }} VNĐ</strong></p>
                    <a href="/product/{{ $product->product_id }}" class="btn btn-primary w-100">
                        <i class="bi bi-eye"></i> Xem Chi Tiết
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <p class="text-center text-muted">Chưa có sản phẩm nào.</p>
        </div>
    @endforelse
</div>

<style>
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
    }
</style>

@endsection
