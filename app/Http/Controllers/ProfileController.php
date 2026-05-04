<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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
    {
        if (!session('logged_in')) {
            return redirect('/login');
        }

        $userId = session('user_id');
        $order = Order::getOrderById($orderId);

        // Kiểm tra xem đơn hàng có thuộc về user này không
        if (!$order || $order->user_id != $userId) {
            return redirect('/my-orders')->with('error', 'Không tìm thấy đơn hàng');
        }

        $orderDetails = Order::getOrderDetails($orderId);
        
        return view('order-detail', compact('order', 'orderDetails'));
    }
}
