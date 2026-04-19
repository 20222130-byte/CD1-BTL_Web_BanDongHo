<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Report
{
    public static function getSummary()
    {
        return [
            'total_users' => DB::table('users')->count(),
            'total_products' => DB::table('products')->count(),
            'total_orders' => DB::table('orders')->count(),
            'total_revenue' => DB::table('orders')->sum('total_amount') ?? 0,
            'total_payments' => DB::table('payments')->count(),
        ];
    }

    public static function getOrderStatuses()
    {
        return DB::table('orders')
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
    }

    public static function getPaymentStatuses()
    {
        return DB::table('payments')
            ->select('payment_status', DB::raw('count(*) as count'))
            ->groupBy('payment_status')
            ->get();
    }

    public static function getTopProducts()
    {
        return DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.product_id')
            ->select('products.product_name', DB::raw('SUM(order_details.quantity) as sold_quantity'))
            ->groupBy('products.product_name')
            ->orderByDesc('sold_quantity')
            ->limit(5)
            ->get();
    }

    public static function getRecentOrders()
    {
        return DB::table('orders')
            ->leftJoin('users', 'orders.user_id', '=', 'users.user_id')
            ->select('orders.order_id', 'orders.order_date', 'orders.total_amount', 'orders.status', 'users.full_name', 'users.username')
            ->orderByDesc('orders.order_date')
            ->limit(5)
            ->get();
    }
}
