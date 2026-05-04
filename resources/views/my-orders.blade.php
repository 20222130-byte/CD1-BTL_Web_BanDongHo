@extends('layouts.app')

@section('title', 'Đơn Hàng Của Tôi')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold"><i class="bi bi-bag-check text-primary me-2"></i> Đơn Hàng Của Tôi</h2>
            <p class="text-muted">Xem lịch sử và trạng thái các đơn hàng bạn đã đặt.</p>
        </div>
        <a href="/" class="btn btn-outline-primary btn-sm rounded-pill px-4">
            Tiếp tục mua sắm
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($orders->count() > 0)
        <div class="row g-4">
            @foreach($orders as $order)
                <div class="col-12">
                    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
                        <div class="card-header bg-white border-0 py-3 px-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small">Mã đơn hàng:</span>
                                    <span class="fw-bold text-primary">#{{ $order->order_id }}</span>
                                </div>
                                <div>
                                    @php
                                        $statusClass = match($order->status) {
                                            'pending' => 'bg-warning text-dark',
                                            'processing' => 'bg-info text-white',
                                            'shipped' => 'bg-primary text-white',
                                            'delivered' => 'bg-success text-white',
                                            'cancelled' => 'bg-danger text-white',
                                            default => 'bg-secondary text-white'
                                        };
                                        $statusName = match($order->status) {
                                            'pending' => 'Đang chờ xử lý',
                                            'processing' => 'Đang xử lý',
                                            'shipped' => 'Đang giao hàng',
                                            'delivered' => 'Đã giao hàng',
                                            'cancelled' => 'Đã hủy',
                                            default => $order->status
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }} rounded-pill px-3 py-2">
                                        {{ $statusName }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <p class="mb-1 text-muted small">Ngày đặt:</p>
                                    <p class="mb-0 fw-medium">{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1 text-muted small">Địa chỉ giao hàng:</p>
                                    <p class="mb-0 fw-medium text-truncate" title="{{ $order->shipping_address }}">{{ $order->shipping_address }}</p>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <p class="mb-1 text-muted small">Tổng thanh toán:</p>
                                    <h4 class="mb-0 fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light border-0 px-4 py-3">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" disabled>
                                    Xem chi tiết (Sắp ra mắt)
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card border-0 shadow-sm py-5 text-center" style="border-radius: 20px;">
            <div class="card-body">
                <i class="bi bi-cart-x text-muted" style="font-size: 5rem;"></i>
                <h4 class="mt-4 fw-bold">Bạn chưa có đơn hàng nào</h4>
                <p class="text-muted">Hãy bắt đầu mua sắm để nhận được những ưu đãi tốt nhất.</p>
                <a href="/" class="btn btn-primary mt-3 px-5 py-2 rounded-pill">Mua sắm ngay</a>
            </div>
        </div>
    @endif
</div>
@endsection
