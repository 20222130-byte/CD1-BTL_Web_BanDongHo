<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;

class ProductManagerController extends Controller
{
    public function index()
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

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
        $stats = Product::getProductStats();
        $categories = Category::getAllCategories();

        return view('product-manager', compact('products', 'stats', 'categories'));
    }

    public function show($id)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'B?n kh�ng c� quy?n truy c?p');
        }

        $product = Product::getProductById($id);
        if (!$product) {
            return redirect('/product-manager')->with('error', 'S?n ph?m kh�ng t?n t?i');
        }

        return view('product-edit', compact('product'));
    }

    public function create()
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'B?n kh�ng c� quy?n truy c?p');
        }

        return view('product-create');
    }

    public function store(Request $request)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'B?n kh�ng c� quy?n truy c?p');
        }

        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image_url' => 'nullable|url',
            'category_id' => 'nullable|integer',
        ]);

        try {
            $productId = Product::createProduct($validated);
            return redirect('/product-manager')->with('success', 'Th�m s?n ph?m th�nh c�ng! ID: ' . $productId);
        } catch (\Throwable $e) {
            return redirect('/product-create')->with('error', 'L?i khi th�m s?n ph?m: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'B?n kh�ng c� quy?n truy c?p');
        }

        $product = Product::getProductById($id);
        if (!$product) {
            return redirect('/product-manager')->with('error', 'S?n ph?m kh�ng t?n t?i');
        }

        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image_url' => 'nullable|url',
            'category_id' => 'nullable|integer',
        ]);

        try {
            Product::updateProduct($id, $validated);
            return redirect('/product-manager')->with('success', 'C?p nh?t s?n ph?m th�nh c�ng!');
        } catch (\Throwable $e) {
            return redirect('/product-edit/' . $id)->with('error', 'L?i khi c?p nh?t s?n ph?m: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'B?n kh�ng c� quy?n truy c?p');
        }

        $product = Product::getProductById($id);
        if (!$product) {
            return redirect('/product-manager')->with('error', 'S?n ph?m kh�ng t?n t?i');
        }

        try {
            Product::deleteProduct($id);
            return redirect('/product-manager')->with('success', 'X�a s?n ph?m th�nh c�ng!');
        } catch (\Throwable $e) {
            return redirect('/product-manager')->with('error', 'L?i khi x�a s?n ph?m: ' . $e->getMessage());
        }
    }

    // Category Management Methods
    public function categoryIndex()
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'B?n kh�ng c� quy?n truy c?p');
        }

        $categories = Category::getAllCategories();
        return view('category-manager', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'B?n kh�ng c� quy?n truy c?p');
        }

        $validated = $request->validate([
            'category_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        try {
            $categoryId = Category::createCategory($validated);
            return redirect('/category-manager')->with('success', 'Th�m danh m?c th�nh c�ng! ID: ' . $categoryId);
        } catch (\Throwable $e) {
            return redirect('/category-manager')->with('error', 'L?i khi th�m danh m?c: ' . $e->getMessage());
        }
    }

    public function categoryEdit($id)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'B?n kh�ng c� quy?n truy c?p');
        }

        $category = Category::getCategoryById($id);
        if (!$category) {
            return redirect('/category-manager')->with('error', 'Danh m?c kh�ng t?n t?i');
        }

        return view('category-edit', compact('category'));
    }

    public function categoryUpdate(Request $request, $id)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'B?n kh�ng c� quy?n truy c?p');
        }

        $category = Category::getCategoryById($id);
        if (!$category) {
            return redirect('/category-manager')->with('error', 'Danh m?c kh�ng t?n t?i');
        }

        $validated = $request->validate([
            'category_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        try {
            Category::updateCategory($id, $validated);
            return redirect('/category-manager')->with('success', 'C?p nh?t danh m?c th�nh c�ng!');
        } catch (\Throwable $e) {
            return redirect('/category-edit/' . $id)->with('error', 'L?i khi c?p nh?t danh m?c: ' . $e->getMessage());
        }
    }

    public function categoryDelete($id)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'B?n kh�ng c� quy?n truy c?p');
        }

        try {
            Category::deleteCategory($id);
            return redirect('/category-manager')->with('success', 'X�a danh m?c th�nh c�ng!');
        } catch (\Throwable $e) {
            return redirect('/category-manager')->with('error', 'L?i khi x�a danh m?c: ' . $e->getMessage());
        }
    }
}

