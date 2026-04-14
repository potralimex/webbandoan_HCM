<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Profile;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) return redirect()->route('home');
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Email không được để trống.',
            'email.email'       => 'Email không hợp lệ.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min'      => 'Mật khẩu phải ít nhất 6 ký tự.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Chào mừng Admin ' . $user->name . '!');
            }

            return redirect()->route('home')->with('success', 'Đăng nhập thành công! Chào mừng ' . $user->name . '!');
        }

        return back()->withErrors(['email' => 'Email hoặc mật khẩu không chính xác.'])->withInput();
    }

    public function showRegister()
    {
        if (Auth::check()) return redirect()->route('home');
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|min:2|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone'    => 'nullable|regex:/^[0-9]{10,11}$/',
            'role'     => 'required|in:customer,restaurant_owner',
        ], [
            'name.required'      => 'Họ tên không được để trống.',
            'name.min'           => 'Họ tên phải ít nhất 2 ký tự.',
            'email.required'     => 'Email không được để trống.',
            'email.unique'       => 'Email này đã được sử dụng.',
            'password.min'       => 'Mật khẩu phải ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'phone.regex'        => 'Số điện thoại không hợp lệ (10-11 số).',
            'role.in'            => 'Vai trò không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'phone'    => $request->phone,
        ]);

        Profile::create(['user_id' => $user->id]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Đăng ký thành công! Chào mừng ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Đã đăng xuất thành công!');
    }
}
