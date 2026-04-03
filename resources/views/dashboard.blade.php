@extends('layouts.app')

@section('title', 'Bảng Điều Khiển Admin')

@section('content')

<style>
    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
    }
    .stat-card {
        border-left: 4px solid #667eea;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .stat-card.blue {
        border-left-color: #667eea;
    }
    .stat-card.green {
        border-left-color: #48bb78;
    }
    .stat-card.orange {
        border-left-color: #f6ad55;
    }
    .menu-item {
        display: inline-block;
        margin: 10px;
    }
    .menu-btn {
        min-width: 200px;
        padding: 15px;
        text-align: center;
    }
</style>

<!-- Header Dashboard -->
<div class="dashboard-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-2"><i class="bi bi-speedometer2"></i> Bảng Điều Khiển Quản Trị Admin</h2>
            <p class="mb-0">Chào mừng, <strong>{{ session('full_name') }}</strong></p>
        </div>
        <div>
            <a href="/" class="btn btn-light">
                <i class="bi bi-house"></i> Trang Chủ
            </a>
        </div>
    </div>
</div>

<!-- Thống Kê -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm stat-card blue">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Tổng Người Dùng</p>
                        <h3 class="text-primary mb-0">{{ DB::table('users')->count() }}</h3>
                    </div>
                    <div>
                        <i class="bi bi-people-fill" style="font-size: 2rem; color: #667eea; opacity: 0.2;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card shadow-sm stat-card green">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Tổng Sản Phẩm</p>
                        <h3 class="text-success mb-0">{{ DB::table('products')->count() ?? 0 }}</h3>
                    </div>
                    <div>
                        <i class="bi bi-shop" style="font-size: 2rem; color: #48bb78; opacity: 0.2;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card shadow-sm stat-card orange">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Tổng Đơn Hàng</p>
                        <h3 class="text-warning mb-0">{{ DB::table('orders')->count() ?? 0 }}</h3>
                    </div>
                    <div>
                        <i class="bi bi-cart-check" style="font-size: 2rem; color: #f6ad55; opacity: 0.2;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Khách Hàng -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light border-0">
                <h5 class="mb-0"><i class="bi bi-person-badge"></i> Khách Hàng Gần Đây</h5>
            </div>
            <div class="card-body">
                @php
                    $users = DB::table('users')->where('role', '!=', 'admin')->orderBy('created_at', 'desc')->limit(5)->get();
                @endphp

                @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên Tài Khoản</th>
                                    <th>Email</th>
                                    <th>Họ Và Tên</th>
                                    <th>Số Điện Thoại</th>
                                    <th>Ngày Tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>#{{ $user->user_id }}</td>
                                        <td><strong>{{ $user->username }}</strong></td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->full_name }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Chưa có khách hàng nào</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Menu Quản Trị -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light border-0">
                <h5 class="mb-0"><i class="bi bi-list-check"></i> Các Chức Năng Quản Lý</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-lg-4 mb-3">
                        <a href="#" class="btn btn-outline-primary menu-btn w-100" disabled>
                            <i class="bi bi-people"></i><br>
                            <small>Quản Lý Người Dùng</small>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <a href="#" class="btn btn-outline-success menu-btn w-100" disabled>
                            <i class="bi bi-box"></i><br>
                            <small>Quản Lý Sản Phẩm</small>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <a href="#" class="btn btn-outline-warning menu-btn w-100" disabled>
                            <i class="bi bi-cart"></i><br>
                            <small>Quản Lý Đơn Hàng</small>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <a href="#" class="btn btn-outline-info menu-btn w-100" disabled>
                            <i class="bi bi-graph-up"></i><br>
                            <small>Báo Cáo Thống Kê</small>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <a href="#" class="btn btn-outline-secondary menu-btn w-100" disabled>
                            <i class="bi bi-gear"></i><br>
                            <small>Cài Đặt Hệ Thống</small>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <a href="/logout" class="btn btn-outline-danger menu-btn w-100">
                            <i class="bi bi-box-arrow-right"></i><br>
                            <small>Đăng Xuất</small>
                        </a>
                    </div>
                </div>
                <div class="alert alert-info mt-3 mb-0">
                    <i class="bi bi-info-circle"></i> <strong>Lưu ý:</strong> Các chức năng khác đang được phát triển. Hiện tại bạn có thể xem dữ liệu khách hàng trên trang này.
                </div>
            </div>
        </div>
    </div>
</div>

@endsection