<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class User
{
    public static function getAllUsers()
    {
        return DB::table('users')->orderByDesc('created_at')->get();
    }

    public static function getUserStats()
    {
        return [
            'total_users' => DB::table('users')->count(),
            'admin_users' => DB::table('users')->where('role', 'admin')->count(),
            'customer_users' => DB::table('users')->where('role', 'customer')->count(),
            'new_users_today' => DB::table('users')->whereDate('created_at', today())->count(),
        ];
    }

    public static function getUserById($id)
    {
        return DB::table('users')->where('user_id', $id)->first();
    }

    public static function createUser($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['created_at'] = now();
        return DB::table('users')->insertGetId($data);
    }

    public static function updateUser($id, $data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        
        return DB::table('users')->where('user_id', $id)->update($data);
    }

    public static function deleteUser($id)
    {
        return DB::table('users')->where('user_id', $id)->delete();
    }
}
