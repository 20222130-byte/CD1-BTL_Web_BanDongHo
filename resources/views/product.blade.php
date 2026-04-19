@extends('layouts.app')

@section('title', 'Chi Tiết Sản Phẩm')

@section('content')

<div class="row mb-4">
    <div class="col-md-8 offset-md-2">
        <a href="/" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <img src="https://via.placeholder.com/400x400?text=Đồng+Hồ+{{ $product_id }}" class="img-fluid rounded">
                    </div>
                    <div class="col-md-6">
                        <h2 class="text-primary mb-3">Đồng Hồ {{ $product_id }}</h2>

                        <p class="text-muted mb-4">
                            <i class="bi bi-tags"></i> Danh mục: Đồng hồ cao cấp
                        </p>

                        <div class="mb-4">
                            <h5 class="text-danger">Giá: {{ number_format(rand(1000000,5000000), 0, ',', '.') }} VNĐ</h5>
                            <p class="text-muted">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star text-warning"></i>
                                (42 đánh giá)
                            </p>
                        </div>

                        <div class="mb-4">
                            <h6>Mô Tả Sản Phẩm</h6>
                            <p class="text-muted">
                                Đồng hồ cao cấp, chất lượng premium với bảo hành 2 năm.
                                Thiết kế hiện đại, phù hợp với mọi lứa tuổi.
                                Vật liệu chất lượng cao, chống nước và chống bụi.
                            </p>
                        </div>

                        <div class="mb-4">
                            <h6>Thông Số Kỹ Thuật</h6>
                            <ul class="list-unstyled">
                                <li><strong>Chất liệu:</strong> Thép không gỉ</li>
                                <li><strong>Kính:</strong> Sapphire</li>
                                <li><strong>Chống nước:</strong> 50m</li>
                                <li><strong>Bảo hành:</strong> 2 năm chính hãng</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-primary btn-lg" onclick="addToCart({{ $product_id }})">
                                <i class="bi bi-cart-plus"></i> Thêm Vào Giỏ Hàng
                            </button>
                            <button id="wishlist-btn" class="btn btn-outline-secondary btn-lg" onclick="toggleWishlist({{ $product_id }})">
                                <i id="wishlist-icon" class="bi bi-heart"></i> Yêu Thích
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <div class="mt-5">
            <h4 class="mb-3">Sản Phẩm Liên Quan</h4>
            <div class="row">
                @for ($i = 1; $i <= 3; $i++)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm">
                            <img src="https://via.placeholder.com/300x300?text=Đồng+Hồ+{{ rand(1,9) }}" class="card-img-top">
                            <div class="card-body">
                                <h6 class="card-title">Đồng Hồ {{ rand(1,9) }}</h6>
                                <p class="card-text text-danger"><strong>{{ number_format(rand(1000000,5000000), 0, ',', '.') }} VNĐ</strong></p>
                                <a href="/product/{{ rand(1,9) }}" class="btn btn-sm btn-primary w-100">
                                    Xem Chi Tiết
                                </a>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>

<script>
    const isLoggedIn = @json(session('logged_in') === true);

    function addToCart(productId) {
        if (!isLoggedIn) {
            const pending = JSON.parse(localStorage.getItem('pendingCart')) || [];
            const existing = pending.find(item => item.id === productId);

            if (existing) {
                existing.quantity += 1;
            } else {
                pending.push({ id: productId, quantity: 1 });
            }

            localStorage.setItem('pendingCart', JSON.stringify(pending));
            window.location.href = '/login?next=/cart';
            return;
        }

        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const productIndex = cart.findIndex(item => item.id === productId);

        if (productIndex > -1) {
            cart[productIndex].quantity += 1;
        } else {
            cart.push({ id: productId, quantity: 1 });
        }

        localStorage.setItem('cart', JSON.stringify(cart));

        // Show notification
        const message = document.createElement('div');
        message.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
        message.innerHTML = `
            <i class="bi bi-check-circle"></i> Đã thêm sản phẩm vào giỏ hàng!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(message);

        setTimeout(() => {
            message.remove();
        }, 3000);
    }

    function toggleWishlist(productId) {
        const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
        const index = wishlist.indexOf(productId);
        const wishlistBtn = document.getElementById('wishlist-btn');
        const wishlistIcon = document.getElementById('wishlist-icon');

        if (index > -1) {
            wishlist.splice(index, 1);
            wishlistIcon.classList.remove('bi-heart-fill');
            wishlistIcon.classList.add('bi-heart');
            wishlistBtn.classList.remove('btn-danger');
            wishlistBtn.classList.add('btn-outline-secondary');
            message_text = 'Đã xóa khỏi danh sách yêu thích!';
        } else {
            wishlist.push(productId);
            wishlistIcon.classList.remove('bi-heart');
            wishlistIcon.classList.add('bi-heart-fill');
            wishlistBtn.classList.remove('btn-outline-secondary');
            wishlistBtn.classList.add('btn-danger');
            message_text = 'Đã thêm vào danh sách yêu thích!';
        }

        localStorage.setItem('wishlist', JSON.stringify(wishlist));

        // Show notification
        const message = document.createElement('div');
        message.className = 'alert alert-info alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
        message.innerHTML = `
            <i class="bi bi-heart-fill"></i> ${message_text}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(message);

        setTimeout(() => {
            message.remove();
        }, 3000);
    }

    // Khởi tạo trạng thái wishlist khi trang load
    document.addEventListener('DOMContentLoaded', function () {
        const productId = {{ $product_id }};
        const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
        const wishlistBtn = document.getElementById('wishlist-btn');
        const wishlistIcon = document.getElementById('wishlist-icon');

        if (wishlist.includes(productId)) {
            wishlistIcon.classList.remove('bi-heart');
            wishlistIcon.classList.add('bi-heart-fill');
            wishlistBtn.classList.remove('btn-outline-secondary');
            wishlistBtn.classList.add('btn-danger');
        }
    });
</script>

@endsection
