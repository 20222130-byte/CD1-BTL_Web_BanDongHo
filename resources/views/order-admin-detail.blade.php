@extends('layouts.app')

@section('title', 'Chi Tiết Đơn Hàng')

@section('content')

<div class="container" style="max-width: 1000px; margin-top: 2rem; margin-bottom: 3rem;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-receipt"></i> Chi Tiết Đơn Hàng #{{ $order->order_id }}</h2>
        </div>
        <a href="/order-manage" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
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

    <div class="row">
        <div class="col-lg-8">
            <!-- Thông tin đơn hàng -->
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
                                    'pending' => 'Chờ Xác Nhận',
                                    'confirmed' => 'Đã Xác Nhận',
                                    'processing' => 'Đang Xử Lý',
                                    'delivery' => 'Đang Giao',
                                    'delivered' => 'Đã Giao',
                                    'cancelled' => 'Đã Hủy',
                                    default => ucfirst($order->status)
                                };
                            @endphp
                            <p><strong>Trạng Thái Hiện Tại:</strong> <span class="badge bg-{{ $statusClass }} p-2">{{ $statusText }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin khách hàng -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-person-circle"></i> Thông Tin Khách Hàng</h5>
                </div>
                <div class="card-body">
                    <p><strong>Họ Tên:</strong> {{ $user->full_name ?? 'Không xác định' }}</p>
                    <p><strong>Email:</strong> {{ $user->email ?? 'Không xác định' }}</p>
                    <p><strong>Số Điện Thoại:</strong> {{ $user->phone ?? 'Không xác định' }}</p>
                    <p><strong>Địa Chỉ:</strong> {{ $user->address ?? 'Không xác định' }}</p>
                </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <div class="card shadow-sm">
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
        </div>

        <!-- Cột bên phải: Tóm tắt & cập nhật trạng thái -->
        <div class="col-lg-4">
            <!-- Tóm tắt đơn hàng -->
            <div class="card shadow-sm mb-4 sticky-top" style="top: 20px;">
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
                    <div class="d-flex justify-content-between mb-4">
                        <span style="font-size: 1.1rem;">Tổng Cộng:</span>
                        <strong style="font-size: 1.2rem; color: #e74c3c;">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</strong>
                    </div>

                    <!-- Cập nhật trạng thái -->
                    <form action="/order-manage/update-status/{{ $order->order_id }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="status" class="form-label"><strong>Cập Nhật Trạng Thái</strong></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">-- Chọn trạng thái --</option>
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Chờ Xác Nhận</option>
                                <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Đã Xác Nhận</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Đang Xử Lý</option>
                                <option value="delivery" {{ $order->status === 'delivery' ? 'selected' : '' }}>Đang Giao</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Đã Giao</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Đã Hủy</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-lg"></i> Cập Nhật Trạng Thái
                        </button>
                    </form>
                </div>
            </div>

            <!-- Ghi chú địa chỉ giao -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Địa Chỉ Giao Hàng</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0 text-muted">{{ $order->shipping_address ?? 'Không có thông tin' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
