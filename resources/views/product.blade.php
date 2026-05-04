@extends('layouts.app')

@section('title', $product->product_name)

@section('content')

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-10 offset-md-1">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="bi bi-house-door"></i> Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->product_name }}</li>
                </ol>
            </nav>

            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-md-6 bg-light d-flex align-items-center justify-content-center p-3">
                            @if($product->image_url)
                                <img src="{{ Str::startsWith($product->image_url, 'http') ? $product->image_url : asset($product->image_url) }}" class="img-fluid rounded-4 shadow-sm" style="max-height: 500px; object-fit: contain;">
                            @else
                                <img src="https://via.placeholder.com/600x600?text={{ urlencode($product->product_name) }}" class="img-fluid rounded-4 shadow-sm">
                            @endif
                        </div>
                        <div class="col-md-6 p-4 p-lg-5">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge bg-primary bg-opacity-10 text-primary mb-2 px-3 py-2 rounded-pill">Đồng hồ cao cấp</span>
                                    <h1 class="fw-bold h2 mb-1">{{ $product->product_name }}</h1>
                                    <p class="text-muted small">Mã sản phẩm: #{{ $product->product_id }}</p>
                                </div>
                                <button id="wishlist-btn" class="btn btn-outline-danger border-0 rounded-circle p-2" onclick="toggleWishlist({{ $product->product_id }})">
                                    <i id="wishlist-icon" class="bi bi-heart fs-4"></i>
                                </button>
                            </div>

                            <div class="d-flex align-items-center gap-3 mb-4">
                                <h3 class="text-danger fw-bold mb-0">{{ number_format($product->price, 0, ',', '.') }} VNĐ</h3>
                                <div class="d-flex flex-column">
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 mb-1">
                                        <i class="bi bi-check2-circle me-1"></i> Còn hàng
                                    </span>
                                    <small class="text-muted"><i class="bi bi-box-seam me-1"></i> Số lượng trong kho: <span class="fw-bold text-primary">{{ $product->stock }}</span></small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold mb-2"><i class="bi bi-info-circle me-2"></i> Mô Tả Sản Phẩm</h6>
                                <p class="text-muted small lh-lg">
                                    {{ $product->description ?: 'Sản phẩm đồng hồ cao cấp với thiết kế sang trọng, lịch lãm, phù hợp cho mọi dịp. Chất liệu cao cấp đảm bảo độ bền và tính thẩm mỹ vượt trội theo thời gian.' }}
                                </p>
                            </div>

                            <div class="mb-4 p-3 bg-light rounded-3 border border-white shadow-sm">
                                <h6 class="fw-bold mb-3">Chọn số lượng</h6>
                                <div class="input-group" style="width: 140px;">
                                    <button class="btn btn-white border-light shadow-sm" type="button" onclick="updateQty(-1)">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="text" id="product-qty" class="form-control text-center border-light shadow-sm bg-white" value="1" readonly>
                                    <button class="btn btn-white border-light shadow-sm" type="button" onclick="updateQty(1)">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-grid gap-3 pt-2">
                                <button class="btn btn-primary btn-lg shadow-sm rounded-pill py-3 fw-bold" onclick="addToCart({{ json_encode($product) }})">
                                    <i class="bi bi-cart-plus me-2"></i> Thêm Vào Giỏ Hàng
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if($relatedProducts->count() > 0)
            <div class="mt-5 pt-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0">Sản Phẩm Liên Quan</h4>
                    <a href="/" class="text-primary text-decoration-none small fw-bold">Xem tất cả <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="row g-4">
                    @foreach($relatedProducts as $related)
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-card" style="transition: all 0.3s ease;">
                                <div class="position-relative">
                                    @if($related->image_url)
                                        <img src="{{ Str::startsWith($related->image_url, 'http') ? $related->image_url : asset($related->image_url) }}" class="card-img-top" style="height: 250px; object-fit: cover;">
                                    @else
                                        <img src="https://via.placeholder.com/400x400?text={{ urlencode($related->product_name) }}" class="card-img-top" style="height: 250px; object-fit: cover;">
                                    @endif
                                </div>
                                <div class="card-body p-4">
                                    <h6 class="fw-bold mb-2 text-truncate">{{ $related->product_name }}</h6>
                                    <p class="text-danger fw-bold mb-3 h5">{{ number_format($related->price, 0, ',', '.') }} ₫</p>
                                    <a href="/product/{{ $related->product_id }}" class="btn btn-outline-primary w-100 rounded-pill py-2">
                                        Xem Chi Tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .breadcrumb-item + .breadcrumb-item::before {
        content: "\F285";
        font-family: "bootstrap-icons";
        font-size: 0.7rem;
        vertical-align: middle;
    }
    .hover-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .btn-white {
        background-color: #fff;
        color: #6c757d;
    }
    .btn-white:hover {
        background-color: #f8f9fa;
        color: #212529;
    }
