@extends('layouts.admin')
@section('title', 'Quản lý Thực đơn')
@section('breadcrumb') Thực đơn @endsection
@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <form action="{{ route('admin.menu-items.index') }}" method="GET" class="d-flex gap-1" style="flex-wrap:wrap;">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" style="width:200px;" placeholder="Tìm món ăn...">
        <select name="restaurant_id" class="form-control" style="width:auto;">
            <option value="">Tất cả nhà hàng</option>
            @foreach($restaurants as $r)
                <option value="{{ $r->id }}" {{ request('restaurant_id')==$r->id ? 'selected' : '' }}>{{ $r->name }}</option>
            @endforeach
        </select>
        <select name="category_id" class="form-control" style="width:auto;">
            <option value="">Tất cả danh mục</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}" {{ request('category_id')==$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-light"><i class="fas fa-search"></i></button>
        @if(request()->hasAny(['search','restaurant_id','category_id']))<a href="{{ route('admin.menu-items.index') }}" class="btn btn-light">✕</a>@endif
    </form>
    <a href="{{ route('admin.menu-items.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm món</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Món ăn</th>
                    <th>Nhà hàng</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $item->image_url }}" alt="" class="img-thumbnail" onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=100&h=100&fit=crop'">
                            <div>
                                <div style="font-weight:700; font-size:0.875rem;">{{ $item->name }}</div>
                                @if($item->is_featured)<span class="badge badge-warning">⭐ Nổi bật</span>@endif
                            </div>
                        </div>
                    </td>
                    <td style="font-size:0.85rem;">{{ $item->restaurant->name }}</td>
                    <td><span class="badge badge-primary">{{ $item->category?->name }}</span></td>
                    <td>
                        <div style="font-weight:700; color:var(--primary);">{{ number_format($item->effective_price) }}đ</div>
                        @if($item->sale_price)<div style="font-size:0.75rem; color:var(--text-muted); text-decoration:line-through;">{{ number_format($item->price) }}đ</div>@endif
                    </td>
                    <td>
                        <span class="badge {{ $item->is_available ? 'badge-success' : 'badge-secondary' }}">
                            {{ $item->is_available ? 'Có sẵn' : 'Hết hàng' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.menu-items.edit', $item) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.menu-items.destroy', $item) }}" method="POST" onsubmit="return confirm('Xóa món {{ $item->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center" style="padding:3rem; color:var(--text-muted);">Chưa có món ăn nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<ul class="pagination">
    @if($items->onFirstPage())<li class="disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>
    @else<li><a class="page-link" href="{{ $items->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a></li>@endif
    @foreach($items->getUrlRange(1,$items->lastPage()) as $page => $url)
        <li class="{{ $page==$items->currentPage()?'active':'' }}"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
    @endforeach
    @if($items->hasMorePages())<li><a class="page-link" href="{{ $items->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a></li>
    @else<li class="disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></li>@endif
</ul>
@endsection
