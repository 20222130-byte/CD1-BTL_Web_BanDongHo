@extends('layouts.app')

@section('title', 'Chi Tiết Đơn Hàng')

@section('content')

<div class="container" style="max-width: 900px; margin-top: 2rem; margin-bottom: 3rem;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-receipt"></i> Chi Tiết Đơn Hàng #{{ $order->order_id }}</h2>
        </div>
        <a href="/my-orders" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-package"></i> Thông Tin Đơn Hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Mã Đơn Hàng:</strong> #{{ $order->order_id }}</p>
                            <p><strong>Ngày Đặt:</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
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
                            <p><strong>Trạng Thái:</strong> <span class="badge bg-{{ $statusClass }} p-2">{{ $statusText }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-box-seam"></i> Danh Sách Sản Phẩm</h5>
                </div>
                <div class="card-body">
                    @if($orderDetails->count())
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sản Phẩm</th>
                                        <th class="text-end">Giá</th>
                                        <th class="text-end">Số Lượng</th>
                                        <th class="text-end">Thành Tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $subtotal = 0; @endphp
                                    @foreach($orderDetails as $detail)
                                        @php
                                            $itemTotal = $detail->price * $detail->quantity;
                                            $subtotal += $itemTotal;
                                        @endphp
                                        <tr>
                                            <td>{{ $detail->product_name ?? 'Sản phẩm #' . $detail->product_id }}</td>
                                            <td class="text-end">{{ number_format($detail->price, 0, ',', '.') }} ₫</td>
                                            <td class="text-end">{{ $detail->quantity }}</td>
                                            <td class="text-end"><strong>{{ number_format($itemTotal, 0, ',', '.') }} ₫</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Không có sản phẩm nào trong đơn hàng này.</p>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Địa Chỉ Giao Hàng</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $order->shipping_address ?? 'Không có thông tin' }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calculator"></i> Tóm Tắt Đơn Hàng</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm Tính:</span>
                        <strong>{{ number_format($order->total_amount, 0, ',', '.') }} ₫</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phí Vận Chuyển:</span>
                        <strong>0 ₫</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span style="font-size: 1.1rem;">Tổng Cộng:</span>
                        <strong style="font-size: 1.2rem; color: #e74c3c;">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</strong>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <p class="mb-2"><strong><i class="bi bi-info-circle"></i> Trạng Thái Đơn Hàng</strong></p>
                        <p class="text-muted small">
                            @php
                                $statusMessage = match($order->status) {
                                    'pending' => 'Đơn hàng của bạn đang chờ xác nhận từ shop.',
                                    'confirmed' => 'Đơn hàng đã được xác nhận và đang chuẩn bị hàng.',
                                    'processing' => 'Shop đang xử lý và chuẩn bị gửi hàng cho bạn.',
                                    'delivery' => 'Đơn hàng đang trên đường giao đến bạn.',
                                    'delivered' => 'Đơn hàng đã được giao thành công!',
                                    'cancelled' => 'Đơn hàng đã bị hủy.',
                                    default => 'Trạng thái không xác định.'
                                };
                            @endphp
                            {{ $statusMessage }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
