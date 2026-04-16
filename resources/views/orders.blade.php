@extends('layouts.app')

@section('title', 'Quản Lý Đơn Hàng')

@section('content')

<div class="container" style="max-width: 1100px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-cart-check"></i> Quản Lý Đơn Hàng</h2>
            <p class="text-muted mb-0">Danh sách đơn hàng đã được tạo và thanh toán.</p>
        </div>
        <a href="/dashboard" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại Dashboard
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if($orders->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Đơn Hàng</th>
                                <th>Khách Hàng</th>
                                <th>Email</th>
                                <th>Tổng Tiền</th>
                                <th>Trạng Thái</th>
                                <th>Ngày</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td><strong>#{{ $order->order_id }}</strong></td>
                                    <td>{{ $order->full_name ?? 'Khách' }}</td>
                                    <td>{{ $order->email ?? '-' }}</td>
                                    <td>{{ number_format($order->total_amount, 0, ',', '.') }} ₫</td>
                                    <td class="text-capitalize"><span class="badge bg-info">{{ $order->status }}</span></td>
                                    <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <p class="text-muted mb-0">Chưa có đơn hàng nào.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
