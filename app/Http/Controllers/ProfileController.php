<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function show()
    {
        $user    = Auth::user()->load('profile');
        $orders  = Auth::user()->orders()->with('restaurant')->latest()->take(5)->get();
        $favorites = Auth::user()->favorites()->get();
        return view('profile.show', compact('user', 'orders', 'favorites'));
    }

    public function edit()
    {
        $user = Auth::user()->load('profile');
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'    => 'required|string|min:2|max:100',
            'phone'   => 'nullable|regex:/^[0-9]{10,11}$/',
            'address' => 'nullable|string|max:255',
            'city'    => 'nullable|string|max:100',
            'bio'     => 'nullable|string|max:300',
            'avatar'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'name.required' => 'Họ tên không được để trống.',
            'phone.regex'   => 'Số điện thoại không hợp lệ.',
            'avatar.image'  => 'File phải là ảnh.',
            'avatar.max'    => 'Ảnh không được vượt quá 2MB.',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update([
            'name'  => $validated['name'],
            'phone' => $validated['phone'] ?? $user->phone,
            'avatar' => $user->avatar,
        ]);

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'address' => $validated['address'] ?? null,
                'city'    => $validated['city'] ?? null,
                'bio'     => $validated['bio'] ?? null,
            ]
        );

        return redirect()->route('profile.show')->with('success', 'Cập nhật hồ sơ thành công!');
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password'  => 'required',
            'password'          => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'password.min'              => 'Mật khẩu mới phải ít nhất 8 ký tự.',
            'password.confirmed'        => 'Xác nhận mật khẩu không khớp.',
        ]);

        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
        }

        $user->update(['password' => Hash::make($validated['password'])]);
        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
}
