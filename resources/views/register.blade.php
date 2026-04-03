@extends('layouts.app')

@section('title', 'Đăng ký')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-lg">
            <div class="card-body p-5">
                <h3 class="text-center mb-4 text-primary fw-bold">Tạo Tài Khoản Mới</h3>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="/register">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tên tài khoản</label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" 
                                   value="{{ old('username') }}" placeholder="Nhập tên tài khoản">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" placeholder="Nhập email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Mật khẩu (ít nhất 8 ký tự)</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Nhập mật khẩu">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Xác nhận mật khẩu</label>
                            <input type="password" name="confirm_password" class="form-control @error('confirm_password') is-invalid @enderror" 
                                   placeholder="Nhập lại mật khẩu">
                            @error('confirm_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Họ và tên</label>
                        <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" 
                               value="{{ old('full_name') }}" placeholder="Nhập họ và tên">
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Số điện thoại</label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone') }}" placeholder="Nhập số điện thoại">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Địa chỉ</label>
                            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                                   value="{{ old('address') }}" placeholder="Nhập địa chỉ">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button class="btn btn-success w-100 py-2 fw-bold mb-3">Đăng ký</button>

                    <p class="text-center">
                        Đã có tài khoản? <a href="/login" class="text-primary fw-bold">Đăng nhập tại đây</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection