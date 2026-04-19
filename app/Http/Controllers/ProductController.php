<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function show($id)
    {
        return view('product', ['product_id' => $id]);
    }
}
