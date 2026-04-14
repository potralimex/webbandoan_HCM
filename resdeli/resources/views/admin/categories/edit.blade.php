@extends('layouts.admin')
@section('title', 'Sửa danh mục: ' . $category->name)
@section('breadcrumb') <a href="{{ route('admin.categories') }}" style="color:inherit;text-decoration:none;">Danh mục</a> / Sửa @endsection
@section('content')
<div style="max-width:500px;">
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-edit" style="color:var(--primary);"></i> Sửa danh mục: {{ $category->name }}</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="form-label">Tên danh mục *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Icon (emoji)</label>
                    <input type="text" name="icon" class="form-control" value="{{ old('icon', $category->icon) }}" style="font-size:1.5rem;">
                </div>
                <div class="form-group">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" class="form-control">{{ old('description', $category->description) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ $category->is_active ? 'checked' : '' }}> Đang hoạt động
                    </label>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu thay đổi</button>
                    <a href="{{ route('admin.categories') }}" class="btn btn-light"><i class="fas fa-times"></i> Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
