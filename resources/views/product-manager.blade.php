@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Quản Lý Sản Phẩm</h1>
        <a href="/product-create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm Sản Phẩm
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Tổng Sản Phẩm</h5>
                    <h2>{{ $stats['total_products'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Tổng Kho</h5>
                    <h2>{{ number_format($stats['total_stock']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Giá TB</h5>
                    <h2>{{ number_format($stats['avg_price']) }} ₫</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Hết Hàng</h5>
                    <h2>{{ $stats['low_stock'] }}</h2>
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

    {{-- Products Table --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên Sản Phẩm</th>
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
