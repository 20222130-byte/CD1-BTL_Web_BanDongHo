<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Payment
{
    public static function createPayment($orderId, $paymentMethod)
    {
        DB::table('payments')->insert([
            'order_id' => $orderId,
            'payment_method' => $paymentMethod,
            'payment_status' => 'paid',
            'payment_date' => now(),
        ]);
    }
}
