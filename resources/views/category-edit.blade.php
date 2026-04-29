@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                    <h5 class="mb-0 py-2 fw-bold">
                        <i class="bi bi-pencil-square me-2"></i> Chỉnh Sửa Danh Mục
                    </h5>
                </div>
                <div class="card-body">
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

                    <form method="POST" action="/category-update/{{ $category->category_id }}">
                        @csrf
                        <div class="mb-3">
                            <label for="category_name" class="form-label">Tên Danh Mục *</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" value="{{ $category->category_name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Nhóm Danh Mục (Mô Tả)</label>
                            <input type="text" class="form-control" id="description" name="description" value="{{ $category->description }}" list="groupOptions" placeholder="Chọn hoặc nhập nhóm mới...">
                            <datalist id="groupOptions">
                                @foreach($groups as $group)
                                    <option value="{{ $group }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Cập Nhật
                            </button>
                            <a href="/category-manager" class="btn btn-secondary">
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
