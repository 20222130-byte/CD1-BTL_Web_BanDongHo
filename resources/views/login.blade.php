@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-lg">
            <div class="card-body p-5">
                <h3 class="text-center mb-4 text-primary fw-bold">Đăng Nhập</h3>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="/login">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên tài khoản hoặc Email</label>
                        <input type="text" name="username_or_email" class="form-control @error('username_or_email') is-invalid @enderror" 
                               value="{{ old('username_or_email') }}" placeholder="Nhập tên tài khoản hoặc email">
                        @error('username_or_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Mật khẩu</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Nhập mật khẩu">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="btn btn-primary w-100 py-2 fw-bold mb-3">Đăng nhập</button>

                    <p class="text-center">
                        Chưa có tài khoản? <a href="/register" class="text-success fw-bold">Đăng ký ngay</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection