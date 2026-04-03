<?php

namespace App\Http\Controllers;

use App\Models\LoginModel;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected $loginModel;

    public function __construct(LoginModel $loginModel)
    {
        $this->loginModel = $loginModel;
    }

    /**
     * Hiển thị form đăng nhập
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username_or_email' => 'required',
            'password' => 'required'
        ], [
            'username_or_email.required' => 'Tên tài khoản hoặc email không được bỏ trống',
            'password.required' => 'Mật khẩu không được bỏ trống'
        ]);

        // Xác thực user
        $user = $this->loginModel->authenticate(
            $validated['username_or_email'],
            $validated['password']
        );

        if ($user) {
            // Lưu thông tin user vào session
            session([
                'user_id' => $user->user_id,
                'username' => $user->username,
                'email' => $user->email,
                'full_name' => $user->full_name,
                'role' => $user->role,
                'logged_in' => true
            ]);

            // Redirect dựa trên role
            if ($user->role === 'admin') {
                return redirect('/dashboard')->with('success', 'Đăng nhập thành công');
            } else {
                return redirect('/')->with('success', 'Đăng nhập thành công');
            }
        } else {
            return back()->withErrors(['error' => 'Tên tài khoản/email hoặc mật khẩu không hợp lệ'])->withInput();
        }
    }

    /**
     * Đăng xuất
     */
    public function logout()
    {
        session()->flush();
        return redirect('/')->with('success', 'Đã đăng xuất');
    }
}
