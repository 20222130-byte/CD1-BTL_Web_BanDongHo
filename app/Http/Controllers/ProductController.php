<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
    {
        $query = DB::table('products');

        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('product_name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if (request('category')) {
            $query->where('category_id', request('category'));
        }

        $products = $query->orderByDesc('created_at')->get();
        $categories = Category::getAllCategories();
        return view('index', compact('products', 'categories'));
    }

    public function show($id)
    {
        return view('product', ['product_id' => $id]);
    }
}
