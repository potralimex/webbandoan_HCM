@extends('layouts.admin')
@section('title', 'Quản lý Người dùng')
@section('breadcrumb') Người dùng @endsection
@section('content')
<div class="d-flex align-items-center justify-content-between mb-3" style="flex-wrap:wrap; gap:0.75rem;">
    <form action="{{ route('admin.users') }}" method="GET" class="d-flex gap-1" style="flex-wrap:wrap;">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" style="width:240px;" placeholder="Tìm tên, email...">
        <select name="role" class="form-control" style="width:auto;" onchange="this.form.submit()">
            <option value="">Tất cả vai trò</option>
            <option value="customer" {{ request('role')=='customer'?'selected':'' }}>Khách hàng</option>
            <option value="restaurant_owner" {{ request('role')=='restaurant_owner'?'selected':'' }}>Chủ nhà hàng</option>
            <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
        </select>
        <button type="submit" class="btn btn-light"><i class="fas fa-search"></i></button>
        @if(request()->hasAny(['search','role']))<a href="{{ route('admin.users') }}" class="btn btn-light">✕</a>@endif
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Người dùng</th>
                    <th>Vai trò</th>
                    <th>Điện thoại</th>
                    <th>Đã xác minh</th>
                    <th>Ngày đăng ký</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $user->avatar_url }}" alt="" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                            <div>
                                <div style="font-weight:700; font-size:0.875rem;">{{ $user->name }}</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $user->isAdmin() ? 'badge-danger' : ($user->isRestaurantOwner() ? 'badge-info' : 'badge-success') }}">
                            {{ $user->isAdmin() ? '👑 Admin' : ($user->isRestaurantOwner() ? '🏪 Chủ nhà hàng' : '🛒 Khách hàng') }}
                        </span>
                    </td>
                    <td style="font-size:0.875rem;">{{ $user->phone ?: '—' }}</td>
                    <td>
                        @if($user->email_verified_at)
                            <span class="badge badge-success">✓ Đã xác minh</span>
                        @else
                            <span class="badge badge-secondary">Chưa xác minh</span>
                        @endif
                    </td>
                    <td style="font-size:0.78rem; color:var(--text-muted);">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td>
                        @if(!$user->isAdmin())
                        <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $user->email_verified_at ? 'btn-warning' : 'btn-success' }}">
                                {{ $user->email_verified_at ? '🔒 Khóa' : '🔓 Mở' }}
                            </button>
                        </form>
                        @else
                        <span class="badge badge-secondary">N/A</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center" style="padding:3rem; color:var(--text-muted);">Không có người dùng nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<ul class="pagination">
    @if($users->onFirstPage())<li class="disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>
    @else<li><a class="page-link" href="{{ $users->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a></li>@endif
    @foreach($users->getUrlRange(1,$users->lastPage()) as $page => $url)
        <li class="{{ $page==$users->currentPage()?'active':'' }}"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
    @endforeach
    @if($users->hasMorePages())<li><a class="page-link" href="{{ $users->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a></li>
    @else<li class="disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></li>@endif
</ul>
@endsection
