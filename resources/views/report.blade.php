@extends('layouts.app')

@section('title', 'Báo Cáo Thống Kê')

@section('content')

<style>
    .report-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
    }
    .metric-card {
        border-radius: 8px;
        border-left: 4px solid;
        transition: all 0.3s ease;
    }
    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
    }
    .metric-value {
        font-size: 2.5rem;
        font-weight: bold;
    }
    .metric-label {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 5px;
    }
    .chart-container {
        position: relative;
        height: 300px;
        margin-bottom: 20px;
    }
    .badge-lg {
        font-size: 1.1rem;
        padding: 8px 16px;
    }

    /* Tối ưu hóa cho in ấn (PDF chuyên nghiệp) */
    @media print {
        body {
            background-color: white !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .navbar, footer, .btn, .d-flex.gap-2, .report-header a {
            display: none !important; /* Ẩn các thành phần điều hướng */
        }
        .report-header {
            background: #667eea !important;
            -webkit-print-color-adjust: exact;
            color: white !important;
            border-radius: 0;
            margin-bottom: 20px;
        }
        .card {
            border: 1px solid #eee !important;
            box-shadow: none !important;
            break-inside: avoid; /* Tránh ngắt trang giữa chừng card */
        }
        .metric-card {
            border-left: 4px solid !important;
            -webkit-print-color-adjust: exact;
        }
        .metric-value {
            font-size: 2rem !important;
        }
        .chart-container {
            height: 250px !important;
        }
        .container {
            width: 100% !important;
            max-width: none !important;
            padding: 0 !important;
        }
    }
</style>

<div class="report-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-2"><i class="bi bi-file-earmark-bar-graph"></i> Báo Cáo Thống Kê Kinh Doanh</h2>
            <p class="mb-0">Tổng hợp dữ liệu hệ thống | Cập nhật lúc: <strong>{{ \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}</strong></p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="exportCSV()" class="btn btn-success">
                <i class="bi bi-file-earmark-excel"></i> Xuất file Excel
            </button>
            <button onclick="exportToPDF()" class="btn btn-danger">
                <i class="bi bi-file-pdf"></i> Xuất file PDF
            </button>
            <a href="/dashboard" class="btn btn-light">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm metric-card" style="border-left-color: #667eea;">
            <div class="card-body">
                <p class="metric-label"><i class="bi bi-people"></i> Tổng Người Dùng</p>
                <p class="metric-value text-primary">{{ $summary['total_users'] }}</p>
                <small class="text-muted">Khách hàng đã đăng ký</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm metric-card" style="border-left-color: #48bb78;">
            <div class="card-body">
                <p class="metric-label"><i class="bi bi-box"></i> Tổng Sản Phẩm</p>
                <p class="metric-value text-success">{{ $summary['total_products'] }}</p>
                <small class="text-muted">SKU trong hệ thống</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm metric-card" style="border-left-color: #f6ad55;">
            <div class="card-body">
                <p class="metric-label"><i class="bi bi-cart"></i> Tổng Đơn Hàng</p>
                <p class="metric-value text-warning">{{ $summary['total_orders'] }}</p>
                <small class="text-muted">Đơn hàng tất cả</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm metric-card" style="border-left-color: #f25287;">
            <div class="card-body">
                <p class="metric-label"><i class="bi bi-cash-coin"></i> Tổng Doanh Thu</p>
                <p class="metric-value text-danger">{{ number_format($summary['total_revenue'], 0, ',', '.') }}₫</p>
                <small class="text-muted">Toàn bộ doanh thu</small>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Metrics -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <p class="metric-label mb-2"><i class="bi bi-percent"></i> Tỷ Lệ Chuyển Đổi</p>
                @php
                    $conversion = $summary['total_users'] > 0
                        ? number_format(($summary['total_orders'] / $summary['total_users']) * 100, 2)
                        : 0;
                @endphp
                <p class="metric-value text-info">{{ $conversion }}%</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <p class="metric-label mb-2"><i class="bi bi-cart-check"></i> Giá Trị Đơn Bình Quân</p>
                @php
                    $avgOrder = $summary['total_orders'] > 0
                        ? number_format($summary['total_revenue'] / $summary['total_orders'], 0, ',', '.')
                        : 0;
                @endphp
                <p class="metric-value text-success">{{ $avgOrder }}₫</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <p class="metric-label mb-2"><i class="bi bi-wallet2"></i> Thanh Toán Thành Công</p>
                @php
                    $paidPayments = 0;
                    foreach($paymentStatuses as $p) {
                        if($p->payment_status === 'paid') $paidPayments = $p->count;
                    }
                    $rate = $summary['total_orders'] > 0
                        ? number_format(($paidPayments / $summary['total_orders']) * 100, 2)
                        : 0;
                @endphp
                <p class="metric-value text-warning">{{ $rate }}%</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-light border-0">
                <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Tình Trạng Đơn Hàng</h5>
            </div>
            <div class="card-body">
                @if($orderStatuses->count())
                    <div class="chart-container">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                    <div class="row g-2">
                        @foreach($orderStatuses as $status)
                            <div class="col-6">
                                <div class="p-2 bg-light rounded text-center">
                                    <p class="mb-1 text-muted text-capitalize small">{{ $status->status }}</p>
                                    <h5 class="mb-0">{{ $status->count }}</h5>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">Không có dữ liệu</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-light border-0">
                <h5 class="mb-0"><i class="bi bi-wallet2"></i> Tình Trạng Thanh Toán</h5>
            </div>
            <div class="card-body">
                @if($paymentStatuses->count())
                    <div class="chart-container">
                        <canvas id="paymentStatusChart"></canvas>
                    </div>
                    <div class="row g-2">
                        @foreach($paymentStatuses as $payment)
                            <div class="col-6">
                                <div class="p-2 bg-light rounded text-center">
                                    <p class="mb-1 text-muted text-capitalize small">{{ $payment->payment_status }}</p>
                                    <h5 class="mb-0">{{ $payment->count }}</h5>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">Không có dữ liệu</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Product & Order Tables -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-light border-0">
                <h5 class="mb-0"><i class="bi bi-trophy"></i> Top 5 Sản Phẩm Bán Chạy</h5>
            </div>
            <div class="card-body">
                @if($topProducts->count())
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản Phẩm</th>
                                    <th class="text-end">Số Lượng Bán</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $i => $product)
                                    <tr>
                                        <td>
                                            <span class="badge badge-lg" style="background: linear-gradient(135deg, #667eea, #764ba2);">{{ $i + 1 }}</span>
                                            {{ $product->product_name }}
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-success">{{ $product->sold_quantity }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Chưa có dữ liệu</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-light border-0">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> 5 Đơn Hàng Gần Nhất</h5>
            </div>
            <div class="card-body">
                @if($recentOrders->count())
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Khách Hàng</th>
                                    <th>Ngày</th>
                                    <th class="text-end">Tổng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td><strong>#{{ $order->order_id }}</strong></td>
                                        <td>
                                            <small>{{ $order->full_name ?? $order->username ?? 'Khách vãng lai' }}</small>
                                        </td>
                                        <td>
                                            <small>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</small>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-info">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Chưa có đơn hàng</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function exportCSV() {
        let csvContent = "data:text/csv;charset=utf-8,\uFEFF"; // Add BOM for Excel UTF-8
        
        // 1. Summary Section
        csvContent += "BAO CAO THONG KE KINH DOANH\n";
        csvContent += "Ngay xuat: " + "{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}\n\n";
        
        csvContent += "Metric,Gia tri\n";
        csvContent += "Tong nguoi dung," + "{{ $summary['total_users'] }}\n";
        csvContent += "Tong san pham," + "{{ $summary['total_products'] }}\n";
        csvContent += "Tong don hang," + "{{ $summary['total_orders'] }}\n";
        csvContent += "Tong doanh thu," + "{{ $summary['total_revenue'] }}\n\n";
        
        // 2. Top Products
        csvContent += "TOP 5 SAN PHAM BAN CHAY\n";
        csvContent += "San pham,So luong ban\n";
        @foreach($topProducts as $product)
            csvContent += "{{ $product->product_name }},{{ $product->sold_quantity }}\n";
        @endforeach
        csvContent += "\n";
        
        // 3. Recent Orders
        csvContent += "5 DON HANG GAN NHAT\n";
        csvContent += "Ma don,Khach hang,Ngay dat,Tong tien\n";
        @foreach($recentOrders as $order)
            csvContent += "#{{ $order->order_id }},{{ $order->full_name ?? $order->username }},{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }},{{ $order->total_amount }}\n";
        @endforeach
        
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "Thong-Ke-Kinh-Doanh-{{ \Carbon\Carbon::now()->format('d-m-Y') }}.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function exportToPDF() {
        window.print();
    }

    // Order Status Chart
    const orderCtx = document.getElementById('orderStatusChart');
    if (orderCtx) {
        new Chart(orderCtx, {
            type: 'doughnut',
            data: {
                labels: @json($orderStatuses->pluck('status')),
                datasets: [{
                    data: @json($orderStatuses->pluck('count')),
                    backgroundColor: [
                        '#667eea',
                        '#48bb78',
                        '#f6ad55',
                        '#f25287',
                        '#a8a8a8'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Payment Status Chart
    const paymentCtx = document.getElementById('paymentStatusChart');
    if (paymentCtx) {
        new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: @json($paymentStatuses->pluck('payment_status')),
                datasets: [{
                    data: @json($paymentStatuses->pluck('count')),
                    backgroundColor: [
                        '#48bb78',
                        '#f6ad55',
                        '#f25287'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
</script>

@endsection
