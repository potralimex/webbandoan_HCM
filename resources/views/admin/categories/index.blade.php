@extends('layouts.admin')
@section('title', 'Quản lý Danh mục')
@section('breadcrumb') Danh mục @endsection
@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div></div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm danh mục</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Icon</th>
                    <th>Tên danh mục</th>
                    <th>Slug</th>
                    <th>Số món</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td style="font-size:1.5rem;">{{ $cat->icon ?: '🍽️' }}</td>
                    <td style="font-weight:700;">{{ $cat->name }}</td>
                    <td style="font-size:0.8rem; color:var(--text-muted);">{{ $cat->slug }}</td>
                    <td><span class="badge badge-info">{{ $cat->menu_items_count }} món</span></td>
                    <td><span class="badge {{ $cat->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $cat->is_active ? 'Hoạt động' : 'Ẩn' }}</span></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Xóa danh mục {{ $cat->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center" style="padding:3rem; color:var(--text-muted);">Chưa có danh mục nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<ul class="pagination">
    @if($categories->onFirstPage())<li class="disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>
    @else<li><a class="page-link" href="{{ $categories->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a></li>@endif
    @foreach($categories->getUrlRange(1,$categories->lastPage()) as $page => $url)
        <li class="{{ $page==$categories->currentPage()?'active':'' }}"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
    @endforeach
    @if($categories->hasMorePages())<li><a class="page-link" href="{{ $categories->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a></li>
    @else<li class="disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></li>@endif
</ul>
@endsection
