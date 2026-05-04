@extends('layouts.app')

@section('title', 'Hồ Sơ Cá Nhân')

@section('content')

<div class="container" style="max-width: 700px; margin-top: 2rem; margin-bottom: 3rem;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-person-circle"></i> Hồ Sơ Cá Nhân</h2>
        <a href="/" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Lỗi xác thực:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-info-circle"></i> Thông Tin Cá Nhân</h5>
        </div>
        <div class="card-body">
            <form action="/profile/update" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="username" class="form-label">Tên Đăng Nhập</label>
                            <input type="text" class="form-control" id="username" value="{{ $user->username }}" disabled>
                            <small class="text-muted">Không thể thay đổi tên đăng nhập</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Họ Tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   value="{{ old('full_name', $user->full_name ?? '') }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email', $user->email ?? '') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số Điện Thoại <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="{{ old('phone', $user->phone ?? '') }}" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Địa Chỉ <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address', $user->address ?? '') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="created_at" class="form-label">Ngày Tạo Tài Khoản</label>
                    <input type="text" class="form-control" id="created_at" 
                           value="{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') }}" disabled>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Cập Nhật Thông Tin
                    </button>
                    <a href="/" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-bag"></i> Liên Kết Nhanh</h5>
        </div>
        <div class="card-body">
            <div class="d-grid gap-2 d-md-flex">
                <a href="/my-orders" class="btn btn-outline-primary">
                    <i class="bi bi-bag-check"></i> Xem Đơn Hàng Của Tôi
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
