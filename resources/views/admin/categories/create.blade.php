@extends('layouts.admin')
@section('title', 'Thêm Danh mục')
@section('breadcrumb') <a href="{{ route('admin.categories') }}" style="color:inherit;text-decoration:none;">Danh mục</a> / Thêm mới @endsection
@section('content')
<div style="max-width:500px;">
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-tag" style="color:var(--primary);"></i> Thêm danh mục mới</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Tên danh mục *</label>
                    <input type="text" name="name" class="form-control {{ $errors->has('name')?'is-invalid':'' }}" value="{{ old('name') }}" required>
                    @if($errors->has('name'))<div class="invalid-feedback">{{ $errors->first('name') }}</div>@endif
                </div>
                <div class="form-group">
                    <label class="form-label">Icon (emoji)</label>
                    <input type="text" name="icon" class="form-control" value="{{ old('icon') }}" placeholder="🍜" maxlength="10" style="font-size:1.5rem;">
                </div>
                <div class="form-group">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" class="form-control" placeholder="Mô tả ngắn về danh mục...">{{ old('description') }}</textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Tạo danh mục</button>
                    <a href="{{ route('admin.categories') }}" class="btn btn-light"><i class="fas fa-times"></i> Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
