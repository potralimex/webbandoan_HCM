@extends('layouts.app')
@section('title', 'Chỉnh sửa hồ sơ - ResDeli')
@section('content')
<div class="container" style="padding: 2rem 1rem; max-width:700px;">
    <div class="d-flex gap-2 mb-3" style="font-size:0.875rem;">
        <a href="{{ route('profile.show') }}" style="color:var(--text-muted); text-decoration:none;"><i class="fas fa-arrow-left"></i> Hồ sơ</a>
        <span style="color:var(--text-muted);">/</span>
        <span>Chỉnh sửa</span>
    </div>

    @if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i><span>{{ session('success') }}</span></div>
    @endif

    <!-- Edit Profile Form -->
    <div class="card" style="padding:2rem; margin-bottom:1.5rem;">
        <h2 style="font-size:1.1rem; font-weight:800; margin-bottom:1.5rem; border-bottom:1px solid var(--border); padding-bottom:0.75rem;">
            <i class="fas fa-user-edit" style="color:var(--primary);"></i> Thông tin cá nhân
        </h2>
        @if($errors->any())
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i><div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div></div>
        @endif
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div class="form-group" style="grid-column:1/-1; display:flex; align-items:center; gap:1.5rem;">
                    <img src="{{ $user->avatar_url }}" alt="" id="avatarPreview" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid var(--primary-light);">
                    <div>
                        <label class="form-label">Ảnh đại diện</label>
                        <input type="file" name="avatar" accept="image/*" class="form-control" onchange="previewAvatar(this)" style="padding:0.4rem;">
                        <p style="font-size:0.75rem; color:var(--text-muted); margin-top:0.3rem;">JPG, PNG, WebP - Tối đa 2MB</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Họ và tên *</label>
                    <input type="text" name="name" class="form-control {{ $errors->has('name')?'is-invalid':'' }}" value="{{ old('name', $user->name) }}" required>
                    @if($errors->has('name'))<div class="invalid-feedback">{{ $errors->first('name') }}</div>@endif
                </div>
                <div class="form-group">
                    <label class="form-label">Số điện thoại</label>
                    <input type="tel" name="phone" class="form-control {{ $errors->has('phone')?'is-invalid':'' }}" value="{{ old('phone', $user->phone) }}">
                    @if($errors->has('phone'))<div class="invalid-feedback">{{ $errors->first('phone') }}</div>@endif
                </div>
                <div class="form-group">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $user->profile?->address) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Thành phố</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city', $user->profile?->city) }}">
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Giới thiệu bản thân</label>
                    <textarea name="bio" class="form-control" placeholder="Vài dòng về bạn...">{{ old('bio', $user->profile?->bio) }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu thay đổi</button>
        </form>
    </div>

    <!-- Change Password Form -->
    <div class="card" style="padding:2rem;">
        <h2 style="font-size:1.1rem; font-weight:800; margin-bottom:1.5rem; border-bottom:1px solid var(--border); padding-bottom:0.75rem;">
            <i class="fas fa-lock" style="color:var(--primary);"></i> Đổi mật khẩu
        </h2>
        <form action="{{ route('profile.password') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Mật khẩu hiện tại *</label>
                <input type="password" name="current_password" class="form-control {{ $errors->has('current_password')?'is-invalid':'' }}" required>
                @if($errors->has('current_password'))<div class="invalid-feedback">{{ $errors->first('current_password') }}</div>@endif
            </div>
            <div class="form-group">
                <label class="form-label">Mật khẩu mới *</label>
                <input type="password" name="password" class="form-control {{ $errors->has('password')?'is-invalid':'' }}" required>
                @if($errors->has('password'))<div class="invalid-feedback">{{ $errors->first('password') }}</div>@endif
            </div>
            <div class="form-group">
                <label class="form-label">Xác nhận mật khẩu mới *</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning"><i class="fas fa-key"></i> Đổi mật khẩu</button>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
