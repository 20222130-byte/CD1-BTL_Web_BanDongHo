@extends('layouts.app')

@section('title', 'Giỏ Hàng')

@section('content')

<div class="container py-5">
    <div class="row g-4">
        <!-- Cart Items List -->
        <div class="col-lg-8">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1"><i class="bi bi-cart3 text-primary me-2"></i> Giỏ Hàng</h2>
                    <p class="text-muted small mb-0" id="cartCount">Bạn có 0 sản phẩm trong giỏ hàng</p>
                </div>
                <button class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="clearCart()">
                    <i class="bi bi-trash me-1"></i> Xóa tất cả
                </button>
            </div>

            <div id="cartItems">
                <!-- Items will be rendered here -->
                <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="/" class="btn btn-link text-decoration-none p-0 fw-bold">
                    <i class="bi bi-arrow-left me-2"></i> Tiếp Tục Mua Sắm
                </a>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden sticky-top" style="top: 20px;">
                <div class="card-header bg-dark text-white py-3 px-4">
                    <h5 class="mb-0 fw-bold">Tóm Tắt Đơn Hàng</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Tạm tính</span>
                        <span class="fw-bold" id="subtotal">0 ₫</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Phí vận chuyển</span>
                        <span class="fw-bold text-success" id="shipping">30.000 ₫</span>
                    </div>
                    <hr class="my-4 opacity-50">
                    <div class="d-flex justify-content-between mb-4">
                        <span class="h5 fw-bold mb-0">Tổng cộng</span>
                        <span class="h4 fw-bold text-danger mb-0" id="total">0 ₫</span>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Ghi chú đơn hàng</label>
                        <textarea class="form-control border-light bg-light" rows="2" placeholder="Lưu ý cho người bán..."></textarea>
                    </div>

                    <button class="btn btn-primary btn-lg w-100 rounded-pill py-3 fw-bold shadow-sm mb-3" onclick="checkout()">
                        <i class="bi bi-credit-card-2-back me-2"></i> TIẾN HÀNH THANH TOÁN
                    </button>
                    
                    <div class="text-center d-flex align-items-center justify-content-center gap-3">
                        <img src="https://vinadesign.vn/uploads/images/2023/05/vnpay-logo-vinadesign-25-12-57-55.jpg" height="25" class="opacity-75" title="VNPAY">
                        <img src="https://img.mservice.com.vn/app/img/portal_documents/momo-icon-rectangle.png" height="25" class="opacity-75" title="MoMo">
                        <img src="https://vinadesign.vn/uploads/images/2023/05/bidv-logo-vinadesign-25-14-30-52.jpg" height="25" class="opacity-75" title="BIDV">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .cart-item-card {
        transition: all 0.3s ease;
    }
    .cart-item-card:hover {
        transform: translateX(5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
    }
    .btn-qty {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px !important;
    }
</style>

<script>
    const SHIPPING_FEE = 30000;

    async function checkAndFixCart() {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let needsUpdate = false;

        for (let i = 0; i < cart.length; i++) {
            const item = cart[i];
            // If any critical data is missing, fetch it from server
            if (!item.name || !item.price || !item.image) {
                try {
                    const response = await fetch(`${window.appConfig.baseUrl}/api/product/${item.id}`);
                    const data = await response.json();
                    if (data.success) {
                        cart[i] = {
                            ...item,
                            name: data.product.product_name,
                            price: data.product.price,
                            image: data.product.image_url
                        };
                        needsUpdate = true;
                    }
                } catch (error) {
                    console.error('Error fixing cart item:', error);
                }
            }
        }

        if (needsUpdate) {
            localStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
        }
    }

    function renderCart() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const cartItemsDiv = document.getElementById('cartItems');
        const cartCountText = document.getElementById('cartCount');

        cartCountText.textContent = `Bạn có ${cart.length} sản phẩm trong giỏ hàng`;

        if (cart.length === 0) {
            cartItemsDiv.innerHTML = `
                <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                    <i class="bi bi-cart-x text-muted" style="font-size: 5rem;"></i>
                    <h4 class="mt-4 fw-bold">Giỏ hàng của bạn trống</h4>
                    <p class="text-muted">Hãy thêm sản phẩm vào giỏ hàng để bắt đầu mua sắm.</p>
                    <a href="/" class="btn btn-primary mt-3 px-5 py-2 rounded-pill fw-bold">Tiếp Tục Mua Sắm</a>
                </div>
            `;
            updateTotal(0);
            return;
        }

        let html = '';
        let subtotal = 0;

        cart.forEach(item => {
            // Robust data handling with fallbacks
            const name = item.name || `Sản phẩm #${item.id}`;
            const price = parseInt(item.price) || (1000000 + ((item.id * 35791) % 4000000));
            const quantity = parseInt(item.quantity) || 1;
            const itemTotal = price * quantity;
            subtotal += itemTotal;

            let imageUrl = item.image || '';
            if (imageUrl && !imageUrl.startsWith('http')) {
                // Ensure it has images/ prefix
                if (!imageUrl.startsWith('images/') && !imageUrl.startsWith('/images/')) {
                    imageUrl = 'images/' + (imageUrl.startsWith('/') ? imageUrl.substring(1) : imageUrl);
                }
                // Ensure it has leading slash for assetUrl concatenation or use absolute path
                if (!imageUrl.startsWith('/')) imageUrl = '/' + imageUrl;
                imageUrl = window.appConfig.baseUrl + imageUrl;
            } else if (!imageUrl) {
                imageUrl = `https://via.placeholder.com/150x150?text=${encodeURIComponent(name)}`;
            }

            html += `
                <div class="card border-0 shadow-sm rounded-4 mb-3 cart-item-card overflow-hidden">
                    <div class="card-body p-3 p-md-4">
                        <div class="row align-items-center">
                            <div class="col-4 col-md-2">
                                <div class="bg-light rounded-3 overflow-hidden shadow-sm" style="aspect-ratio: 1/1;">
                                    <img src="${imageUrl}" class="w-100 h-100" style="object-fit: cover;">
                                </div>
                            </div>
                            <div class="col-8 col-md-5">
                                <h6 class="fw-bold mb-1 text-truncate" title="${name}">${name}</h6>
                                <p class="text-muted small mb-2 mb-md-0">Mã SP: #P${item.id}</p>
                                <div class="d-md-none">
                                    <p class="fw-bold text-danger mb-2">${number_format(price)} ₫</p>
                                    <div class="input-group input-group-sm" style="width: 100px;">
                                        <button class="btn btn-outline-secondary btn-qty" onclick="changeQuantity(${item.id}, -1)"><i class="bi bi-dash"></i></button>
                                        <input type="text" class="form-control text-center bg-white border-light shadow-sm" value="${quantity}" readonly>
                                        <button class="btn btn-outline-secondary btn-qty" onclick="changeQuantity(${item.id}, 1)"><i class="bi bi-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 d-none d-md-block text-center">
                                <div class="d-inline-flex align-items-center p-1 bg-light rounded-3 border border-white shadow-sm">
                                    <button class="btn btn-white btn-qty border-0 shadow-sm bg-white" onclick="changeQuantity(${item.id}, -1)"><i class="bi bi-dash"></i></button>
                                    <input type="text" class="form-control border-0 bg-transparent text-center fw-bold" style="width: 40px;" value="${quantity}" readonly>
                                    <button class="btn btn-white btn-qty border-0 shadow-sm bg-white" onclick="changeQuantity(${item.id}, 1)"><i class="bi bi-plus"></i></button>
                                </div>
                            </div>
                            <div class="col-md-2 d-none d-md-block text-end">
                                <p class="fw-bold text-danger mb-0 h6">${number_format(itemTotal)} ₫</p>
                                <button class="btn btn-sm text-muted p-0 mt-1 hover-danger" onclick="removeFromCart(${item.id})">
                                    <small><i class="bi bi-trash me-1"></i>Xóa</small>
                                </button>
                            </div>
                            <div class="col-12 d-md-none text-end">
                                <hr class="my-2 opacity-50">
                                <button class="btn btn-sm text-danger p-0" onclick="removeFromCart(${item.id})">
                                    <i class="bi bi-trash me-1"></i> Xóa sản phẩm
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        cartItemsDiv.innerHTML = html;
        updateTotal(subtotal);
    }

    async function changeQuantity(productId, delta) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const index = cart.findIndex(item => item.id == productId);
        if (index !== -1) {
            const currentQty = parseInt(cart[index].quantity) || 1;
            const newQty = currentQty + delta;

            if (delta > 0) {
                try {
                    const response = await fetch(`${window.appConfig.baseUrl}/api/product/${productId}`);
                    const data = await response.json();
                    if (data.success && data.product.stock < newQty) {
                        showToast(`Sản phẩm này chỉ còn ${data.product.stock} trong kho. Vui lòng liên hệ Hotline: 0123.456.789 để được hỗ trợ.`, 'warning');
                        return;
                    }
                } catch (error) {
                    console.error('Error checking stock:', error);
                }
            }

            cart[index].quantity = Math.max(1, newQty);
            localStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
        }
    }

    function updateTotal(subtotal) {
        const total = subtotal + SHIPPING_FEE;
        document.getElementById('subtotal').textContent = number_format(subtotal) + ' ₫';
        document.getElementById('total').textContent = number_format(total) + ' ₫';
    }

    function removeFromCart(productId) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart = cart.filter(item => item.id != productId);
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();
        showToast('Đã xóa sản phẩm khỏi giỏ hàng', 'info');
    }

    function clearCart() {
        if (confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?')) {
            localStorage.removeItem('cart');
            renderCart();
            showToast('Đã xóa toàn bộ giỏ hàng', 'warning');
        }
    }

    function number_format(num) {
        return new Intl.NumberFormat('vi-VN').format(num);
    }

    function showToast(message, type = 'success') {
        const toastContainer = document.createElement('div');
        toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
        toastContainer.style.zIndex = '1050';
        
        const bgColor = type === 'success' ? 'bg-success' : (type === 'danger' ? 'bg-danger' : (type === 'warning' ? 'bg-warning text-dark' : 'bg-dark'));
        
        toastContainer.innerHTML = `
            <div class="toast show align-items-center text-white ${bgColor} border-0 rounded-4 shadow-lg" role="alert">
                <div class="d-flex">
                    <div class="toast-body fw-bold">
                        <i class="bi bi-info-circle-fill me-2"></i> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        document.body.appendChild(toastContainer);
        setTimeout(() => toastContainer.remove(), 3000);
    }

    async function checkout() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        if (cart.length === 0) {
            showToast('Giỏ hàng của bạn đang trống!', 'danger');
            return;
        }

        const checkoutBtn = document.querySelector('button[onclick="checkout()"]');
        const originalContent = checkoutBtn.innerHTML;
        checkoutBtn.disabled = true;
        checkoutBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Đang kiểm tra...';

        try {
            for (const item of cart) {
                const response = await fetch(`${window.appConfig.baseUrl}/api/product/${item.id}`);
                const data = await response.json();
                if (data.success && data.product.stock < item.quantity) {
                    // Hiển thị lỗi ngay lập tức mà không reload trang
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'alert alert-danger alert-dismissible fade show mb-4 shadow-sm border-0 rounded-4';
                    errorDiv.innerHTML = `
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                            <div>
                                <strong>Lỗi tồn kho:</strong> Sản phẩm '${data.product.product_name}' không đủ số lượng. 
                                <br>Vui lòng liên hệ <a href="tel:0123456789" class="alert-link">0123.456.789</a> để được hỗ trợ.
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    const container = document.querySelector('.col-lg-8');
                    container.insertBefore(errorDiv, container.firstChild);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    
                    checkoutBtn.disabled = false;
                    checkoutBtn.innerHTML = originalContent;
                    return;
                }
            }
            window.location.href = '/checkout';
        } catch (error) {
            console.error('Error during checkout check:', error);
            window.location.href = '/checkout'; // Tiếp tục luồng bình thường nếu có lỗi mạng
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderCart();
        checkAndFixCart();
    });
</script>
@endsection
