@extends('layouts.app')

@section('title', 'Đơn Hàng Thành Công')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <div style="font-size: 4rem; color: #28a745; margin-bottom: 20px;">
                        <i class="bi bi-check-circle"></i>
                    </div>

                    <h2 class="mb-3">Thanh Toán Thành Công!</h2>
                    <p class="text-muted mb-4">Cảm ơn bạn đã mua sắm tại Shop Đồng Hồ</p>

                    <div class="alert alert-info mb-4">
                        <h5>Mã Đơn Hàng: <strong>#{{ $order_id }}</strong></h5>
                        <p class="mb-0 text-muted">Vui lòng giữ mã này để theo dõi đơn hàng</p>
                    </div>

                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6>Thông Tin Giao Hàng</h6>
                            <p class="mb-1"><strong>Người Nhận:</strong> {{ session('full_name') }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ session('email') }}</p>
                            <p class="mb-0"><strong>Trạng Thái:</strong> <span class="badge bg-info">Đang Xử Lý</span></p>
                        </div>
                    </div>

                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6>Các Bước Tiếp Theo</h6>
                            <ol class="text-start mb-0">
                                <li>Chúng tôi sẽ kiểm tra và xác nhận đơn hàng trong vòng 2 giờ</li>
                                <li>Sản phẩm sẽ được đóng gói và chuẩn bị giao hàng</li>
                                <li>Bạn sẽ nhận được thông báo khi sản phẩm được giao</li>
                                <li>Xác nhận nhận hàng sau khi kiểm tra sản phẩm</li>
                            </ol>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="text-muted mb-3">Bạn sẽ nhận được email xác nhận tại {{ session('email') }}</p>
                        <p class="text-muted"><i class="bi bi-info-circle"></i> Nếu không tìm thấy email, vui lòng kiểm tra thư mục Spam</p>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="/" class="btn btn-primary btn-lg">
                            <i class="bi bi-shop"></i> Tiếp Tục Mua Sắm
                        </a>
                        <a href="/order-detail/{{ $order_id }}" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-receipt"></i> Xem Chi Tiết Đơn Hàng
                        </a>

                    </div>
                </div>
            </div>

            <div class="alert alert-success mt-4">
                <i class="bi bi-telephone"></i> <strong>Hỗ Trợ Khách Hàng:</strong> 1900 1234 | Email: support@dongho.com
            </div>
        </div>
    </div>
</div>

<script>
    // Xóa giỏ hàng sau khi thanh toán thành công
    localStorage.removeItem('cart');
</script>

@endsection