</style>

<script>
    const isLoggedIn = @json(session('logged_in') === true);

    function updateQty(delta) {
        const qtyInput = document.getElementById('product-qty');
        let currentQty = parseInt(qtyInput.value) || 1;
        qtyInput.value = Math.max(1, currentQty + delta);
    }

    function addToCart(product) {
        const quantity = parseInt(document.getElementById('product-qty').value) || 1;
        const productId = product.product_id;

        const cartItem = {
            id: productId,
            name: product.product_name,
            price: product.price,
            image: product.image_url,
            quantity: quantity
        };

        if (!isLoggedIn) {
            const pending = JSON.parse(localStorage.getItem('pendingCart')) || [];
            const existingIndex = pending.findIndex(item => item.id === productId);

            if (existingIndex > -1) {
                pending[existingIndex].quantity += quantity;
            } else {
                pending.push(cartItem);
            }

            localStorage.setItem('pendingCart', JSON.stringify(pending));
            window.location.href = '/login?next=/cart';
            return;
        }

        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const productIndex = cart.findIndex(item => item.id === productId);

        if (productIndex > -1) {
            cart[productIndex].quantity += quantity;
        } else {
            cart.push(cartItem);
        }

        localStorage.setItem('cart', JSON.stringify(cart));

        // Show notification
        const toastContainer = document.createElement('div');
        toastContainer.className = 'position-fixed top-0 start-50 translate-middle-x p-3';
        toastContainer.style.zIndex = '2000';
        toastContainer.innerHTML = `
            <div class="toast show align-items-center text-white bg-success border-0 rounded-pill shadow-lg" role="alert">
                <div class="d-flex px-3 py-2">
                    <div class="toast-body fw-medium">
                        <i class="bi bi-check-circle-fill me-2"></i> Đã thêm ${quantity} sản phẩm vào giỏ hàng!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        document.body.appendChild(toastContainer);
        setTimeout(() => toastContainer.remove(), 3000);
    }

    function toggleWishlist(productId) {
        const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
        const index = wishlist.indexOf(productId);
        const wishlistBtn = document.getElementById('wishlist-btn');
        const wishlistIcon = document.getElementById('wishlist-icon');
        let message_text = '';

        if (index > -1) {
            wishlist.splice(index, 1);
            wishlistIcon.classList.remove('bi-heart-fill', 'text-danger');
            wishlistIcon.classList.add('bi-heart');
            message_text = 'Đã xóa khỏi danh sách yêu thích!';
        } else {
            wishlist.push(productId);
            wishlistIcon.classList.remove('bi-heart');
            wishlistIcon.classList.add('bi-heart-fill', 'text-danger');
            message_text = 'Đã thêm vào danh sách yêu thích!';
        }

        localStorage.setItem('wishlist', JSON.stringify(wishlist));

        // Show notification
        const toastContainer = document.createElement('div');
        toastContainer.className = 'position-fixed bottom-0 start-50 translate-middle-x p-3';
        toastContainer.style.zIndex = '2000';
        toastContainer.innerHTML = `
            <div class="toast show align-items-center text-white bg-dark border-0 rounded-pill shadow-lg" role="alert">
                <div class="d-flex px-3 py-2">
                    <div class="toast-body fw-medium">
                        <i class="bi bi-heart-fill text-danger me-2"></i> ${message_text}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        document.body.appendChild(toastContainer);
        setTimeout(() => toastContainer.remove(), 3000);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const productId = {{ $product->product_id }};
        const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
        const wishlistIcon = document.getElementById('wishlist-icon');

        if (wishlist.includes(productId)) {
            wishlistIcon.classList.remove('bi-heart');
            wishlistIcon.classList.add('bi-heart-fill', 'text-danger');
        }
    });
</script>

@endsection
