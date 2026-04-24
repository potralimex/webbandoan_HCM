@extends('layouts.admin')
@section('title', 'Quản lý Nhà hàng')
@section('breadcrumb') Nhà hàng @endsection
@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <form action="{{ route('admin.restaurants.index') }}" method="GET" class="d-flex gap-1">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" style="width:260px;" placeholder="Tìm nhà hàng...">
        <button type="submit" class="btn btn-light"><i class="fas fa-search"></i></button>
        @if(request('search'))<a href="{{ route('admin.restaurants.index') }}" class="btn btn-light">✕</a>@endif
    </form>
    <a href="{{ route('admin.restaurants.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm nhà hàng</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nhà hàng</th>
                    <th>Chủ sở hữu</th>
                    <th>Thành phố</th>
                    <th>Rating</th>
                    <th>Trạng thái</th>
                    <th>Giờ mở cửa</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($restaurants as $r)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $r->image_url }}" alt="" class="img-thumbnail" onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=100&h=100&fit=crop'">
                            <div>
                                <div style="font-weight:700; font-size:0.875rem;">{{ $r->name }}</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);">{{ $r->phone }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:0.875rem;">{{ $r->owner->name }}</td>
                    <td>
                        <span class="badge badge-primary">{{ $r->city }}</span>
                    </td>
                    <td><span class="badge badge-warning">⭐ {{ number_format($r->rating, 1) }}</span></td>
                    <td>
                        <span class="badge {{ $r->is_active ? 'badge-success' : 'badge-danger' }}">
                            {{ $r->is_active ? 'Hoạt động' : 'Tạm dừng' }}
                        </span>
                    </td>
                    <td style="font-size:0.8rem;">
                        <span class="badge {{ $r->is_open ? 'badge-success' : 'badge-secondary' }}">
                            {{ $r->is_open ? 'Đang mở' : 'Đóng' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('restaurants.show', $r->slug) }}" class="btn btn-light btn-sm" target="_blank" title="Xem">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.restaurants.edit', $r) }}" class="btn btn-primary btn-sm" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.restaurants.destroy', $r) }}" method="POST" onsubmit="return confirm('Xóa nhà hàng {{ $r->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Xóa"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center" style="padding:3rem; color:var(--text-muted);">Chưa có nhà hàng nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<ul class="pagination">
    @if($restaurants->onFirstPage())<li class="disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>
    @else<li><a class="page-link" href="{{ $restaurants->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a></li>@endif
    @foreach($restaurants->getUrlRange(1,$restaurants->lastPage()) as $page => $url)
        <li class="{{ $page==$restaurants->currentPage()?'active':'' }}"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
    @endforeach
    @if($restaurants->hasMorePages())<li><a class="page-link" href="{{ $restaurants->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a></li>
    @else<li class="disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></li>@endif
</ul>
@endsection
