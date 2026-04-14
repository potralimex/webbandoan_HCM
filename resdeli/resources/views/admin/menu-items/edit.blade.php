@extends('layouts.admin')
@section('title', 'Sửa món: ' . $menuItem->name)
@section('breadcrumb') <a href="{{ route('admin.menu-items.index') }}" style="color:inherit;text-decoration:none;">Thực đơn</a> / Sửa @endsection
@section('content')
<div style="max-width:800px;">
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-edit" style="color:var(--primary);"></i> Chỉnh sửa: {{ $menuItem->name }}</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.menu-items.update', $menuItem) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Tên món ăn *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $menuItem->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nhà hàng *</label>
                        <select name="restaurant_id" class="form-control" required>
                            @foreach($restaurants as $r)
                                <option value="{{ $r->id }}" {{ (old('restaurant_id',$menuItem->restaurant_id))==$r->id?'selected':'' }}>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Danh mục *</label>
                        <select name="category_id" class="form-control" required>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" {{ (old('category_id',$menuItem->category_id))==$c->id?'selected':'' }}>{{ $c->icon }} {{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $menuItem->description) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Giá bán (đ) *</label>
                        <input type="number" name="price" class="form-control" value="{{ old('price', $menuItem->price) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Giá khuyến mãi (đ)</label>
                        <input type="number" name="sale_price" class="form-control" value="{{ old('sale_price', $menuItem->sale_price) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Thời gian chuẩn bị (phút) *</label>
                        <input type="number" name="prep_time" class="form-control" value="{{ old('prep_time', $menuItem->prep_time) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Calo (kcal)</label>
                        <input type="number" name="calories" class="form-control" value="{{ old('calories', $menuItem->calories) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ảnh mới</label>
                        @if($menuItem->image)
                            <img src="{{ $menuItem->image_url }}" style="height:80px; border-radius:var(--radius-sm); display:block; margin-bottom:0.5rem;">
                        @endif
                        <input type="file" name="image" accept="image/*" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tags</label>
                        <div style="display:flex; flex-direction:column; gap:0.5rem;">
                            @foreach($tags as $tag)
                            <label class="form-check">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="form-check-input"
                                    {{ in_array($tag->id, old('tags', $menuItem->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <span style="background:{{ $tag->color }}20; color:{{ $tag->color }}; padding:0.2rem 0.6rem; border-radius:50px; font-size:0.8rem; font-weight:600;">{{ $tag->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Tùy chọn</label>
                        <div style="display:flex; flex-direction:column; gap:0.5rem;">
                            <label class="form-check">
                                <input type="checkbox" name="is_available" class="form-check-input" value="1" {{ old('is_available',$menuItem->is_available) ? 'checked' : '' }}> Còn hàng
                            </label>
                            <label class="form-check">
                                <input type="checkbox" name="is_featured" class="form-check-input" value="1" {{ old('is_featured',$menuItem->is_featured) ? 'checked' : '' }}> Món nổi bật
                            </label>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu thay đổi</button>
                    <a href="{{ route('admin.menu-items.index') }}" class="btn btn-light"><i class="fas fa-times"></i> Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
