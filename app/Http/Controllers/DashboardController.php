<?php

namespace App\Http\Controllers;

use App\Models\Report;

class DashboardController extends Controller
{
    public function index()
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        $summary = Report::getSummary();

        return view('admin-dashboard', compact('summary'));
    }
}
