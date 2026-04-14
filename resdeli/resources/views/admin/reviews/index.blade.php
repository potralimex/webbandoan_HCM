@extends('layouts.admin')
@section('title', 'Quản lý Đánh giá')
@section('breadcrumb') Đánh giá @endsection
@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div class="d-flex gap-1">
        <a href="{{ route('admin.reviews.index') }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-light' }} btn-sm">Tất cả</a>
        <a href="{{ route('admin.reviews.index') }}?status=pending" class="btn {{ request('status')=='pending' ? 'btn-warning' : 'btn-light' }} btn-sm">⏳ Chờ duyệt</a>
        <a href="{{ route('admin.reviews.index') }}?status=approved" class="btn {{ request('status')=='approved' ? 'btn-success' : 'btn-light' }} btn-sm">✅ Đã duyệt</a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Người dùng</th>
                    <th>Nhà hàng</th>
                    <th>Rating</th>
                    <th>Nhận xét</th>
                    <th>Trạng thái</th>
                    <th>Ngày</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $review->user->avatar_url }}" alt="" style="width:32px;height:32px;border-radius:50%;">
                            <span style="font-size:0.875rem; font-weight:600;">{{ $review->user->name }}</span>
                        </div>
                    </td>
                    <td style="font-size:0.875rem;">{{ $review->restaurant->name }}</td>
                    <td>
                        <span class="badge badge-warning">
                            @for($i=1;$i<=$review->rating;$i++)★@endfor {{ $review->rating }}/5
                        </span>
                    </td>
                    <td style="font-size:0.8rem; max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $review->comment ?? '—' }}
                    </td>
                    <td>
                        <span class="badge {{ $review->is_approved ? 'badge-success' : 'badge-warning' }}">
                            {{ $review->is_approved ? 'Đã duyệt' : 'Chờ duyệt' }}
                        </span>
                    </td>
                    <td style="font-size:0.78rem; color:var(--text-muted);">{{ $review->created_at->format('d/m/Y') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            @if(!$review->is_approved)
                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" title="Duyệt"><i class="fas fa-check"></i></button>
                            </form>
                            @endif
                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Xóa đánh giá này?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Xóa"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center" style="padding:3rem; color:var(--text-muted);">Không có đánh giá nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<ul class="pagination">
    @if($reviews->onFirstPage())<li class="disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>
    @else<li><a class="page-link" href="{{ $reviews->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a></li>@endif
    @foreach($reviews->getUrlRange(1,$reviews->lastPage()) as $page => $url)
        <li class="{{ $page==$reviews->currentPage()?'active':'' }}"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
    @endforeach
    @if($reviews->hasMorePages())<li><a class="page-link" href="{{ $reviews->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a></li>
    @else<li class="disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></li>@endif
</ul>
@endsection
