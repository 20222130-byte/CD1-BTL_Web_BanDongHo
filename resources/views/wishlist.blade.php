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
    async function renderWishlist() {
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
        container.innerHTML = `
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Đang tải danh sách yêu thích...</p>
            </div>
        `;

        const products = [];
        for (const productId of wishlist) {
            try {
                const response = await fetch(`/api/product/${productId}`);
                const data = await response.json();
                if (data.success) {
                    products.push(data.product);
                }
            } catch (error) {
                console.error(`Error fetching product ${productId}:`, error);
            }
        }

        if (products.length === 0) {
            container.style.display = 'none';
            emptyMessage.style.display = 'block';
            return;
        }

        container.innerHTML = '';
        products.forEach(product => {
            let src = product.image_url;
            if (src && !src.startsWith('http')) {
                if (!src.startsWith('images/') && !src.startsWith('/images/')) {
                    src = '/images/' + src.replace(/^\//, '');
                } else {
                    src = '/' + src.replace(/^\//, '');
                }
            } else if (!src) {
                src = `https://via.placeholder.com/300x300?text=${encodeURIComponent(product.product_name)}`;
            }

            const html = `
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100 hover-card border-0 rounded-4 overflow-hidden" style="transition: all 0.3s;">
                        <div class="position-relative">
                            <img src="${src}" class="card-img-top" style="height: 250px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/300x300?text=Image+Error'">
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold text-truncate">${product.product_name}</h5>
                            <p class="card-text text-danger h5 fw-bold mb-3">
                                ${new Intl.NumberFormat('vi-VN').format(product.price)} ₫
                            </p>
                            <div class="d-flex gap-2">
                                <a href="/product/${product.product_id}" class="btn btn-primary flex-grow-1 rounded-pill">
                                    <i class="bi bi-eye me-1"></i> Xem chi tiết
                                </a>
                                <button onclick="removeFromWishlist(${product.product_id})" class="btn btn-outline-danger rounded-circle p-2" style="width: 40px; height: 40px;">
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
        const toastContainer = document.createElement('div');
        toastContainer.className = 'position-fixed bottom-0 start-50 translate-middle-x p-3';
        toastContainer.style.zIndex = '2000';
        toastContainer.innerHTML = `
            <div class="toast show align-items-center text-white bg-dark border-0 rounded-pill shadow-lg" role="alert">
                <div class="d-flex px-3 py-2">
                    <div class="toast-body fw-medium">
                        <i class="bi bi-heart-fill text-danger me-2"></i> Đã xóa khỏi danh sách yêu thích!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        document.body.appendChild(toastContainer);
        setTimeout(() => toastContainer.remove(), 3000);
    }

    document.addEventListener('DOMContentLoaded', renderWishlist);
</script>

<style>
    .hover-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
    }
    .card-img-top {
        transition: transform 0.5s ease;
    }
    .hover-card:hover .card-img-top {
        transform: scale(1.05);
    }
</style>
@endsection
