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

        $categories = Category::getAllCategories();
        $productCategories = Product::getCategoriesByProductId($id);
        return view('product-edit', compact('product', 'categories', 'productCategories'));
    }

    public function create()
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'B?n kh�ng c� quy?n truy c?p');
        }

        $categories = Category::getAllCategories();
        return view('product-create', compact('categories'));
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'integer',
        ]);

        $categoryIds = $request->input('category_ids', []);
        $validated['category_id'] = $categoryIds[0] ?? null;

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('images'), $imageName);
            $validated['image_url'] = '/images/'.$imageName;
        }
        unset($validated['image']);

        try {
            $productId = Product::createProduct($validated);
            Product::syncCategories($productId, $categoryIds);
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'nullable|integer',
        ]);

        $categoryIds = $request->input('category_ids', []);
        $validated['category_id'] = $categoryIds[0] ?? null;

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('images'), $imageName);
            $validated['image_url'] = '/images/'.$imageName;
        }
        unset($validated['image']);

        try {
            Product::updateProduct($id, $validated);
            Product::syncCategories($id, $categoryIds);
            return redirect('/product-manager')->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (\Throwable $e) {
            return redirect('/product-edit/' . $id)->with('error', 'Lỗi khi cập nhật sản phẩm: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyênn truy cập');
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

    // Category Management Methods
    public function categoryIndex()
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        $categories = Category::getAllCategories();
        $groups = collect($categories)->pluck('description')->unique()->filter()->values();
        return view('category-manager', compact('categories', 'groups'));
    }

    public function categoryStore(Request $request)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        $validated = $request->validate([
            'category_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        try {
            $categoryId = Category::createCategory($validated);
            return redirect('/category-manager')->with('success', 'Thêm danh mục thành công! ID: ' . $categoryId);
        } catch (\Throwable $e) {
            return redirect('/category-manager')->with('error', 'Lỗi khi thêm danh mục: ' . $e->getMessage());
        }
    }

    public function categoryEdit($id)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        $category = Category::getCategoryById($id);
        if (!$category) {
            return redirect('/category-manager')->with('error', 'Danh mục không tồn tại');
        }

        $categories = Category::getAllCategories();
        $groups = collect($categories)->pluck('description')->unique()->filter()->values();

        return view('category-edit', compact('category', 'groups'));
    }

    public function categoryUpdate(Request $request, $id)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
        }

        $category = Category::getCategoryById($id);
        if (!$category) {
            return redirect('/category-manager')->with('error', 'Danh mục không tồn tại');
        }

        $validated = $request->validate([
            'category_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        try {
            Category::updateCategory($id, $validated);
            return redirect('/category-manager')->with('success', 'Cập nhật danh mục thành công!');
        } catch (\Throwable $e) {
            return redirect('/category-edit/' . $id)->with('error', 'Lỗi khi cập nhật danh mục: ' . $e->getMessage());
        }
    }

    public function categoryDelete($id)
    {
        if (!session('logged_in') || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        try {
            Category::deleteCategory($id);
            return redirect('/category-manager')->with('success', 'Xóa danh mục thành công!');
        } catch (\Throwable $e) {
            return redirect('/category-manager')->with('error', 'Lỗi khi xóa danh mục: ' . $e->getMessage());
        }
    }
}

