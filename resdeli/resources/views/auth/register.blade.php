@extends('layouts.app')
@section('title', 'Đăng ký - ResDeli')
@section('content')
<div style="min-height:calc(100vh - 64px); display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#fff3ef 0%,#f8f9fa 100%); padding:2rem 1rem;">
    <div style="width:100%; max-width:480px;">
        <div class="card animate-fadeIn" style="border:none; box-shadow:0 20px 60px rgba(0,0,0,0.1);">
            <div style="background:linear-gradient(135deg,var(--primary) 0%,var(--primary-dark) 100%); padding:2.5rem 2rem 2rem; text-align:center; border-radius:var(--radius) var(--radius) 0 0;">
                <div style="font-size:2.5rem; margin-bottom:0.5rem;">🎉</div>
                <h1 style="color:#fff; font-size:1.5rem; font-weight:800; margin:0;">Tạo tài khoản mới</h1>
                <p style="color:rgba(255,255,255,0.8); font-size:0.875rem; margin-top:0.4rem;">Bắt đầu hành trình ẩm thực cùng ResDeli</p>
            </div>
            <div style="padding:2rem;">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form action="{{ route('register.post') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-user"></i> Họ và tên</label>
                        <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" placeholder="Nguyễn Văn A" required>
                        @if($errors->has('name'))<div class="invalid-feedback">{{ $errors->first('name') }}</div>@endif
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" placeholder="email@example.com" required>
                        @if($errors->has('email'))<div class="invalid-feedback">{{ $errors->first('email') }}</div>@endif
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-phone"></i> Số điện thoại</label>
                        <input type="tel" name="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" value="{{ old('phone') }}" placeholder="0901234567">
                        @if($errors->has('phone'))<div class="invalid-feedback">{{ $errors->first('phone') }}</div>@endif
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-lock"></i> Mật khẩu</label>
                        <div style="position:relative;">
                            <input type="password" id="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Tối thiểu 8 ký tự" required>
                            <button type="button" onclick="togglePwd('password','eyeIcon1')" style="position:absolute;right:0.9rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted);">
                                <i id="eyeIcon1" class="fas fa-eye"></i>
                            </button>
                        </div>
                        @if($errors->has('password'))<div class="invalid-feedback">{{ $errors->first('password') }}</div>@endif
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-lock"></i> Xác nhận mật khẩu</label>
                        <div style="position:relative;">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu" required>
                            <button type="button" onclick="togglePwd('password_confirmation','eyeIcon2')" style="position:absolute;right:0.9rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted);">
                                <i id="eyeIcon2" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-user-tag"></i> Loại tài khoản</label>
                        <select name="role" class="form-control {{ $errors->has('role') ? 'is-invalid' : '' }}" required>
                            <option value="customer" {{ old('role','customer') == 'customer' ? 'selected' : '' }}>🛒 Khách hàng - Đặt đồ ăn</option>
                            <option value="restaurant_owner" {{ old('role') == 'restaurant_owner' ? 'selected' : '' }}>🏪 Chủ nhà hàng - Quản lý nhà hàng</option>
                        </select>
                        @if($errors->has('role'))<div class="invalid-feedback">{{ $errors->first('role') }}</div>@endif
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" style="margin-bottom:1.25rem; padding:0.75rem;">
                        <i class="fas fa-user-plus"></i> Tạo tài khoản
                    </button>
                    <div class="text-center" style="font-size:0.875rem; color:var(--text-muted);">
                        Đã có tài khoản?
                        <a href="{{ route('login') }}" style="color:var(--primary); font-weight:600;">Đăng nhập</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
function togglePwd(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);
    if (field.type === 'password') { field.type = 'text'; icon.classList.replace('fa-eye','fa-eye-slash'); }
    else { field.type = 'password'; icon.classList.replace('fa-eye-slash','fa-eye'); }
}
</script>
@endsection
