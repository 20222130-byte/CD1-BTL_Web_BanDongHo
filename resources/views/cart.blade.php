@extends('layouts.app')

@section('title', 'Giỏ Hàng')

@section('content')

<style>
    .cart-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    .cart-item {
        border-bottom: 1px solid #dee2e6;
        padding: 15px 0;
    }
    .cart-item:last-child {
        border-bottom: none;
    }
</style>

<div class="cart-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-cart"></i> Giỏ Hàng</h2>
        <a href="/" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Tiếp tục mua hàng
        </a>
    </div>

    <div class="row">
        <!-- Cart Items -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div id="cartItems">
                        <p class="text-muted text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                            <br>
                            Giỏ hàng của bạn trống
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-md-4" id="orderSummary">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0">Tóm Tắt Đơn Hàng</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Tạm tính:</span>
                        <span id="subtotal">0 ₫</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Vận chuyển:</span>
                        <span id="shipping">30,000 ₫</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <span>Giảm giá:</span>
                        <span id="discount">0 ₫</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Tổng cộng:</strong>
                        <strong id="total" class="text-danger">0 ₫</strong>
                    </div>
                    <button class="btn btn-primary w-100 mb-2" onclick="checkout()">
                        <i class="bi bi-credit-card"></i> Thanh Toán
                    </button>
                    <button class="btn btn-outline-secondary w-100" onclick="continueShopping()">
                        Tiếp Tục Mua Sắm
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const SHIPPING_FEE = 30000;

    function renderCart() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const cartItemsDiv = document.getElementById('cartItems');
        const orderSummary = document.getElementById('orderSummary');

        if (cart.length === 0) {
            cartItemsDiv.innerHTML = `
                <p class="text-muted text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                    <br>
                    Giỏ hàng của bạn trống
                </p>
            `;
            orderSummary.style.display = 'none';
            updateTotal(0);
            return;
        }

        orderSummary.style.display = 'block';

        let html = '<div class="cart-item" style="padding-top: 0;">';
        let subtotal = 0;

        cart.forEach(item => {
            const price = Math.floor(Math.random() * (5000000 - 1000000 + 1)) + 1000000;
            const itemTotal = price * item.quantity;
            subtotal += itemTotal;

            html += `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="mb-1">Đồng Hồ #${item.id}</h6>
                        <small class="text-muted">${number_format(price)} ₫ x ${item.quantity}</small>
                    </div>
                    <div class="text-end">
                        <p class="mb-2"><strong>${number_format(itemTotal)} ₫</strong></p>
                        <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${item.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        html += '</div>';
        cartItemsDiv.innerHTML = html;
        updateTotal(subtotal);
    }

    function updateTotal(subtotal) {
        const total = subtotal + SHIPPING_FEE;
        document.getElementById('subtotal').textContent = number_format(subtotal) + ' ₫';
        document.getElementById('total').textContent = number_format(total) + ' ₫';
    }

    function removeFromCart(productId) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart = cart.filter(item => item.id !== productId);
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();

        // Show notification
        const message = document.createElement('div');
        message.className = 'alert alert-info alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
        message.innerHTML = `
            <i class="bi bi-trash"></i> Đã xóa sản phẩm khỏi giỏ hàng
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(message);
        setTimeout(() => message.remove(), 3000);
    }

    function number_format(num) {
        return new Intl.NumberFormat('vi-VN').format(num);
    }

    function checkout() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        if (cart.length === 0) {
            alert('Vui lòng thêm sản phẩm vào giỏ hàng');
            return;
        }
        window.location.href = '/checkout';
    }

    function continueShopping() {
        window.location.href = '/';
    }

    // Load cart on page
    document.addEventListener('DOMContentLoaded', renderCart);
</script>

@endsection
