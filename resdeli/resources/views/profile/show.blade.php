@extends('layouts.app')
@section('title', 'Hồ sơ - ResDeli')
@section('content')
<div class="container" style="padding: 2rem 1rem;">
    <div style="display:grid; grid-template-columns:280px 1fr; gap:2rem; align-items:start;">
        <!-- Sidebar -->
        <div>
            <div class="card" style="padding:2rem; text-align:center; margin-bottom:1rem;">
                <div style="position:relative; display:inline-block; margin-bottom:1rem;">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width:100px; height:100px; border-radius:50%; object-fit:cover; border:4px solid var(--primary-light);">
                </div>
                <h2 style="font-size:1.1rem; font-weight:700; margin-bottom:0.25rem;">{{ $user->name }}</h2>
                <p style="color:var(--text-muted); font-size:0.8rem; margin-bottom:0.75rem;">{{ $user->email }}</p>
                <span class="badge badge-primary">
                    @match($user->role)
                        @case('admin') 👑 Admin @break
                        @case('restaurant_owner') 🏪 Chủ nhà hàng @break
                        @default 🛒 Khách hàng
                    @endmatch
                </span>
            </div>
            <div class="card" style="overflow:hidden;">
                <a href="{{ route('profile.show') }}" class="nav-link" style="display:flex; align-items:center; gap:0.75rem; padding:0.9rem 1.25rem; border-bottom:1px solid var(--border); color:var(--primary); font-weight:600;">
                    <i class="fas fa-user" style="width:18px;"></i> Hồ sơ
                </a>
                <a href="{{ route('profile.edit') }}" style="display:flex; align-items:center; gap:0.75rem; padding:0.9rem 1.25rem; border-bottom:1px solid var(--border); text-decoration:none; color:var(--text); font-size:0.875rem; transition:background 0.15s;" onmouseover="this.style.background='var(--bg)'" onmouseout="this.style.background=''">
                    <i class="fas fa-edit" style="width:18px; color:var(--text-muted);"></i> Chỉnh sửa hồ sơ
                </a>
                <a href="{{ route('orders.index') }}" style="display:flex; align-items:center; gap:0.75rem; padding:0.9rem 1.25rem; border-bottom:1px solid var(--border); text-decoration:none; color:var(--text); font-size:0.875rem; transition:background 0.15s;" onmouseover="this.style.background='var(--bg)'" onmouseout="this.style.background=''">
                    <i class="fas fa-receipt" style="width:18px; color:var(--text-muted);"></i> Đơn hàng
                </a>
                @if($user->isAdmin())
                <a href="{{ route('admin.dashboard') }}" style="display:flex; align-items:center; gap:0.75rem; padding:0.9rem 1.25rem; border-bottom:1px solid var(--border); text-decoration:none; color:var(--text); font-size:0.875rem; transition:background 0.15s;" onmouseover="this.style.background='var(--bg)'" onmouseout="this.style.background=''">
                    <i class="fas fa-cog" style="width:18px; color:var(--text-muted);"></i> Quản trị
                </a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="display:flex; align-items:center; gap:0.75rem; padding:0.9rem 1.25rem; background:none; border:none; color:var(--danger); font-size:0.875rem; cursor:pointer; width:100%; transition:background 0.15s;" onmouseover="this.style.background='#fde8ec'" onmouseout="this.style.background=''">
                        <i class="fas fa-sign-out-alt" style="width:18px;"></i> Đăng xuất
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div>
            <!-- Profile Info -->
            <div class="card" style="padding:2rem; margin-bottom:1.5rem;">
                <h2 style="font-size:1.1rem; font-weight:800; margin-bottom:1.5rem; padding-bottom:0.75rem; border-bottom:1px solid var(--border);">
                    <i class="fas fa-user" style="color:var(--primary);"></i> Thông tin cá nhân
                </h2>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                    <div>
                        <p style="font-size:0.78rem; font-weight:600; color:var(--text-muted); margin-bottom:0.3rem; text-transform:uppercase; letter-spacing:0.5px;">Họ và tên</p>
                        <p style="font-weight:600;">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p style="font-size:0.78rem; font-weight:600; color:var(--text-muted); margin-bottom:0.3rem; text-transform:uppercase; letter-spacing:0.5px;">Email</p>
                        <p>{{ $user->email }}</p>
                    </div>
                    <div>
                        <p style="font-size:0.78rem; font-weight:600; color:var(--text-muted); margin-bottom:0.3rem; text-transform:uppercase; letter-spacing:0.5px;">Điện thoại</p>
                        <p>{{ $user->phone ?: '—' }}</p>
                    </div>
                    <div>
                        <p style="font-size:0.78rem; font-weight:600; color:var(--text-muted); margin-bottom:0.3rem; text-transform:uppercase; letter-spacing:0.5px;">Thành phố</p>
                        <p>{{ $user->profile?->city ?: '—' }}</p>
                    </div>
                    @if($user->profile?->bio)
                    <div style="grid-column:1/-1;">
                        <p style="font-size:0.78rem; font-weight:600; color:var(--text-muted); margin-bottom:0.3rem; text-transform:uppercase; letter-spacing:0.5px;">Giới thiệu</p>
                        <p>{{ $user->profile->bio }}</p>
                    </div>
                    @endif
                </div>
                <div class="mt-3">
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline btn-sm"><i class="fas fa-edit"></i> Chỉnh sửa</a>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="card" style="padding:2rem; margin-bottom:1.5rem;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h2 style="font-size:1.1rem; font-weight:800;"><i class="fas fa-receipt" style="color:var(--primary);"></i> Đơn hàng gần đây</h2>
                    <a href="{{ route('orders.index') }}" class="btn btn-light btn-sm">Xem tất cả</a>
                </div>
                @if($orders->count() > 0)
                @foreach($orders as $order)
                <a href="{{ route('orders.show', $order) }}" style="display:flex; gap:1rem; align-items:center; padding:0.9rem 0; border-bottom:1px solid var(--border); text-decoration:none; color:inherit; transition:background 0.15s;">
                    <img src="{{ $order->restaurant->image_url }}" alt="" style="width:48px;height:48px;border-radius:var(--radius-sm);object-fit:cover;" onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=100&h=100&fit=crop'">
                    <div style="flex:1;">
                        <strong style="font-size:0.875rem;">{{ $order->restaurant->name }}</strong>
                        <div style="font-size:0.78rem; color:var(--text-muted);">{{ $order->order_number }} · {{ $order->created_at->format('d/m/Y') }}</div>
                    </div>
                    <div style="text-align:right;">
                        {!! $order->status_badge !!}
                        <div style="font-size:0.875rem; font-weight:700; color:var(--primary); margin-top:0.3rem;">{{ number_format($order->total) }}đ</div>
                    </div>
                </a>
                @endforeach
                @else
                <p style="color:var(--text-muted); text-align:center; padding:2rem;">Chưa có đơn hàng nào</p>
                @endif
            </div>

            <!-- Favorites -->
            @if($favorites->count() > 0)
            <div class="card" style="padding:2rem;">
                <h2 style="font-size:1.1rem; font-weight:800; margin-bottom:1.25rem;"><i class="fas fa-heart" style="color:var(--danger);"></i> Nhà hàng yêu thích</h2>
                <div class="grid grid-3" style="gap:1rem;">
                    @foreach($favorites as $r)
                    <a href="{{ route('restaurants.show', $r->slug) }}" style="text-decoration:none; color:inherit;">
                        <div style="border:1px solid var(--border); border-radius:var(--radius-sm); overflow:hidden; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'">
                            <img src="{{ $r->image_url }}" alt="{{ $r->name }}" style="width:100%; height:90px; object-fit:cover;" onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=300&h=200&fit=crop'">
                            <div style="padding:0.75rem;">
                                <strong style="font-size:0.8rem;">{{ $r->name }}</strong>
                                <div style="font-size:0.75rem; color:var(--text-muted);">⭐ {{ number_format($r->rating, 1) }}</div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
