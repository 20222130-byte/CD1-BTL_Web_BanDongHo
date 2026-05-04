<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
<<<<<<< HEAD

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
=======
use App\Models\Order;

class ProfileController extends Controller
{
    public function showProfile()
    {
        if (!session('logged_in')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập');
        }

        $user = User::getUserById(session('user_id'));
        return view('profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/login');
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        $userId = session('user_id');
        $updated = User::updateUser($userId, $validated);

        if ($updated) {
            return redirect('/profile')->with('success', 'Cập nhật thông tin thành công!');
        } else {
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật thông tin.');
        }
    }

    public function myOrders()
    {
        if (!session('logged_in')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập');
        }

        $userId = session('user_id');
        $orders = Order::getUserOrders($userId);
        
        return view('user-orders', compact('orders'));
    }

    public function orderDetail($orderId)
>>>>>>> a1863cdf6d77a08bf48a952dd39765b3b3355e29
    {
        if (!session('logged_in')) {
            return redirect('/login');
        }

        $userId = session('user_id');
<<<<<<< HEAD
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
=======
        $order = Order::getOrderById($orderId);

        // Kiểm tra xem đơn hàng có thuộc về user này không
        if (!$order || $order->user_id != $userId) {
            return redirect('/my-orders')->with('error', 'Không tìm thấy đơn hàng');
        }

        $orderDetails = Order::getOrderDetails($orderId);
        
        return view('order-detail', compact('order', 'orderDetails'));
>>>>>>> a1863cdf6d77a08bf48a952dd39765b3b3355e29
    }
}
