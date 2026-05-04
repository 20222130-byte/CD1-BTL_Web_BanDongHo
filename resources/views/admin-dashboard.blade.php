@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <div class="container">
        <!-- Header -->
        <div class="text-white mb-5">
            <h1 class="display-4 fw-bold mb-2">
                <i class="bi bi-speedometer2"></i> Bảng Điều Khiển Admin
            </h1>
            <p class="lead">Chào mừng, <strong>{{ session('full_name') }}</strong> 👋</p>
            <p class="text-white-50">Quản lý toàn bộ hệ thống từ đây</p>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-5">
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small">Tổng Người Dùng</p>
                                <h3 class="fw-bold text-primary">{{ $summary['total_users'] }}</h3>
                            </div>
                            <i class="bi bi-people-fill text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small">Tổng Sản Phẩm</p>
                                <h3 class="fw-bold text-info">{{ $summary['total_products'] }}</h3>
                            </div>
                            <i class="bi bi-box-seam text-info" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small">Tổng Đơn Hàng</p>
                                <h3 class="fw-bold text-warning">{{ $summary['total_orders'] }}</h3>
                            </div>
                            <i class="bi bi-receipt text-warning" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small">Doanh Thu</p>
                                <h3 class="fw-bold text-success">{{ number_format($summary['total_revenue']) }} ₫</h3>
                            </div>
                            <i class="bi bi-cash-coin text-success" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Management Features -->
        <div class="mb-4">
            <h3 class="text-white fw-bold mb-4">
                <i class="bi bi-gear-fill"></i> Các Chức Năng Quản Lý
            </h3>

            <div class="row">
                <!-- Quản Lý Người Dùng -->
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100 hover-card" style="cursor: pointer; transition: all 0.3s;">
                        <div class="card-body text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-people-fill" style="font-size: 3rem; color: #667eea;"></i>
                            </div>
                            <h5 class="card-title fw-bold">Quản Lý Người Dùng</h5>
                            <p class="card-text text-muted small">Xem, thêm, sửa, xóa tài khoản người dùng</p>
                            <a href="/user-manager" class="btn btn-primary mt-3 w-100">Truy Cập</a>
                        </div>
                    </div>
                </div>

                <!-- Quản Lý Sản Phẩm -->
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100 hover-card" style="cursor: pointer; transition: all 0.3s;">
                        <div class="card-body text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-box-seam" style="font-size: 3rem; color: #48bb78;"></i>
                            </div>
                            <h5 class="card-title fw-bold">Quản Lý Sản Phẩm</h5>
                            <p class="card-text text-muted small">Quản lý kho hàng, giá, mô tả sản phẩm</p>
                            <a href="/product-manager" class="btn btn-success mt-3 w-100">Truy Cập</a>
                        </div>
                    </div>
                </div>

                <!-- Quản Lý Đơn Hàng -->
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100 hover-card" style="cursor: pointer; transition: all 0.3s;">
                        <div class="card-body text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-receipt" style="font-size: 3rem; color: #f6ad55;"></i>
                            </div>
                            <h5 class="card-title fw-bold">Quản Lý Đơn Hàng</h5>
                            <p class="card-text text-muted small">Xem tất cả đơn hàng và cập nhật trạng thái</p>
                            <a href="/order-manage" class="btn btn-warning mt-3 w-100">Truy Cập</a>
                        </div>
                    </div>
                </div>

                <!-- Báo Cáo Thống Kê -->
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100 hover-card" style="cursor: pointer; transition: all 0.3s;">
                        <div class="card-body text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-graph-up" style="font-size: 3rem; color: #4299e1;"></i>
                            </div>
                            <h5 class="card-title fw-bold">Báo Cáo Thống Kê</h5>
                            <p class="card-text text-muted small">Xem biểu đồ doanh số, bán chạy nhất</p>
                            <a href="/report" class="btn btn-info mt-3 w-100">Truy Cập</a>
                        </div>
                    </div>
                </div>

                <!-- Cài Đặt Hệ Thống -->
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100 hover-card" style="cursor: pointer; transition: all 0.3s;">
                        <div class="card-body text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-gear" style="font-size: 3rem; color: #8b5cf6;"></i>
                            </div>
                            <h5 class="card-title fw-bold">Cài Đặt Hệ Thống</h5>
                            <p class="card-text text-muted small">Cấu hình và tùy chỉnh hệ thống</p>
                            <a href="/system-settings" class="btn btn-secondary mt-3 w-100" disabled>Sắp Ra Mắt</a>
                        </div>
                    </div>
                </div>

                <!-- Đăng Xuất -->
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100 hover-card" style="cursor: pointer; transition: all 0.3s;">
                        <div class="card-body text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-box-arrow-right" style="font-size: 3rem; color: #f56565;"></i>
                            </div>
                            <h5 class="card-title fw-bold">Đăng Xuất</h5>
                            <p class="card-text text-muted small">Thoát khỏi tài khoản quản trị</p>
                            <a href="/logout" class="btn btn-danger mt-3 w-100">Đăng Xuất</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1) !important;
    }
</style>
@endsection
