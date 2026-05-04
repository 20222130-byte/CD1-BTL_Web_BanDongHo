@extends('layouts.app')

@section('title', 'Quản Lý Đơn Hàng')

@section('content')

<div class="container py-4">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-cart-check text-primary me-2"></i> Quản Lý Đơn Hàng</h4>
                <p class="text-muted small mb-0">Theo dõi và cập nhật trạng thái đơn hàng của khách hàng</p>
            </div>
            <a href="/dashboard" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left"></i> Dashboard
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Mã Đơn</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Khách Hàng</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Tổng Tiền</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Trạng Thái</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted text-center">Hành Động</th>
                        <th class="pe-4 py-3 text-uppercase small fw-bold text-muted text-end">Ngày Đặt</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-primary">#{{ $order->order_id }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $order->full_name ?? 'Khách lẻ' }}</span>
                                    <span class="text-muted small">{{ $order->email }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</span>
                            </td>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'bg-warning text-dark',
                                        'confirmed' => 'bg-info text-white',
                                        'processing' => 'bg-info text-white',
                                        'delivery' => 'bg-primary text-white',
                                        'delivered' => 'bg-success text-white',
                                        'cancelled' => 'bg-danger text-white'
                                    ][$order->status] ?? 'bg-secondary text-white';
                                    
                                    $statusText = [
                                        'pending' => 'Chờ xác nhận',
                                        'confirmed' => 'Đã xác nhận',
                                        'processing' => 'Đang xử lý',
                                        'delivery' => 'Đang giao',
                                        'delivered' => 'Đã giao',
                                        'cancelled' => 'Đã hủy'
                                    ][$order->status] ?? $order->status;
                                @endphp
                                <span class="badge {{ $statusClass }} rounded-pill px-3 py-2" style="font-size: 0.75rem;">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="/order-admin-detail/{{ $order->order_id }}" class="btn btn-sm btn-outline-primary rounded-circle p-2" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-info rounded-circle p-2 edit-status-btn" title="Chỉnh sửa trạng thái" data-order-id="{{ $order->order_id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                                <div class="mt-2 status-select-container d-none" id="status-container-{{ $order->order_id }}">
                                    <select name="status" class="form-select form-select-sm rounded-pill shadow-sm status-select" data-order-id="{{ $order->order_id }}" style="width: 140px;">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                        <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                        <option value="delivery" {{ $order->status == 'delivery' ? 'selected' : '' }}>Đang giao</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã giao</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                    </select>
                                </div>
                            </td>
                            <td class="pe-4 text-end">
                                <span class="text-muted small">{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted opacity-25"></i>
                                <p class="text-muted mt-3 mb-0">Chưa có đơn hàng nào được ghi nhận.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle status select
    document.querySelectorAll('.edit-status-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            const container = document.getElementById('status-container-' + orderId);
            container.classList.toggle('d-none');
        });
    });

    // AJAX status update
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function() {
            const orderId = this.getAttribute('data-order-id');
            const status = this.value;
            
            fetch('/order-update-status/' + orderId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the badge text and class in the table
                    location.reload(); // Re-render or manually update badge
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi cập nhật trạng thái');
            });
        });
    });
});
</script>

@endsection
