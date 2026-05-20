<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Throwable;

class Order
{
    public static function createOrder($userId, $cart, $address)
    {
        try {
            DB::beginTransaction();

            $orderId = DB::table('orders')->insertGetId([
                'user_id' => $userId,
                'order_date' => now(),
                'total_amount' => 0,
                'status' => 'pending',
                'shipping_address' => $address,
            ]);

            $subtotal = 0;
            foreach ($cart as $item) {
                $productId = intval($item['id']);
                $quantity = max(1, intval($item['quantity'] ?? 1));
                
                $product = DB::table('products')->where('product_id', $productId)->first();
                if (!$product) {
                    throw new \Exception("Sản phẩm #{$productId} không tồn tại hoặc đã bị xóa.");
                }

                $price = $product->price;
                $subtotal += $price * $quantity;
                
                // Trừ số lượng tồn kho
                DB::table('products')->where('product_id', $productId)->decrement('stock', $quantity);

                DB::table('order_details')->insert([
                    'order_id' => $orderId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);
            }

            DB::table('orders')->where('order_id', $orderId)->update(['total_amount' => $subtotal]);

            DB::commit();
            return $orderId;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function ensureProductExists($productId, $price)
    {
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
    }

    public static function getOrdersWithUser()
    {
        return DB::table('orders')
            ->leftJoin('users', 'orders.user_id', '=', 'users.user_id')
            ->select('orders.*', 'users.full_name', 'users.email')
            ->orderByDesc('orders.order_date')
            ->get();
    }

    public static function getOrdersByUserId($userId)
    {
        return DB::table('orders')
            ->where('user_id', $userId)
            ->orderByDesc('order_date')
            ->get();
    }

    public static function updateOrderStatus($orderId, $status)
    {
        return DB::table('orders')
            ->where('order_id', $orderId)
            ->update(['status' => $status]);
    }

    public static function getOrderDetails($orderId)
    {
        return DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.product_id')
            ->where('order_id', $orderId)
            ->select('order_details.*', 'products.product_name', 'products.image_url')
            ->get();
    }
}
