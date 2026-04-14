@extends('layouts.app')
@section('title', 'Đơn hàng của tôi - ResDeli')
@section('content')
<div class="container section">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 style="font-size:1.6rem; font-weight:800; margin-bottom:0.25rem;"><i class="fas fa-receipt" style="color:var(--primary);"></i> Đơn hàng của tôi</h1>
            <p style="color:var(--text-muted); font-size:0.875rem;">Theo dõi và quản lý đơn hàng</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Đặt thêm</a>
    </div>

    @if($orders->count() > 0)
    <div style="display:flex; flex-direction:column; gap:1rem;">
        @foreach($orders as $order)
        <a href="{{ route('orders.show', $order) }}" style="text-decoration:none; color:inherit;">
        <div class="card" style="padding:1.5rem; display:flex; gap:1.5rem; align-items:center; flex-wrap:wrap; transition:all 0.2s;">
            <img src="{{ $order->restaurant->image_url }}" alt="" style="width:72px; height:72px; border-radius:var(--radius-sm); object-fit:cover; flex-shrink:0;" onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=200&h=200&fit=crop'">
            <div style="flex:1; min-width:0;">
                <div class="d-flex align-items-center justify-content-between" style="flex-wrap:wrap; gap:0.5rem; margin-bottom:0.25rem;">
                    <strong style="font-size:0.95rem;">{{ $order->restaurant->name }}</strong>
                    {!! $order->status_badge !!}
                </div>
                <div style="font-size:0.8rem; color:var(--text-muted); margin-bottom:0.5rem;">
                    Mã: <strong>{{ $order->order_number }}</strong> · {{ $order->created_at->format('d/m/Y H:i') }}
                </div>
                <div style="font-size:0.875rem; color:var(--text-muted);">
                    {{ $order->items->count() }} món · Tổng: <strong style="color:var(--primary);">{{ number_format($order->total) }}đ</strong>
                </div>
            </div>
            <i class="fas fa-chevron-right" style="color:var(--text-muted);"></i>
        </div>
        </a>
        @endforeach
    </div>

    <ul class="pagination">
        @if($orders->onFirstPage())
            <li class="disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>
        @else
            <li><a class="page-link" href="{{ $orders->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a></li>
        @endif
        @foreach($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
            <li class="{{ $page == $orders->currentPage() ? 'active' : '' }}"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
        @endforeach
        @if($orders->hasMorePages())
            <li><a class="page-link" href="{{ $orders->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a></li>
        @else
            <li class="disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></li>
        @endif
    </ul>
    @else
    <div class="text-center" style="padding:5rem 2rem;">
        <div style="font-size:4rem; margin-bottom:1rem;">📋</div>
        <h3 style="color:var(--text-muted); margin-bottom:0.5rem;">Chưa có đơn hàng nào</h3>
        <p style="color:var(--text-muted); margin-bottom:1.5rem;">Đặt đồ ăn ngay để bắt đầu!</p>
        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">🍜 Khám phá nhà hàng</a>
    </div>
    @endif
</div>
@endsection
