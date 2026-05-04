@extends('layouts.app')

@section('title', 'Thanh Toán')

@section('content')

<div class="container" style="max-width: 900px;">
    <div class="row">
        <div class="col-md-8">
            <h2 class="mb-4"><i class="bi bi-credit-card"></i> Thanh Toán</h2>

            <form method="POST" action="/process-payment" id="checkoutForm">
                @csrf

                <!-- Thông Tin Khách Hàng -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light border-0">
                        <h5 class="mb-0"><i class="bi bi-person"></i> Thông Tin Khách Hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Họ Và Tên</label>
                            <input type="text" name="full_name" class="form-control" value="{{ session('full_name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ session('email') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số Điện Thoại</label>
                            <input type="tel" name="phone" class="form-control" placeholder="0xxxxxxxxx" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Địa Chỉ Giao Hàng</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Địa chỉ nhà bạn..." required></textarea>
                        </div>
                    </div>
                </div>

                <!-- Phương Thức Thanh Toán -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light border-0">
                        <h5 class="mb-0"><i class="bi bi-wallet2"></i> Phương Thức Thanh Toán</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="creditcard" value="creditcard" checked>
                                <label class="form-check-label" for="creditcard">
                                    <i class="bi bi-credit-card"></i> <strong>Thẻ Tín Dụng / Ghi Nợ</strong>
                                    <small class="d-block text-muted">Visa, Mastercard, JCB</small>
                                </label>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="bank" value="bank">
                                <label class="form-check-label" for="bank">
                                    <i class="bi bi-building"></i> <strong>Chuyển Khoản Ngân Hàng</strong>
                                    <small class="d-block text-muted">Chuyển tiền trực tiếp từ ngân hàng</small>
                                </label>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="ewallet" value="ewallet">
                                <label class="form-check-label" for="ewallet">
                                    <i class="bi bi-wallet"></i> <strong>Ví Điện Tử</strong>
                                    <small class="d-block text-muted">Momo, ZaloPay, PayPal</small>
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod">
                                <label class="form-check-label" for="cod">
                                    <i class="bi bi-cash-coin"></i> <strong>Thanh Toán Khi Nhận Hàng (COD)</strong>
                                    <small class="d-block text-muted">Thanh toán tiền mặt khi nhận hàng</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chi Tiết Thanh Toán -->
                <div id="paymentDetails" class="card shadow-sm mb-4" style="display: none;">
                    <div class="card-header bg-light border-0">
                        <h5 class="mb-0">Nhập Thông Tin Thanh Toán</h5>
                    </div>
                    <div class="card-body" id="paymentForm">
                        <!-- Nội dung sẽ được điền bằng JavaScript -->
                    </div>
                </div>

                <!-- Nút Thanh Toán -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-lg"></i> Xác Nhận Thanh Toán
                    </button>
                    <a href="/cart" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-arrow-left"></i> Quay Lại Giỏ Hàng
                    </a>
                </div>
            </form>
        </div>

        <!-- Tóm Tắt Đơn Hàng -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0">Tóm Tắt Đơn Hàng</h5>
                </div>
                <div class="card-body">
                    <div id="orderItems"></div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính:</span>
                        <span id="subtotal">0 ₫</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <span>Vận chuyển:</span>
                        <span>30,000 ₫</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <strong>Tổng cộng:</strong>
                        <strong id="total" class="text-danger">0 ₫</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const SHIPPING_FEE = 30000;

    function number_format(num) {
        return new Intl.NumberFormat('vi-VN').format(num);
    }

    function renderOrderSummary() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const orderItemsDiv = document.getElementById('orderItems');
        let html = '';
        let subtotal = 0;

        cart.forEach(item => {
            const price = parseInt(item.price) || (1000000 + ((item.id * 35791) % 4000000));
            const itemTotal = price * item.quantity;
            subtotal += itemTotal;

            html += `
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between">
                        <span><strong>Đồng Hồ #${item.id}</strong></span>
                        <span>x${item.quantity}</span>
                    </div>
                    <small class="text-muted">${number_format(price)} ₫</small>
                    <div class="text-end mt-1">
                        <strong>${number_format(itemTotal)} ₫</strong>
                    </div>
                </div>
            `;
        });

        orderItemsDiv.innerHTML = html;
        updateTotal(subtotal);
    }

    function updateTotal(subtotal) {
        const total = subtotal + SHIPPING_FEE;
        document.getElementById('subtotal').textContent = number_format(subtotal) + ' ₫';
        document.getElementById('total').textContent = number_format(total) + ' ₫';
    }

    function updatePaymentForm() {
        const method = document.querySelector('input[name="payment_method"]:checked').value;
        const paymentDetails = document.getElementById('paymentDetails');
        const paymentForm = document.getElementById('paymentForm');

        if (method === 'creditcard') {
            paymentDetails.style.display = 'block';
            paymentForm.innerHTML = `
                <div class="mb-3">
                    <label class="form-label">Số Thẻ</label>
                    <input type="text" class="form-control" placeholder="1234 5678 9012 3456" maxlength="19">
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">MM/YY</label>
                        <input type="text" class="form-control" placeholder="12/25" maxlength="5">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">CVV</label>
                        <input type="text" class="form-control" placeholder="123" maxlength="4">
                    </div>
                </div>
                <div class="mb-0">
                    <label class="form-label">Tên Chủ Thẻ</label>
                    <input type="text" class="form-control" placeholder="NGUYEN VAN A">
                </div>
            `;
        } else if (method === 'bank') {
            paymentDetails.style.display = 'block';
            paymentForm.innerHTML = `
                <div class="alert alert-info mb-3">
                    <p><strong>Thông Tin Chuyển Khoản:</strong></p>
                    <p>Ngân Hàng: <strong>Vietcombank</strong></p>
                    <p>Số TK: <strong>0412345678</strong></p>
                    <p>Chủ TK: <strong>Shop Đồng Hồ</strong></p>
                    <p>Nội Dung: <strong>Thanh toán đơn hàng</strong></p>
                </div>
                <p class="text-muted small">Hệ thống sẽ xác nhận chuyển khoản tự động trong vòng 5 phút</p>
            `;
        } else if (method === 'ewallet') {
            paymentDetails.style.display = 'block';
            paymentForm.innerHTML = `
                <div class="mb-3">
                    <label class="form-label">Chọn Ví Điện Tử</label>
                    <select class="form-select">
                        <option selected>Chọn ví...</option>
                        <option>Momo</option>
                        <option>ZaloPay</option>
                        <option>PayPal</option>
                    </select>
                </div>
                <p class="text-muted small">Bạn sẽ được chuyển hướng đến ứng dụng để hoàn tất thanh toán</p>
            `;
        } else {
            paymentDetails.style.display = 'none';
        }
    }

    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', updatePaymentForm);
    });

    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Lưu dữ liệu giỏ hàng vào form
        const cart = localStorage.getItem('cart');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'cart';
        input.value = cart;
        this.appendChild(input);

        // Submit form
        this.submit();
    });

    // Load on page
    renderOrderSummary();
</script>

@endsection
