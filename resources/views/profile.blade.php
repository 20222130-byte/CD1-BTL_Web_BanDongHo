@extends('layouts.app')

@section('title', 'Hồ Sơ Cá Nhân')

@section('content')
<<<<<<< HEAD
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header bg-primary text-white p-4 text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;">
                    <div class="mb-3">
                        <i class="bi bi-person-circle" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="mb-0">{{ session('full_name') }}</h3>
                    <p class="mb-0 opacity-75">{{ session('email') }}</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="/profile-update" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Họ và Tên</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-person text-primary"></i></span>
                                    <input type="text" name="full_name" class="form-control bg-light border-0" value="{{ old('full_name', $user->full_name) }}" placeholder="Nhập họ tên">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Số Điện Thoại</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-telephone text-primary"></i></span>
                                    <input type="text" name="phone" class="form-control bg-light border-0" value="{{ old('phone', $user->phone) }}" placeholder="Nhập số điện thoại">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Địa Chỉ</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-geo-alt text-primary"></i></span>
                                    <input type="text" name="address" class="form-control bg-light border-0" value="{{ old('address', $user->address) }}" placeholder="Nhập địa chỉ">
                                </div>
                            </div>
                            
                            <hr class="my-4 opacity-50">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-shield-lock me-2"></i> Đổi Mật Khẩu</h5>
                            <p class="text-muted small">Bỏ trống nếu không muốn thay đổi mật khẩu</p>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Mật Khẩu Mới</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-key text-primary"></i></span>
                                    <input type="password" name="password" class="form-control bg-light border-0" placeholder="••••••••">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Xác Nhận Mật Khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-key-fill text-primary"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control bg-light border-0" placeholder="••••••••">
                                </div>
                            </div>

                            <div class="col-12 mt-5">
                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                                    Lưu Thay Đổi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
=======

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
>>>>>>> a1863cdf6d77a08bf48a952dd39765b3b3355e29
            </div>
        </div>
    </div>
</div>

<<<<<<< HEAD
<style>
    .form-control:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.1);
        border-color: #667eea !important;
    }
    .input-group-text {
        border-right: none;
    }
    .form-control {
        border-left: none;
    }
</style>
=======
>>>>>>> a1863cdf6d77a08bf48a952dd39765b3b3355e29
@endsection
