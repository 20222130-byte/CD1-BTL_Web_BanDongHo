<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LoginModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    /**
     * Tìm user bằng username hoặc email
     */
    public function getUserByUsernameOrEmail($username_or_email)
    {
        return DB::table('users')
            ->where('username', $username_or_email)
            ->orWhere('email', $username_or_email)
            ->first();
    }

    /**
     * Xác thực user
     */
    public function authenticate($username_or_email, $password)
    {
        $user = $this->getUserByUsernameOrEmail($username_or_email);
        
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        
        return null;
    }
}
