@extends('layouts.admin')
@section('title', 'Thêm Nhà hàng')
@section('breadcrumb') <a href="{{ route('admin.restaurants.index') }}" style="color:inherit; text-decoration:none;">Nhà hàng</a> / Thêm mới @endsection
@section('content')
<div style="max-width:800px;">
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-store" style="color:var(--primary);"></i> Thông tin nhà hàng</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.restaurants.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Tên nhà hàng *</label>
                        <input type="text" name="name" class="form-control {{ $errors->has('name')?'is-invalid':'' }}" value="{{ old('name') }}" required>
                        @if($errors->has('name'))<div class="invalid-feedback">{{ $errors->first('name') }}</div>@endif
                    </div>
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Địa chỉ *</label>
                        <input type="text" name="address" class="form-control {{ $errors->has('address')?'is-invalid':'' }}" value="{{ old('address') }}" required>
                        @if($errors->has('address'))<div class="invalid-feedback">{{ $errors->first('address') }}</div>@endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">Thành phố *</label>
                        <input type="text" name="city" class="form-control {{ $errors->has('city')?'is-invalid':'' }}" value="{{ old('city') }}" required placeholder="Hồ Chí Minh">
                        @if($errors->has('city'))<div class="invalid-feedback">{{ $errors->first('city') }}</div>@endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">Số điện thoại *</label>
                        <input type="text" name="phone" class="form-control {{ $errors->has('phone')?'is-invalid':'' }}" value="{{ old('phone') }}" required>
                        @if($errors->has('phone'))<div class="invalid-feedback">{{ $errors->first('phone') }}</div>@endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Thời gian giao (phút) *</label>
                        <input type="number" name="delivery_time" class="form-control" value="{{ old('delivery_time', 30) }}" min="5" max="120" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phí giao hàng (đ) *</label>
                        <input type="number" name="delivery_fee" class="form-control" value="{{ old('delivery_fee', 15000) }}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Đơn tối thiểu (đ) *</label>
                        <input type="number" name="min_order" class="form-control" value="{{ old('min_order', 50000) }}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ảnh nhà hàng</label>
                        <input type="file" name="image" accept="image/*" class="form-control" onchange="previewImg(this,'imgPreview')">
                        <img id="imgPreview" src="" style="display:none; margin-top:0.75rem; max-height:150px; border-radius:var(--radius-sm);">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Giờ mở cửa *</label>
                        <input type="time" name="open_time" class="form-control" value="{{ old('open_time', '08:00') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Giờ đóng cửa *</label>
                        <input type="time" name="close_time" class="form-control" value="{{ old('close_time', '22:00') }}" required>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Tạo nhà hàng</button>
                    <a href="{{ route('admin.restaurants.index') }}" class="btn btn-light"><i class="fas fa-times"></i> Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>function previewImg(input, id) { if(input.files&&input.files[0]){ const r=new FileReader(); r.onload=e=>{const img=document.getElementById(id);img.src=e.target.result;img.style.display='block';}; r.readAsDataURL(input.files[0]); } }</script>
@endsection
