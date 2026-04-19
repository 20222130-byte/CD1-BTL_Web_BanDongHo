<?php

namespace App\Http\Controllers;

use App\Models\Report;

class ReportController extends Controller
{
    public function showReport()
    {
        $summary = Report::getSummary();
        $orderStatuses = Report::getOrderStatuses();
        $paymentStatuses = Report::getPaymentStatuses();
        $topProducts = Report::getTopProducts();
        $recentOrders = Report::getRecentOrders();

        return view('report', compact('summary', 'orderStatuses', 'paymentStatuses', 'topProducts', 'recentOrders'));
    }
}
