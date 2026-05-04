@extends('layouts.app')

@section('title', 'Đơn Hàng Của Tôi')

@section('content')

<div class="container" style="max-width: 1100px; margin-top: 2rem; margin-bottom: 3rem;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-bag-check"></i> Đơn Hàng Của Tôi</h2>
            <p class="text-muted mb-0">Danh sách các đơn hàng mà bạn đã đặt.</p>
        </div>
        <div>
            <a href="/profile" class="btn btn-outline-info">
                <i class="bi bi-person-circle"></i> Hồ Sơ
            </a>
            <a href="/" class="btn btn-outline-secondary">
                <i class="bi bi-shop"></i> Tiếp Tục Mua Sắm
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            @if($orders->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Mã Đơn Hàng</th>
                                <th>Số Sản Phẩm</th>
                                <th>Tổng Tiền</th>
                                <th>Trạng Thái</th>
                                <th>Ngày Đặt</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <strong>#{{ $order->order_id }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $order->item_count }} sản phẩm</span>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($order->total_amount, 0, ',', '.') }} ₫</strong>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($order->status) {
                                                'pending' => 'warning',
                                                'confirmed' => 'info',
                                                'processing' => 'info',
                                                'delivery' => 'primary',
                                                'delivered' => 'success',
                                                'cancelled' => 'danger',
                                                default => 'secondary'
                                            };
                                            $statusText = match($order->status) {
                                                'pending' => 'Chờ xác nhận',
                                                'confirmed' => 'Đã xác nhận',
                                                'processing' => 'Đang xử lý',
                                                'delivery' => 'Đang giao',
                                                'delivered' => 'Đã giao',
                                                'cancelled' => 'Đã hủy',
                                                default => $order->status
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="/order-detail/{{ $order->order_id }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="bi bi-eye"></i> Chi Tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-3 mb-0">Bạn chưa có đơn hàng nào.</p>
                    <a href="/" class="btn btn-primary mt-3">
                        <i class="bi bi-shop"></i> Tiếp Tục Mua Sắm
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
