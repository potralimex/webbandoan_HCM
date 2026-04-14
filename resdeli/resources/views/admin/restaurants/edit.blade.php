@extends('layouts.admin')
@section('title', 'Sửa nhà hàng: ' . $restaurant->name)
@section('breadcrumb') <a href="{{ route('admin.restaurants.index') }}" style="color:inherit; text-decoration:none;">Nhà hàng</a> / Sửa @endsection
@section('content')
<div style="max-width:800px;">
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-edit" style="color:var(--primary);"></i> Chỉnh sửa: {{ $restaurant->name }}</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.restaurants.update', $restaurant) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Tên nhà hàng *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $restaurant->name) }}" required>
                    </div>
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $restaurant->description) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Địa chỉ *</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $restaurant->address) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Thành phố *</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city', $restaurant->city) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Số điện thoại *</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $restaurant->phone) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $restaurant->email) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Thời gian giao (phút) *</label>
                        <input type="number" name="delivery_time" class="form-control" value="{{ old('delivery_time', $restaurant->delivery_time) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phí giao hàng (đ) *</label>
                        <input type="number" name="delivery_fee" class="form-control" value="{{ old('delivery_fee', $restaurant->delivery_fee) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Đơn tối thiểu (đ) *</label>
                        <input type="number" name="min_order" class="form-control" value="{{ old('min_order', $restaurant->min_order) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ảnh nhà hàng</label>
                        @if($restaurant->image)
                            <img src="{{ $restaurant->image_url }}" style="height:100px; border-radius:var(--radius-sm); display:block; margin-bottom:0.75rem;">
                        @endif
                        <input type="file" name="image" accept="image/*" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Trạng thái</label>
                        <div style="display:flex; flex-direction:column; gap:0.5rem; margin-top:0.25rem;">
                            <label class="form-check">
                                <input type="checkbox" name="is_open" class="form-check-input" {{ $restaurant->is_open ? 'checked' : '' }} value="1"> Đang mở cửa
                            </label>
                            <label class="form-check">
                                <input type="checkbox" name="is_active" class="form-check-input" {{ $restaurant->is_active ? 'checked' : '' }} value="1"> Đang hoạt động
                            </label>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu thay đổi</button>
                    <a href="{{ route('admin.restaurants.index') }}" class="btn btn-light"><i class="fas fa-times"></i> Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
