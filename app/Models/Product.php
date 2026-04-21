<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Product
{
    public static function getAllProducts()
    {
        return DB::table('products')
            ->orderByDesc('created_at')
            ->get();
    }

    public static function getProductById($id)
    {
        return DB::table('products')
            ->where('product_id', $id)
            ->first();
    }

    public static function createProduct($data)
    {
        return DB::table('products')->insertGetId([
            'product_name' => $data['product_name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'stock' => $data['stock'] ?? 0,
            'image_url' => $data['image_url'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'created_at' => now(),
        ]);
    }

    public static function updateProduct($id, $data)
    {
        return DB::table('products')
            ->where('product_id', $id)
            ->update([
                'product_name' => $data['product_name'] ?? null,
                'description' => $data['description'] ?? null,
                'price' => $data['price'] ?? null,
                'stock' => $data['stock'] ?? null,
                'image_url' => $data['image_url'] ?? null,
                'category_id' => $data['category_id'] ?? null,
            ]);
    }

    public static function deleteProduct($id)
    {
        return DB::table('products')
            ->where('product_id', $id)
            ->delete();
    }

    public static function getProductStats()
    {
        return [
            'total_products' => DB::table('products')->count(),
            'total_stock' => DB::table('products')->sum('stock') ?? 0,
            'avg_price' => DB::table('products')->avg('price') ?? 0,
            'low_stock' => DB::table('products')->where('stock', '<', 10)->count(),
        ];
    }
}
