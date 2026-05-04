@extends('layouts.app')

@section('title', 'Quản Lý Đơn Hàng')

@section('content')

<div class="container" style="max-width: 1200px; margin-top: 2rem; margin-bottom: 3rem;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-cart-check"></i> Quản Lý Đơn Hàng</h2>
            <p class="text-muted mb-0">Quản lý và cập nhật trạng thái các đơn hàng của khách hàng.</p>
        </div>
        <a href="/dashboard" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại Dashboard
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

    <div class="card shadow-sm">
        <div class="card-body">
            @if($orders->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Mã Đơn Hàng</th>
                                <th>Khách Hàng</th>
                                <th>Email</th>
                                <th>Tổng Tiền</th>
                                <th>Trạng Thái</th>
                                <th>Ngày Đặt</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td><strong>#{{ $order->order_id }}</strong></td>
                                    <td>{{ $order->full_name ?? 'Khách' }}</td>
                                    <td>{{ $order->email ?? '-' }}</td>
                                    <td><strong>{{ number_format($order->total_amount, 0, ',', '.') }} ₫</strong></td>
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
                                                'pending' => 'Chờ Xác Nhận',
                                                'confirmed' => 'Đã Xác Nhận',
                                                'processing' => 'Đang Xử Lý',
                                                'delivery' => 'Đang Giao',
                                                'delivered' => 'Đã Giao',
                                                'cancelled' => 'Đã Hủy',
                                                default => ucfirst($order->status)
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/order-admin-detail/{{ $order->order_id }}" class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $order->order_id }}" title="Cập nhật trạng thái">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal cập nhật trạng thái -->
                                <div class="modal fade" id="updateStatusModal{{ $order->order_id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Cập Nhật Trạng Thái Đơn Hàng #{{ $order->order_id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="/order-manage/update-status/{{ $order->order_id }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="status{{ $order->order_id }}" class="form-label">Trạng Thái <span class="text-danger">*</span></label>
                                                        <select class="form-select" id="status{{ $order->order_id }}" name="status" required>
                                                            <option value="">-- Chọn trạng thái --</option>
                                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Chờ Xác Nhận</option>
                                                            <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Đã Xác Nhận</option>
                                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Đang Xử Lý</option>
                                                            <option value="delivery" {{ $order->status === 'delivery' ? 'selected' : '' }}>Đang Giao</option>
                                                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Đã Giao</option>
                                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Đã Hủy</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                                                    <button type="submit" class="btn btn-primary">Cập Nhật</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-3 mb-0">Chưa có đơn hàng nào.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
