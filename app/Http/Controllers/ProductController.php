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
            $catId = request('category');
            $query->where(function($q) use ($catId) {
                $q->where('category_id', $catId)
                  ->orWhereExists(function ($sub) use ($catId) {
                      $sub->select(DB::raw(1))
                          ->from('product_categories')
                          ->whereColumn('product_categories.product_id', 'products.product_id')
                          ->where('product_categories.category_id', $catId);
                  });
            });
        }

        $products = $query->orderByDesc('created_at')->get();
        $categories = Category::getAllCategories();
        return view('index', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = Product::getProductById($id);
        if (!$product) {
            return redirect('/')->with('error', 'Sản phẩm không tồn tại');
        }

        $relatedProducts = DB::table('products')
            ->where('category_id', $product->category_id)
            ->where('product_id', '!=', $id)
            ->limit(3)
            ->get();

        return view('product', compact('product', 'relatedProducts'));
    }

    public function apiShow($id)
    {
        $product = Product::getProductById($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại'], 404);
        }
        return response()->json(['success' => true, 'product' => $product]);
    }
}
