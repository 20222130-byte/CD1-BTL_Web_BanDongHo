<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RegisterModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $fillable = ['username', 'email', 'password', 'full_name', 'phone', 'address', 'role'];
    public $timestamps = false;

    /**
     * Kiểm tra username đã tồn tại hay chưa
     */
    public function checkUsernameExists($username)
    {
        return DB::table('users')->where('username', $username)->exists();
    }

    /**
     * Kiểm tra email đã tồn tại hay chưa
     */
    public function checkEmailExists($email)
    {
        return DB::table('users')->where('email', $email)->exists();
    }

    /**
     * Tạo user mới
     */
    public function createUser($data)
    {
        return DB::table('users')->insert([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'role' => 'customer',
            'created_at' => now()
        ]);
    }
}
