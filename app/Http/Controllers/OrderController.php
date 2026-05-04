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
}
