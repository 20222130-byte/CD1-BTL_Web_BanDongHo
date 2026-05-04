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
                                        'processing' => 'bg-info text-white',
                                        'shipped' => 'bg-primary text-white',
                                        'delivered' => 'bg-success text-white',
                                        'cancelled' => 'bg-danger text-white'
                                    ][$order->status] ?? 'bg-secondary text-white';
                                    
                                    $statusText = [
                                        'pending' => 'Chờ xử lý',
                                        'processing' => 'Đang xử lý',
                                        'shipped' => 'Đang giao',
                                        'delivered' => 'Đã giao',
                                        'cancelled' => 'Đã hủy'
                                    ][$order->status] ?? $order->status;
                                @endphp
                                <span class="badge {{ $statusClass }} rounded-pill px-3 py-2" style="font-size: 0.75rem;">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="text-center">
                                <form action="/order-update-status/{{ $order->order_id }}" method="POST" class="d-flex justify-content-center gap-2">
                                    @csrf
                                    <select name="status" class="form-select form-select-sm rounded-pill shadow-sm" style="width: 140px;" onchange="this.form.submit()">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đang giao</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã giao</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                    </select>
                                </form>
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

@endsection
