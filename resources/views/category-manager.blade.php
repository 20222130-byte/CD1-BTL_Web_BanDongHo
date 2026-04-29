@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Quản Lý Danh Mục</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-circle"></i> Thêm Danh Mục
        </button>
    </div>

    {{-- Messages --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $groupedCategories = collect($categories)->groupBy('description');
    @endphp

    <div class="row">
        @forelse($groupedCategories as $groupName => $cats)
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold text-primary">
                        <i class="bi bi-diamond-fill text-info me-2" style="font-size: 0.8rem;"></i>
                        {{ $loop->iteration }}. {{ $groupName ?: 'Khác' }}
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($cats as $category)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 border-0 pb-2 pt-2">
                            <div class="fs-6 text-dark">
                                <span class="me-2 text-muted">•</span> {{ $category->category_name }}
                            </div>
                            <div>
                                <a href="/category-edit/{{ $category->category_id }}" class="btn btn-sm btn-outline-warning rounded-circle me-1" title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="/category-delete/{{ $category->category_id }}" class="btn btn-sm btn-outline-danger rounded-circle" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center py-4">Chưa có danh mục nào.</div>
        </div>
        @endforelse
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm Danh Mục Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/category-store">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Tên Danh Mục</label>
                        <input type="text" class="form-control" id="category_name" name="category_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Nhóm Danh Mục (Mô Tả)</label>
                        <input type="text" class="form-control" id="description" name="description" list="groupOptions" placeholder="Chọn hoặc nhập nhóm mới...">
                        <datalist id="groupOptions">
                            @foreach($groups as $group)
                                <option value="{{ $group }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
