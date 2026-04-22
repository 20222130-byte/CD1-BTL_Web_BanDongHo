@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>S?a Danh M?c</h1>
        <a href="/category-manager" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay L?i
        </a>
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

    <div class="card">
        <div class="card-body">
            <form method="POST" action="/category-update/{{ $category->category_id }}">
                @csrf
                <div class="mb-3">
                    <label for="category_name" class="form-label">Tên Danh M?c</label>
                    <input type="text" class="form-control" id="category_name" name="category_name" value="{{ $category->category_name }}" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Mô T?</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ $category->description }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">C?p Nh?t</button>
            </form>
        </div>
    </div>
</div>

@endsection

