<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductManagerController extends Controller
{
    public function index()
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        $products = Product::getAllProducts();
        $stats = Product::getProductStats();

        return view('product-manager', compact('products', 'stats'));
    }

    public function show($id)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        $product = Product::getProductById($id);
        if (!$product) {
            return redirect('/product-manager')->with('error', 'Sản phẩm không tồn tại');
        }

        return view('product-edit', compact('product'));
    }

    public function create()
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        return view('product-create');
    }

    public function store(Request $request)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
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
            return redirect('/product-manager')->with('success', 'Thêm sản phẩm thành công! ID: ' . $productId);
        } catch (\Throwable $e) {
            return redirect('/product-create')->with('error', 'Lỗi khi thêm sản phẩm: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        $product = Product::getProductById($id);
        if (!$product) {
            return redirect('/product-manager')->with('error', 'Sản phẩm không tồn tại');
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
            return redirect('/product-manager')->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (\Throwable $e) {
            return redirect('/product-edit/' . $id)->with('error', 'Lỗi khi cập nhật sản phẩm: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        $product = Product::getProductById($id);
        if (!$product) {
            return redirect('/product-manager')->with('error', 'Sản phẩm không tồn tại');
        }

        try {
            Product::deleteProduct($id);
            return redirect('/product-manager')->with('success', 'Xóa sản phẩm thành công!');
        } catch (\Throwable $e) {
            return redirect('/product-manager')->with('error', 'Lỗi khi xóa sản phẩm: ' . $e->getMessage());
        }
    }
}
