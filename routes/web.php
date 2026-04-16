<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('index');
});

// Product Detail
Route::get('/product/{id}', function ($id) {
    return view('product', ['product_id' => $id]);
});

// Shopping Cart
Route::get('/cart', function () {
    if (!session('logged_in')) {
        return redirect('/login')->with('error', 'Vui lòng đăng nhập để xem giỏ hàng');
    }
    return view('cart');
});

// Checkout
Route::get('/checkout', function () {
    if (!session('logged_in')) {
        return redirect('/login')->with('error', 'Vui lòng đăng nhập để thanh toán');
    }
    return view('checkout');
});

// Process Payment
Route::post('/process-payment', function (Request $request) {
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
        DB::beginTransaction();

        $orderId = DB::table('orders')->insertGetId([
            'user_id' => session('user_id'),
            'order_date' => now(),
            'total_amount' => 0,
            'status' => 'pending',
            'shipping_address' => $validated['address'],
        ]);

        $subtotal = 0;
        foreach ($cart as $item) {
            $productId = intval($item['id']);
            $quantity = max(1, intval($item['quantity'] ?? 1));
            $price = 1000000 + (($productId * 35791) % 4000000);
            $subtotal += $price * $quantity;

            $product = DB::table('products')->where('product_id', $productId)->first();
            if (!$product) {
                DB::table('products')->insert([
                    'product_id' => $productId,
                    'product_name' => 'Đồng Hồ ' . $productId,
                    'description' => 'Sản phẩm tham chiếu cho đơn hàng',
                    'price' => $price,
                    'stock' => 100,
                    'image_url' => null,
                    'category_id' => null,
                    'created_at' => now(),
                ]);
            }

            DB::table('order_details')->insert([
                'order_id' => $orderId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
            ]);
        }

        DB::table('orders')->where('order_id', $orderId)->update(['total_amount' => $subtotal]);

        DB::table('payments')->insert([
            'order_id' => $orderId,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'paid',
            'payment_date' => now(),
        ]);

        DB::commit();
    } catch (Throwable $e) {
        DB::rollBack();
        return redirect('/checkout')->with('error', 'Có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại.');
    }

    return redirect('/order-success?order_id=' . $orderId)
        ->with('success', 'Thanh toán thành công! Đơn hàng #' . $orderId . ' đã được tạo.');
});

// Order Success
Route::get('/order-success', function () {
    if (!session('logged_in')) {
        return redirect('/');
    }
    return view('order-success', ['order_id' => request()->query('order_id')]);
});

// Orders Admin
Route::get('/orders', function () {
    if (!session('logged_in') || session('role') !== 'admin') {
        return redirect('/')->with('error', 'Bạn không có quyền truy cập');
    }

    $orders = DB::table('orders')
        ->leftJoin('users', 'orders.user_id', '=', 'users.user_id')
        ->select('orders.order_id', 'orders.order_date', 'orders.total_amount', 'orders.status', 'orders.shipping_address', 'users.full_name', 'users.email')
        ->orderByDesc('orders.order_date')
        ->get();

    return view('orders', compact('orders'));
});

// Register Routes
Route::get('/register', [RegisterController::class, 'showRegisterForm']);
Route::post('/register', [RegisterController::class, 'register']);

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login']);

// Logout
Route::get('/logout', [LoginController::class, 'logout']);

// Dashboard (chỉ cho admin)
Route::get('/dashboard', function () {
    return view('dashboard');
});

// Báo cáo thống kê
Route::get('/report', function () {

    $summary = [
        'total_users' => DB::table('users')->count(),
        'total_products' => DB::table('products')->count(),
        'total_orders' => DB::table('orders')->count(),
        'total_revenue' => DB::table('orders')->sum('total_amount') ?? 0,
        'total_payments' => DB::table('payments')->count(),
    ];

    $orderStatuses = DB::table('orders')
        ->select('status', DB::raw('count(*) as count'))
        ->groupBy('status')
        ->get();

    $paymentStatuses = DB::table('payments')
        ->select('payment_status', DB::raw('count(*) as count'))
        ->groupBy('payment_status')
        ->get();

    $topProducts = DB::table('order_details')
        ->join('products', 'order_details.product_id', '=', 'products.product_id')
        ->select('products.product_name', DB::raw('SUM(order_details.quantity) as sold_quantity'))
        ->groupBy('products.product_name')
        ->orderByDesc('sold_quantity')
        ->limit(5)
        ->get();

    $recentOrders = DB::table('orders')
        ->leftJoin('users', 'orders.user_id', '=', 'users.user_id')
        ->select('orders.order_id', 'orders.order_date', 'orders.total_amount', 'orders.status', 'users.full_name', 'users.username')
        ->orderByDesc('orders.order_date')
        ->limit(5)
        ->get();

    return view('report', compact('summary', 'orderStatuses', 'paymentStatuses', 'topProducts', 'recentOrders'));
});


