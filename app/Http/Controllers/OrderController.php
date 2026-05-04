<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;

class OrderController extends Controller
{
    public function showCart()
    {
        if (!session('logged_in')) {
            return redirect('/login?next=/cart')->with('error', 'Vui lòng đăng nhập để xem giỏ hàng');
        }
        return view('cart');
    }

    public function showCheckout()
    {
        if (!session('logged_in')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để thanh toán');
        }
        return view('checkout');
    }

    public function processPayment(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/login');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:creditcard,bank,ewallet,cod',
            'full_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required'
        ]);

        $cart = json_decode($request->input('cart', '[]'), true);
        if (empty($cart)) {
            return redirect('/cart')->with('error', 'Giỏ hàng của bạn đang trống');
        }

        try {
            $orderId = Order::createOrder(
                session('user_id'),
                $cart,
                $validated['address']
            );

            Payment::createPayment($orderId, $validated['payment_method']);

            return redirect('/order-success?order_id=' . $orderId)
                ->with('success', 'Thanh toán thành công! Đơn hàng #' . $orderId . ' đã được tạo.');
        } catch (\Throwable $e) {
            return redirect('/checkout')->with('error', 'Có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại.');
        }
    }

    public function showOrderSuccess()
    {
        if (!session('logged_in')) {
            return redirect('/');
        }
        return view('order-success', ['order_id' => request()->query('order_id')]);
    }

    public function listOrders()
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        $orders = Order::getOrdersWithUser();
        return view('orders', compact('orders'));
    }

    public function myOrders()
    {
        if (!session('logged_in')) {
            return redirect('/login?next=/my-orders')->with('error', 'Vui lòng đăng nhập để xem đơn hàng');
        }

        $orders = Order::getOrdersByUserId(session('user_id'));
        return view('my-orders', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        try {
            Order::updateOrderStatus($id, $validated['status']);
            
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
            }
            
            return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
        } catch (\Throwable $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Lỗi khi cập nhật trạng thái: ' . $e->getMessage());
        }
    }
}
