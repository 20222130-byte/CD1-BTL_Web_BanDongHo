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
            return redirect('/cart');
        }

        // Kiểm tra tồn kho trước khi tạo đơn hàng
        foreach ($cart as $item) {
            $product = \App\Models\Product::getProductById($item['id']);
            if (!$product || $product->stock < $item['quantity']) {
                $pName = $product ? $product->product_name : "Sản phẩm #".$item['id'];
                
                return redirect('/cart')->with('error', "Sản phẩm '{$pName}' không đủ số lượng trong kho. Xin liên hệ với chúng tôi qua Hotline: 0123.456.789 để được hỗ trợ.");
            }
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
            'status' => 'required|in:pending,confirmed,processing,delivery,delivered,cancelled'
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

    public function showOrderDetail($id)
    {
        if (!session('logged_in')) {
            return redirect('/login');
        }

        $order = \Illuminate\Support\Facades\DB::table('orders')->where('order_id', $id)->first();
        if (!$order || $order->user_id != session('user_id')) {
            return redirect('/my-orders')->with('error', 'Không tìm thấy đơn hàng hoặc bạn không có quyền xem.');
        }

        $orderDetails = Order::getOrderDetails($id);
        return view('order-detail', compact('order', 'orderDetails'));
    }

    public function showAdminOrderDetail($id)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        $order = \Illuminate\Support\Facades\DB::table('orders')->where('order_id', $id)->first();
        if (!$order) {
            return redirect('/order-manage')->with('error', 'Không tìm thấy đơn hàng.');
        }

        $user = \Illuminate\Support\Facades\DB::table('users')->where('user_id', $order->user_id)->first();
        $orderDetails = Order::getOrderDetails($id);
        return view('order-admin-detail', compact('order', 'orderDetails', 'user'));
    }
}
