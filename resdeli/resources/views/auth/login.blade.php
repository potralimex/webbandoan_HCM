@extends('layouts.app')
@section('title', 'Đăng nhập - ResDeli')
@section('content')
<div style="min-height:calc(100vh - 64px); display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#fff3ef 0%,#f8f9fa 100%); padding:2rem 1rem;">
    <div style="width:100%; max-width:440px;">
        <div class="card animate-fadeIn" style="border:none; box-shadow:0 20px 60px rgba(0,0,0,0.1);">
            <div style="background:linear-gradient(135deg,var(--primary) 0%,var(--primary-dark) 100%); padding:2.5rem 2rem 2rem; text-align:center; border-radius:var(--radius) var(--radius) 0 0;">
                <div style="font-size:2.5rem; margin-bottom:0.5rem;">🍜</div>
                <h1 style="color:#fff; font-size:1.5rem; font-weight:800; margin:0;">Chào mừng trở lại!</h1>
                <p style="color:rgba(255,255,255,0.8); font-size:0.875rem; margin-top:0.4rem;">Đăng nhập để đặt đồ ăn ngay</p>
            </div>
            <div style="padding:2rem;">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>{{ $errors->first() }}</div>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="email"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" id="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" placeholder="email@example.com" required>
                        @if($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password"><i class="fas fa-lock"></i> Mật khẩu</label>
                        <div style="position:relative;">
                            <input type="password" id="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="••••••••" required>
                            <button type="button" onclick="togglePwd('password','eyeIcon')" style="position:absolute;right:0.9rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted);">
                                <i id="eyeIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                        @if($errors->has('password'))
                            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                        @endif
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <label class="form-check" style="cursor:pointer; font-size:0.875rem;">
                            <input type="checkbox" name="remember" class="form-check-input"> Nhớ đăng nhập
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" style="margin-bottom:1.25rem; padding:0.75rem;">
                        <i class="fas fa-sign-in-alt"></i> Đăng nhập
                    </button>

                    <div class="text-center" style="font-size:0.875rem; color:var(--text-muted);">
                        Chưa có tài khoản?
                        <a href="{{ route('register') }}" style="color:var(--primary); font-weight:600;">Đăng ký ngay</a>
                    </div>

                    <hr>
                    <div style="background:var(--bg); border-radius:var(--radius-sm); padding:1rem; font-size:0.8rem; color:var(--text-muted);">
                        <strong>Demo accounts:</strong><br>
                        Admin: admin@resdeli.com / password<br>
                        Customer: customer@resdeli.com / password
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
