@extends('layouts.admin')
@section('title', 'Quản lý Đơn hàng')
@section('breadcrumb') Đơn hàng @endsection
@section('content')
<div class="d-flex align-items-center justify-content-between mb-3" style="flex-wrap:wrap; gap:0.75rem;">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex gap-1" style="flex-wrap:wrap;">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" style="width:200px;" placeholder="Tìm mã đơn...">
        <select name="status" class="form-control" style="width:auto;" onchange="this.form.submit()">
            <option value="">Tất cả trạng thái</option>
            @foreach(['pending'=>'Chờ xác nhận','confirmed'=>'Đã xác nhận','preparing'=>'Đang chuẩn bị','delivering'=>'Đang giao','delivered'=>'Đã giao','cancelled'=>'Đã hủy'] as $key => $label)
                <option value="{{ $key }}" {{ request('status')==$key?'selected':'' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-light"><i class="fas fa-search"></i></button>
        @if(request()->hasAny(['search','status']))<a href="{{ route('admin.orders.index') }}" class="btn btn-light">✕</a>@endif
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Nhà hàng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th>Cập nhật</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>
                        <span style="font-weight:700; font-size:0.8rem; color:var(--primary);">{{ $order->order_number }}</span>
                    </td>
                    <td>
                        <div style="font-weight:600; font-size:0.85rem;">{{ $order->user->name }}</div>
                        <div style="font-size:0.75rem; color:var(--text-muted);">{{ $order->phone }}</div>
                    </td>
                    <td style="font-size:0.85rem;">{{ $order->restaurant->name }}</td>
                    <td style="font-weight:700; color:var(--primary);">{{ number_format($order->total) }}đ</td>
                    <td>{!! $order->status_badge !!}</td>
                    <td style="font-size:0.8rem; color:var(--text-muted);">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="d-flex gap-1">
                            @csrf @method('PUT')
                            <select name="status" class="form-control" style="width:auto; font-size:0.78rem; padding:0.35rem 0.5rem;">
                                @foreach(['pending'=>'Chờ xác nhận','confirmed'=>'Đã xác nhận','preparing'=>'Đang chuẩn bị','delivering'=>'Đang giao','delivered'=>'Đã giao','cancelled'=>'Đã hủy'] as $k => $l)
                                    <option value="{{ $k }}" {{ $order->status==$k?'selected':'' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center" style="padding:3rem; color:var(--text-muted);">Không có đơn hàng nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<ul class="pagination">
    @if($orders->onFirstPage())<li class="disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>
    @else<li><a class="page-link" href="{{ $orders->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a></li>@endif
    @foreach($orders->getUrlRange(1,$orders->lastPage()) as $page => $url)
        <li class="{{ $page==$orders->currentPage()?'active':'' }}"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
    @endforeach
    @if($orders->hasMorePages())<li><a class="page-link" href="{{ $orders->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a></li>
    @else<li class="disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></li>@endif
</ul>
@endsection
