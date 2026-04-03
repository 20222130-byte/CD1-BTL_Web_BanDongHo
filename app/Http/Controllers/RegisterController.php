<?php

namespace App\Http\Controllers;

use App\Models\RegisterModel;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    protected $registerModel;

    public function __construct(RegisterModel $registerModel)
    {
        $this->registerModel = $registerModel;
    }

    /**
     * Hiển thị form đăng ký
     */
    public function showRegisterForm()
    {
        return view('register');
    }

    /**
     * Xử lý đăng ký
     */
    public function register(Request $request)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'username' => 'required|min:3|max:50',
            'email' => 'required|email|max:100',
            'password' => 'required|min:8',
            'confirm_password' => 'required|min:8|same:password',
            'full_name' => 'required|max:100',
            'phone' => 'required|max:20',
            'address' => 'required'
        ], [
            'username.required' => 'Tên tài khoản không được bỏ trống',
            'username.min' => 'Tên tài khoản phải ít nhất 3 ký tự',
            'email.required' => 'Email không được bỏ trống',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu không được bỏ trống',
            'password.min' => 'Mật khẩu phải ít nhất 8 ký tự',
            'confirm_password.same' => 'Mật khẩu xác nhận không khớp',
            'full_name.required' => 'Họ và tên không được bỏ trống',
            'phone.required' => 'Số điện thoại không được bỏ trống',
            'address.required' => 'Địa chỉ không được bỏ trống'
        ]);

        // Kiểm tra username đã tồn tại
        if ($this->registerModel->checkUsernameExists($validated['username'])) {
            return back()->withErrors(['username' => 'Tên tài khoản đã tồn tại'])->withInput();
        }

        // Kiểm tra email đã tồn tại
        if ($this->registerModel->checkEmailExists($validated['email'])) {
            return back()->withErrors(['email' => 'Email đã được đăng ký'])->withInput();
        }

        // Mã hóa mật khẩu
        $validated['password'] = bcrypt($validated['password']);

        // Tạo user mới
        $success = $this->registerModel->createUser($validated);

        if ($success) {
            return redirect('/login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
        } else {
            return back()->withErrors(['error' => 'Đăng ký thất bại. Vui lòng thử lại.'])->withInput();
        }
    }
}
