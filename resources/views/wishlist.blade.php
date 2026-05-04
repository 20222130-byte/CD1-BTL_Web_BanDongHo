@extends('layouts.app')

@section('title', 'Danh Sách Yêu Thích')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-heart-fill text-danger"></i> Danh Sách Yêu Thích</h1>
        <a href="/" class="btn btn-outline-secondary">
            <i class="bi bi-shop"></i> Tiếp Tục Mua Sắm
        </a>
    </div>

    <div id="wishlist-empty" class="text-center py-5">
        <i class="bi bi-heart" style="font-size: 4rem; color: #ccc;"></i>
        <h4 class="mt-3 text-muted">Danh sách yêu thích trống</h4>
        <p class="text-muted mb-4">Hãy thêm những sản phẩm bạn yêu thích</p>
        <a href="/" class="btn btn-primary">
            <i class="bi bi-shop"></i> Tiếp Tục Mua Sắm
        </a>
    </div>

    <div id="wishlist-items" class="row" style="display: none;">
        <!-- Items sẽ được thêm bằng JavaScript -->
    </div>
</div>

<script>
    function renderWishlist() {
        const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
        const container = document.getElementById('wishlist-items');
        const emptyMessage = document.getElementById('wishlist-empty');

        if (wishlist.length === 0) {
            container.style.display = 'none';
            emptyMessage.style.display = 'block';
            return;
        }

        container.style.display = 'flex';
        emptyMessage.style.display = 'none';
        container.innerHTML = '';

        wishlist.forEach(productId => {
            const html = `
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100 hover-card" style="transition: all 0.3s;">
                        <img src="https://via.placeholder.com/300x300?text=Đồng+Hồ+${productId}" class="card-img-top" style="height: 250px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">Đồng Hồ ${productId}</h5>
                            <p class="card-text text-danger">
                                <strong>${(1000000 + (productId * 35791) % 4000000).toLocaleString('vi-VN')} ₫</strong>
                            </p>
                            <div class="d-flex gap-2">
                                <a href="/product/${productId}" class="btn btn-sm btn-primary flex-grow-1">
                                    <i class="bi bi-eye"></i> Xem
                                </a>
                                <button onclick="removeFromWishlist(${productId})" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-heart-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.innerHTML += html;
        });
    }

    function removeFromWishlist(productId) {
        const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
        const index = wishlist.indexOf(productId);

        if (index > -1) {
            wishlist.splice(index, 1);
        }

        localStorage.setItem('wishlist', JSON.stringify(wishlist));
        renderWishlist();

        // Show notification
        const message = document.createElement('div');
        message.className = 'alert alert-info alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
        message.innerHTML = `
            <i class="bi bi-check-circle"></i> Đã xóa khỏi danh sách yêu thích!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(message);

        setTimeout(() => {
            message.remove();
        }, 3000);
    }

    document.addEventListener('DOMContentLoaded', renderWishlist);
</script>

<style>
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endsection
