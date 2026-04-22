<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Category
{
    public static function getAllCategories()
    {
        return DB::table('categories')->get();
    }

    public static function getCategoryById($id)
    {
        return DB::table('categories')->where('category_id', $id)->first();
    }

    public static function createCategory($data)
    {
        return DB::table('categories')->insertGetId([
            'category_name' => $data['category_name'],
            'description' => $data['description'] ?? null,
        ]);
    }

    public static function updateCategory($id, $data)
    {
        return DB::table('categories')
            ->where('category_id', $id)
            ->update([
                'category_name' => $data['category_name'] ?? null,
                'description' => $data['description'] ?? null,
            ]);
    }

    public static function deleteCategory($id)
    {
        return DB::table('categories')->where('category_id', $id)->delete();
    }
}
