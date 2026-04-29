@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Quản Lý Sản Phẩm</h1>
        <div>
            <a href="/category-manager" class="btn btn-secondary me-2">
                <i class="bi bi-tags"></i> Quản Lý Danh Mục
            </a>
            <a href="/product-create" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm Sản Phẩm
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white border-0 shadow-sm" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                    <i class="bi bi-box-seam fs-1 mb-2" style="opacity: 0.8;"></i>
                    <h5 class="card-title fw-normal">Tổng Sản Phẩm</h5>
                    <h2 class="fw-bold mb-0">{{ $stats['total_products'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white border-0 shadow-sm" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                    <i class="bi bi-layers fs-1 mb-2" style="opacity: 0.8;"></i>
                    <h5 class="card-title fw-normal">Tổng Kho</h5>
                    <h2 class="fw-bold mb-0">{{ number_format($stats['total_stock']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white border-0 shadow-sm" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                    <i class="bi bi-tags fs-1 mb-2" style="opacity: 0.8;"></i>
                    <h5 class="card-title fw-normal">Giá TB</h5>
                    <h2 class="fw-bold mb-0">{{ number_format($stats['avg_price']) }} ₫</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white border-0 shadow-sm" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                    <i class="bi bi-exclamation-triangle fs-1 mb-2" style="opacity: 0.8;"></i>
                    <h5 class="card-title fw-normal">Hết Hàng</h5>
                    <h2 class="fw-bold mb-0">{{ $stats['low_stock'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Messages --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Search Form --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="/product-manager" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">Tất cả danh mục</option>
                        @if(isset($categories))
                            @php
                                $groupedCats = collect($categories)->groupBy('description');
                            @endphp
                            @foreach($groupedCats as $group => $cats)
                                <optgroup label="{{ $group ?: 'Khác' }}">
                                    @foreach($cats as $category)
                                        <option value="{{ $category->category_id }}" {{ request('category') == $category->category_id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tìm
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="/product-manager" class="btn btn-secondary w-100">
                        <i class="bi bi-x-circle"></i> Xóa
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Products Table --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Danh Mục</th>
                        <th>Giá</th>
                        <th>Kho</th>
                        <th>Mô Tả</th>
                        <th>Ngày Tạo</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td><strong>#{{ $product->product_id }}</strong></td>
                            <td>{{ $product->product_name }}</td>
                            <td>
                                @if($product->category_id)
                                    @php
                                        $categoryName = $categories->where('category_id', $product->category_id)->first()->category_name ?? 'N/A';
                                    @endphp
                                    {{ $categoryName }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ number_format($product->price) }} ₫</td>
                            <td>
                                @if($product->stock > 20)
                                    <span class="badge bg-success">{{ $product->stock }}</span>
                                @elseif($product->stock > 10)
                                    <span class="badge bg-warning">{{ $product->stock }}</span>
                                @else
                                    <span class="badge bg-danger">{{ $product->stock }}</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($product->description, 50) ?? 'N/A' }}</td>
                            <td>{{ $product->created_at ? \Carbon\Carbon::parse($product->created_at)->format('d/m/Y') : 'N/A' }}</td>
                            <td>
                                <a href="/product-edit/{{ $product->product_id }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="/product-delete/{{ $product->product_id }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox"></i> Chưa có sản phẩm nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

