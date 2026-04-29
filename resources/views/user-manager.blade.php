@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Quản Lý Người Dùng</h1>
        <a href="/user-create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm Người Dùng
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white border-0 shadow-sm" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                    <i class="bi bi-people fs-1 mb-2" style="opacity: 0.8;"></i>
                    <h5 class="card-title fw-normal">Tổng Người Dùng</h5>
                    <h2 class="fw-bold mb-0">{{ $stats['total_users'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white border-0 shadow-sm" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                    <i class="bi bi-person-badge fs-1 mb-2" style="opacity: 0.8;"></i>
                    <h5 class="card-title fw-normal">Quản Trị Viên</h5>
                    <h2 class="fw-bold mb-0">{{ $stats['admin_users'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white border-0 shadow-sm" style="background: linear-gradient(135deg, #10b981, #059669);">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                    <i class="bi bi-person fs-1 mb-2" style="opacity: 0.8;"></i>
                    <h5 class="card-title fw-normal">Khách Hàng</h5>
                    <h2 class="fw-bold mb-0">{{ $stats['customer_users'] }}</h2>
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

    {{-- Users Table --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên Đăng Nhập</th>
                        <th>Email</th>
                        <th>Họ Tên</th>
                        <th>Vai Trò</th>
                        <th>Ngày Tạo</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td><strong>#{{ $user->user_id }}</strong></td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->full_name ?? 'N/A' }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">Admin</span>
                                @else
                                    <span class="badge bg-primary">Customer</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') : 'N/A' }}</td>
                            <td>
                                <a href="/user-edit/{{ $user->user_id }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="/user-delete/{{ $user->user_id }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
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
                                <i class="bi bi-inbox"></i> Chưa có người dùng nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
