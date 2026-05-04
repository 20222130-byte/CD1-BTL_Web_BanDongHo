<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;

class ProfileController extends Controller
{
    public function index()
    {
        if (!session('logged_in')) {
            return redirect('/login?next=/profile')->with('error', 'Vui lòng đăng nhập để xem hồ sơ');
        }

        $user = User::getUserById(session('user_id'));
        if (!$user) {
            session()->flush();
            return redirect('/login')->with('error', 'Người dùng không tồn tại');
        }

        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/login');
        }

        $userId = session('user_id');
        $validated = $request->validate([
            'full_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ]);

        try {
            // Remove password if not provided
            if (empty($validated['password'])) {
                unset($validated['password']);
            }
            // Remove password_confirmation as it's not in DB
            unset($validated['password_confirmation']);

            User::updateUser($userId, $validated);

            // Update session if needed (e.g. full_name changed)
            if (isset($validated['full_name'])) {
                session(['full_name' => $validated['full_name']]);
            }

            return redirect('/profile')->with('success', 'Cập nhật hồ sơ thành công!');
        } catch (\Throwable $e) {
            return redirect('/profile')->with('error', 'Lỗi khi cập nhật hồ sơ: ' . $e->getMessage());
        }
    }
}
