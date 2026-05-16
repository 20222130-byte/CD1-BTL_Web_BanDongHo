@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                    <h5 class="mb-0 py-2 fw-bold">
                        <i class="bi bi-plus-circle me-2"></i> Thêm Sản Phẩm Mới
                    </h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Lỗi Validation:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="/product-store" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="product_name" class="form-label">Tên Sản Phẩm *</label>
                            <input type="text" class="form-control @error('product_name') is-invalid @enderror"
                                   id="product_name" name="product_name" value="{{ old('product_name') }}" required>
                            @error('product_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô Tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá (₫) *</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price" value="{{ old('price') }}" min="0" step="1000" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Kho (Số lượng) *</label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                           id="stock" name="stock" value="{{ old('stock') }}" min="0" required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Hình Ảnh Sản Phẩm</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block fw-bold">Danh Mục Sản Phẩm</label>
                            <p class="text-muted small mb-3">Chọn một danh mục phù hợp cho từng nhóm bên dưới:</p>
                            @php
                                $groupedCats = collect($categories)->groupBy('description');
                            @endphp
                            <div class="row">
                                @foreach($groupedCats as $group => $cats)
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-primary small fw-bold">{{ $group ?: 'Khác' }}</label>
                                        <select class="form-select @error('category_ids') is-invalid @enderror" name="category_ids[]">
                                            <option value="">-- Chọn {{ $group ?: 'danh mục' }} --</option>
                                            @foreach($cats as $category)
                                                <option value="{{ $category->category_id }}" {{ in_array($category->category_id, old('category_ids', [])) ? 'selected' : '' }}>
                                                    {{ $category->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                            </div>
                            @error('category_ids')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Thêm Sản Phẩm
                            </button>
                            <a href="/product-manager" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Quay Lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
