<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class User
{
    public static function getAllUsers()
    {
        return DB::table('users')
            ->orderByDesc('created_at')
            ->get();
    }

    public static function getUserById($id)
    {
        return DB::table('users')
            ->where('user_id', $id)
            ->first();
    }

    public static function createUser($data)
    {
        return DB::table('users')->insertGetId([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'full_name' => $data['full_name'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'role' => $data['role'] ?? 'customer',
            'created_at' => now(),
        ]);
    }

    public static function updateUser($id, $data)
    {
        $updateData = [
            'username' => $data['username'] ?? null,
            'email' => $data['email'] ?? null,
            'full_name' => $data['full_name'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'role' => $data['role'] ?? null,
            'updated_at' => now(),
        ];

        if (isset($data['password']) && !empty($data['password'])) {
            $updateData['password'] = bcrypt($data['password']);
        }

        return DB::table('users')
            ->where('user_id', $id)
            ->update($updateData);
    }

    public static function deleteUser($id)
    {
        return DB::table('users')
            ->where('user_id', $id)
            ->delete();
    }

    public static function getUserStats()
    {
        return [
            'total_users' => DB::table('users')->count(),
            'admin_users' => DB::table('users')->where('role', 'admin')->count(),
            'customer_users' => DB::table('users')->where('role', 'customer')->count(),
        ];
    }
}
