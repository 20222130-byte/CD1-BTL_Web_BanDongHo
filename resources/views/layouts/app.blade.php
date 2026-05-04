<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Shop Đồng Hồ')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f4f7f6;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            flex: 1;
        }
        .navbar {
            background: linear-gradient(135deg, #1e293b, #0f172a) !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 1rem 0;
            z-index: 1050;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.6rem;
            color: #fff !important;
            letter-spacing: 0.5px;
        }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.04);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
        }
        .card-header {
            border-bottom: none;
            padding: 1.25rem 1.5rem;
        }
        .btn {
            border-radius: 10px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 0.6rem 1rem;
            border: 1px solid #e2e8f0;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
            border-color: #a0aec0;
        }
        .table-hover tbody tr {
            transition: background-color 0.2s ease;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f5f9;
        }
        footer {
            margin-top: auto;
            border-top: none;
            background: linear-gradient(135deg, #0f172a, #1e293b) !important;
            box-shadow: 0 -4px 15px rgba(0,0,0,0.1);
        }
        .user-info {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
        }
        .badge {
            border-radius: 6px;
            padding: 0.4em 0.6em;
        }
    </style>
    <script>
        window.appConfig = {
            baseUrl: "{{ url('/') }}",
            assetUrl: "{{ asset('') }}"
        };
    </script>
</head>
<body>

<!-- HEADER/NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="/">
            <img src="{{ asset('images/logo.png') }}" alt="CD1 Logo" height="45" class="rounded-1">
            <span class="d-none d-sm-inline">Shop Đồng Hồ</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @if (session('logged_in'))
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link dropdown-toggle text-white d-flex align-items-center gap-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5"></i>
                            <span>{{ session('full_name') }}</span>
                            @if (session('role') === 'admin')
                                <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">Admin</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2" aria-labelledby="userDropdown" style="border-radius: 12px;">
                            <li><a class="dropdown-item py-2" href="/my-orders"><i class="bi bi-bag me-2"></i> Đơn hàng của tôi</a></li>
                            <li><a class="dropdown-item py-2" href="/profile"><i class="bi bi-person me-2"></i> Hồ sơ cá nhân</a></li>
                            @if (session('role') === 'admin')
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2" href="/dashboard"><i class="bi bi-speedometer2 me-2"></i> Dashboard Admin</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger" href="/logout"><i class="bi bi-box-arrow-right me-2"></i> Đăng xuất</a></li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item me-2">
                        <a href="/login" class="btn btn-outline-light">
                            <i class="bi bi-box-arrow-in-right"></i> Đăng Nhập
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/register" class="btn btn-warning">
                            <i class="bi bi-person-plus"></i> Đăng Ký
                        </a>
                    </li>
                @endif

                <li class="nav-item me-2">
                    <a href="@if(session('logged_in'))/cart @else /login?next=/cart @endif" class="btn btn-outline-light">
                        <i class="bi bi-cart"></i> Giỏ Hàng
                    </a>
                </li>
                <li class="nav-item me-2">
                    <a href="/wishlist" class="btn btn-outline-light">
                        <i class="bi bi-heart"></i> Yêu Thích
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="container mt-4">
    @yield('content')
</div>

<!-- FOOTER -->
<footer class="bg-dark text-white text-center mt-5 p-4">
    <div class="container">
        <p class="mb-2"><strong>© 2026 Shop Đồng Hồ</strong></p>
        <p class="mb-0">Dự án Laravel - Bán Đồng Hồ Online</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
