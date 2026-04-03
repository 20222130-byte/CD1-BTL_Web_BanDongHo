<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Shop Đồng Hồ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .container {
            flex: 1;
        }
        .navbar {
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: #fff !important;
        }
        footer {
            margin-top: auto;
            border-top: 1px solid #dee2e6;
        }
        .user-info {
            color: white;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<!-- HEADER/NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">
            <i class="bi bi-shop"></i> Shop Đồng Hồ
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @if (session('logged_in'))
                    <li class="nav-item me-3">
                        <div class="user-info">
                            <i class="bi bi-person-circle"></i>
                            {{ session('full_name') }} 
                            @if (session('role') === 'admin')
                                <span class="badge bg-warning">Admin</span>
                            @endif
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="/logout" class="btn btn-outline-light">
                            <i class="bi bi-box-arrow-right"></i> Đăng Xuất
                        </a>
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