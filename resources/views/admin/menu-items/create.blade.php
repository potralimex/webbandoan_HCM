@extends('layouts.admin')
@section('title', 'Thêm Món ăn')
@section('breadcrumb') <a href="{{ route('admin.menu-items.index') }}" style="color:inherit;text-decoration:none;">Thực đơn</a> / Thêm mới @endsection
@section('content')
<div style="max-width:800px;">
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-utensils" style="color:var(--primary);"></i> Thêm món ăn mới</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.menu-items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Tên món ăn *</label>
                        <input type="text" name="name" class="form-control {{ $errors->has('name')?'is-invalid':'' }}" value="{{ old('name') }}" required>
                        @if($errors->has('name'))<div class="invalid-feedback">{{ $errors->first('name') }}</div>@endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nhà hàng *</label>
                        <select name="restaurant_id" class="form-control {{ $errors->has('restaurant_id')?'is-invalid':'' }}" required>
                            <option value="">-- Chọn nhà hàng --</option>
                            @foreach($restaurants as $r)
                                <option value="{{ $r->id }}" {{ old('restaurant_id')==$r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('restaurant_id'))<div class="invalid-feedback">{{ $errors->first('restaurant_id') }}</div>@endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">Danh mục *</label>
                        <select name="category_id" class="form-control {{ $errors->has('category_id')?'is-invalid':'' }}" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" {{ old('category_id')==$c->id ? 'selected' : '' }}>{{ $c->icon }} {{ $c->name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('category_id'))<div class="invalid-feedback">{{ $errors->first('category_id') }}</div>@endif
                    </div>
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Giá bán (đ) *</label>
                        <input type="number" name="price" class="form-control {{ $errors->has('price')?'is-invalid':'' }}" value="{{ old('price') }}" min="1000" required>
                        @if($errors->has('price'))<div class="invalid-feedback">{{ $errors->first('price') }}</div>@endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">Giá khuyến mãi (đ)</label>
                        <input type="number" name="sale_price" class="form-control {{ $errors->has('sale_price')?'is-invalid':'' }}" value="{{ old('sale_price') }}" min="0">
                        @if($errors->has('sale_price'))<div class="invalid-feedback">{{ $errors->first('sale_price') }}</div>@endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">Thời gian chuẩn bị (phút) *</label>
                        <input type="number" name="prep_time" class="form-control" value="{{ old('prep_time', 15) }}" min="1" max="120" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Calo (kcal)</label>
                        <input type="number" name="calories" class="form-control" value="{{ old('calories') }}" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ảnh món ăn</label>
                        <input type="file" name="image" accept="image/*" class="form-control" onchange="previewImg(this,'imgPreview')">
                        <img id="imgPreview" src="" style="display:none; margin-top:0.75rem; max-height:150px; border-radius:var(--radius-sm);">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tags</label>
                        <div style="display:flex; flex-direction:column; gap:0.5rem;">
                            @foreach($tags as $tag)
                            <label class="form-check">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="form-check-input"
                                    {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                                <span style="background:{{ $tag->color }}20; color:{{ $tag->color }}; padding:0.2rem 0.6rem; border-radius:50px; font-size:0.8rem; font-weight:600;">{{ $tag->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tùy chọn</label>
                        <div style="display:flex; flex-direction:column; gap:0.5rem;">
                            <label class="form-check">
                                <input type="checkbox" name="is_available" class="form-check-input" value="1" {{ old('is_available',1) ? 'checked' : '' }}> Còn hàng
                            </label>
                            <label class="form-check">
                                <input type="checkbox" name="is_featured" class="form-check-input" value="1" {{ old('is_featured') ? 'checked' : '' }}> Món nổi bật
                            </label>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Thêm món ăn</button>
                    <a href="{{ route('admin.menu-items.index') }}" class="btn btn-light"><i class="fas fa-times"></i> Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>function previewImg(input, id) { if(input.files&&input.files[0]){ const r=new FileReader(); r.onload=e=>{const img=document.getElementById(id);img.src=e.target.result;img.style.display='block';}; r.readAsDataURL(input.files[0]); } }</script>
@endsection
