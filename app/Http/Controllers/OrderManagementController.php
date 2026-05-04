<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderManagementController extends Controller
{
    public function index()
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        $orders = Order::getOrdersWithUser();
        return view('order-manage', compact('orders'));
    }

    public function updateStatus(Request $request, $orderId)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,delivery,delivered,cancelled',
        ]);

        $order = Order::getOrderById($orderId);
        if (!$order) {
            return back()->with('error', 'Không tìm thấy đơn hàng');
        }

        $updated = Order::updateOrderStatus($orderId, $validated['status']);

        if ($updated) {
            return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
        } else {
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật trạng thái.');
        }
    }

    public function show($orderId)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        $order = Order::getOrderById($orderId);
        if (!$order) {
            return back()->with('error', 'Không tìm thấy đơn hàng');
        }

        $orderDetails = Order::getOrderDetails($orderId);
        $user = \App\Models\User::getUserById($order->user_id);

        return view('order-admin-detail', compact('order', 'orderDetails', 'user'));
    }
}
