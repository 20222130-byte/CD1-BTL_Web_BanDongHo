@extends('layouts.app')

@section('title', 'Hồ Sơ Cá Nhân')

@section('content')
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

                    <div class="mt-4 text-center">
                        <a href="/my-orders" class="btn btn-link text-decoration-none">
                            <i class="bi bi-bag-check me-1"></i> Xem đơn hàng của tôi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
@endsection
