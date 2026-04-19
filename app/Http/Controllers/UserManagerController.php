<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserManagerController extends Controller
{
    public function index()
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        $users = User::getAllUsers();
        $stats = User::getUserStats();

        return view('user-manager', compact('users', 'stats'));
    }

    public function show($id)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        $user = User::getUserById($id);
        if (!$user) {
            return redirect('/user-manager')->with('error', 'Người dùng không tồn tại');
        }

        return view('user-edit', compact('user'));
    }

    public function create()
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        return view('user-create');
    }

    public function store(Request $request)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        $validated = $request->validate([
            'username' => 'required|string|unique:users,username|max:50',
            'email' => 'required|email|unique:users,email|max:100',
            'password' => 'required|string|min:6',
            'full_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role' => 'required|in:admin,customer',
        ]);

        try {
            $userId = User::createUser($validated);
            return redirect('/user-manager')->with('success', 'Thêm người dùng thành công! ID: ' . $userId);
        } catch (\Throwable $e) {
            return redirect('/user-create')->with('error', 'Lỗi khi thêm người dùng: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        $user = User::getUserById($id);
        if (!$user) {
            return redirect('/user-manager')->with('error', 'Người dùng không tồn tại');
        }

        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $id . ',user_id',
            'email' => 'required|email|max:100|unique:users,email,' . $id . ',user_id',
            'password' => 'nullable|string|min:6',
            'full_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role' => 'required|in:admin,customer',
        ]);

        try {
            User::updateUser($id, $validated);
            return redirect('/user-manager')->with('success', 'Cập nhật người dùng thành công!');
        } catch (\Throwable $e) {
            return redirect('/user-edit/' . $id)->with('error', 'Lỗi khi cập nhật người dùng: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        $user = User::getUserById($id);
        if (!$user) {
            return redirect('/user-manager')->with('error', 'Người dùng không tồn tại');
        }

        try {
            User::deleteUser($id);
            return redirect('/user-manager')->with('success', 'Xóa người dùng thành công!');
        } catch (\Throwable $e) {
            return redirect('/user-manager')->with('error', 'Lỗi khi xóa người dùng: ' . $e->getMessage());
        }
    }
}
