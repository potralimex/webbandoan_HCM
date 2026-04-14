@extends('layouts.app')

@section('title', 'ResDeli - Trang chủ')

@section('content')
<!-- HERO SECTION -->
<section style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); padding: 5rem 0; position: relative; overflow: hidden;">
    <div style="position:absolute;top:0;left:0;right:0;bottom:0;background:url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1400&q=60') center/cover no-repeat; opacity:0.12;"></div>
    <div class="container" style="position:relative; text-align:center;">
        <h1 style="font-size:3rem; font-weight:800; color:#fff; margin-bottom:1rem; line-height:1.2;">
            Đặt đồ ăn ngon 🍜<br>
            <span style="color:var(--primary);">Giao tận nơi nhanh chóng</span>
        </h1>
        <p style="color:#a0aec0; font-size:1.1rem; max-width:520px; margin:0 auto 2.5rem;">
            Khám phá hàng trăm nhà hàng, món ăn đa dạng. Đặt hàng dễ dàng, giao hàng nhanh, hài lòng đảm bảo.
        </p>

        <form action="{{ route('home') }}" method="GET" style="max-width:560px; margin:0 auto; display:flex; gap:0.75rem; flex-wrap:wrap; justify-content:center;">
            <div style="flex:1; min-width:240px; position:relative;">
                <i class="fas fa-search" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:#6c757d;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm nhà hàng, món ăn, khu vực..." style="width:100%; padding:0.85rem 1rem 0.85rem 2.8rem; border-radius:50px; border:none; font-size:0.95rem; outline:none; box-shadow:0 4px 20px rgba(0,0,0,0.2);">
            </div>
            <button type="submit" class="btn btn-primary" style="border-radius:50px; padding:0.85rem 2rem; font-size:0.95rem;">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
        </form>

        <div style="margin-top:2rem; display:flex; justify-content:center; gap:2.5rem; flex-wrap:wrap;">
            <div style="color:#a0aec0; font-size:0.875rem; display:flex; align-items:center; gap:0.5rem;">
                <i class="fas fa-store" style="color:var(--primary);"></i>
                <strong style="color:#fff;">{{ $restaurants->total() }}+</strong> nhà hàng
            </div>
            <div style="color:#a0aec0; font-size:0.875rem; display:flex; align-items:center; gap:0.5rem;">
                <i class="fas fa-clock" style="color:var(--accent);"></i>
                <strong style="color:#fff;">15-45 phút</strong> giao hàng
            </div>
            <div style="color:#a0aec0; font-size:0.875rem; display:flex; align-items:center; gap:0.5rem;">
                <i class="fas fa-star" style="color:#f7b731;"></i>
                <strong style="color:#fff;">4.8/5</strong> đánh giá
            </div>
        </div>
    </div>
</section>

<!-- CATEGORIES -->
<section class="section" style="padding-top:2.5rem; padding-bottom:1.5rem;">
    <div class="container">
        <div style="display:flex; gap:0.75rem; overflow-x:auto; padding-bottom:0.5rem; -webkit-overflow-scrolling:touch; scrollbar-width:none;">
            <a href="{{ route('home') }}" class="btn {{ !request()->has('search') && !request()->has('city') ? 'btn-primary' : 'btn-light' }}" style="white-space:nowrap; border-radius:50px; flex-shrink:0;">
                🍽️ Tất cả
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('home') }}?search={{ $cat->name }}" class="btn btn-light" style="white-space:nowrap; border-radius:50px; flex-shrink:0;">
                    {{ $cat->icon }} {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- FEATURED RESTAURANTS -->
@if($featured->count() > 0)
<section style="background: linear-gradient(135deg, #fff3ef 0%, #fff 100%); padding: 2.5rem 0;">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h2 style="font-size:1.4rem; font-weight:800;">⭐ Nhà hàng nổi bật</h2>
                <p style="color:var(--text-muted); font-size:0.875rem;">Được đánh giá cao nhất bởi khách hàng</p>
            </div>
        </div>
        <div class="grid grid-3">
            @foreach($featured as $r)
            <a href="{{ route('restaurants.show', $r->slug) }}" style="text-decoration:none; color:inherit;">
                <div class="card" style="position:relative;">
                    <div style="position:absolute; top:0.75rem; left:0.75rem; z-index:2;">
                        <span class="badge" style="background:rgba(255,107,53,0.9); color:#fff; backdrop-filter:blur(4px);">
                            <i class="fas fa-fire"></i> Nổi bật
                        </span>
                    </div>
                    <img src="{{ $r->image_url }}" alt="{{ $r->name }}" class="card-img" style="height:180px;" onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=600&h=400&fit=crop'">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h3 class="card-title" style="margin:0; font-size:1rem;">{{ $r->name }}</h3>
                            <span class="badge badge-warning">⭐ {{ number_format($r->rating, 1) }}</span>
                        </div>
                        <p class="card-text" style="margin-bottom:0.75rem; -webkit-line-clamp:2; display:-webkit-box; -webkit-box-orient:vertical; overflow:hidden;">
                            <i class="fas fa-map-marker-alt" style="color:var(--primary);"></i> {{ $r->city }}
                        </p>
                        <div class="d-flex gap-2" style="font-size:0.8rem; color:var(--text-muted);">
                            <span><i class="fas fa-clock"></i> {{ $r->delivery_time }} phút</span>
                            <span><i class="fas fa-motorcycle"></i> {{ number_format($r->delivery_fee) }}đ</span>
                            <span><i class="fas fa-{{ $r->is_open ? 'circle text-success' : 'circle text-danger' }}"></i> {{ $r->is_open ? 'Đang mở' : 'Đóng cửa' }}</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- MAIN RESTAURANTS LIST -->
<section class="section">
    <div class="container">
        <!-- Filter Bar -->
        <div style="display:flex; gap:1rem; align-items:center; justify-content:space-between; flex-wrap:wrap; margin-bottom:2rem;">
            <div>
                <h2 style="font-size:1.4rem; font-weight:800; margin-bottom:0.25rem;">
                    @if(request('search'))
                        🔍 Kết quả cho "<em style="color:var(--primary);">{{ request('search') }}</em>"
                    @else
                        🏪 Tất cả nhà hàng
                    @endif
                </h2>
                <p style="color:var(--text-muted); font-size:0.875rem;">{{ $restaurants->total() }} nhà hàng</p>
            </div>

            <form action="{{ route('home') }}" method="GET" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center;">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <select name="city" class="form-control" style="width:auto;" onchange="this.form.submit()">
                    <option value="">🌆 Tất cả thành phố</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>
                <select name="sort" class="form-control" style="width:auto;" onchange="this.form.submit()">
                    <option value="rating" {{ $sort == 'rating' ? 'selected' : '' }}>⭐ Đánh giá cao</option>
                    <option value="delivery_fee" {{ $sort == 'delivery_fee' ? 'selected' : '' }}>💸 Phí ship thấp</option>
                    <option value="delivery_time" {{ $sort == 'delivery_time' ? 'selected' : '' }}>⚡ Giao nhanh</option>
                    <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>🆕 Mới nhất</option>
                </select>
                @if(request('search') || request('city') || request('sort'))
                    <a href="{{ route('home') }}" class="btn btn-light btn-sm">✕ Xóa bộ lọc</a>
                @endif
            </form>
        </div>

        @if($restaurants->count() > 0)
        <div class="grid grid-3" id="restaurantGrid">
            @foreach($restaurants as $r)
            <a href="{{ route('restaurants.show', $r->slug) }}" style="text-decoration:none; color:inherit;" class="animate-slideUp">
                <div class="card">
                    <div style="position:relative;">
                        <img src="{{ $r->image_url }}" alt="{{ $r->name }}" class="card-img" onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=600&h=400&fit=crop'">
                        @if(!$r->is_open)
                            <div style="position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;">
                                <span style="color:#fff;font-weight:700;font-size:0.9rem;background:rgba(0,0,0,0.5);padding:0.5rem 1rem;border-radius:50px;">Đóng cửa</span>
                            </div>
                        @endif
                        <div style="position:absolute; top:0.75rem; right:0.75rem;">
                            <span class="badge badge-warning">⭐ {{ number_format($r->rating, 1) }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">{{ $r->name }}</h3>
                        <p style="color:var(--text-muted); font-size:0.8rem; margin-bottom:0.75rem;">
                            <i class="fas fa-map-marker-alt" style="color:var(--primary);"></i> {{ $r->address }}, {{ $r->city }}
                        </p>
                        <div style="display:flex; gap:1rem; font-size:0.8rem; color:var(--text-muted); flex-wrap:wrap;">
                            <span><i class="fas fa-clock"></i> {{ $r->delivery_time }} phút</span>
                            <span><i class="fas fa-motorcycle"></i> {{ $r->delivery_fee > 0 ? number_format($r->delivery_fee).'đ' : 'Miễn phí' }}</span>
                            <span>Tối thiểu: {{ number_format($r->min_order) }}đ</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <ul class="pagination">
            @if($restaurants->onFirstPage())
                <li class="disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>
            @else
                <li><a class="page-link" href="{{ $restaurants->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a></li>
            @endif

            @for($i = max(1, $restaurants->currentPage()-2); $i <= min($restaurants->lastPage(), $restaurants->currentPage()+2); $i++)
                <li class="{{ $i == $restaurants->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $restaurants->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            @if($restaurants->hasMorePages())
                <li><a class="page-link" href="{{ $restaurants->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a></li>
            @else
                <li class="disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></li>
            @endif
        </ul>
        @else
        <div class="text-center" style="padding:4rem 2rem;">
            <div style="font-size:4rem; margin-bottom:1rem;">🍽️</div>
            <h3 style="color:var(--text-muted); margin-bottom:0.5rem;">Không tìm thấy nhà hàng</h3>
            <p style="color:var(--text-muted);">Thử tìm kiếm với từ khóa khác</p>
            <a href="{{ route('home') }}" class="btn btn-primary mt-2">Xem tất cả</a>
        </div>
        @endif
    </div>
</section>

<!-- HOW IT WORKS -->
<section style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); padding: 4rem 0;">
    <div class="container text-center">
        <h2 style="color:#fff; font-size:1.8rem; font-weight:800; margin-bottom:0.5rem;">Cách thức hoạt động</h2>
        <p style="color:#a0aec0; margin-bottom:3rem;">Đặt đồ ăn chỉ trong 3 bước đơn giản</p>
        <div class="grid grid-3" style="gap:2rem;">
            <div>
                <div style="width:70px; height:70px; background:rgba(255,107,53,0.15); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem; border:2px solid rgba(255,107,53,0.3);">
                    <i class="fas fa-search" style="font-size:1.5rem; color:var(--primary);"></i>
                </div>
                <h3 style="color:#fff; font-size:1.1rem; margin-bottom:0.5rem;">1. Tìm kiếm</h3>
                <p style="color:#a0aec0; font-size:0.875rem;">Tìm nhà hàng hoặc món ăn yêu thích trong khu vực của bạn</p>
            </div>
            <div>
                <div style="width:70px; height:70px; background:rgba(247,183,49,0.15); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem; border:2px solid rgba(247,183,49,0.3);">
                    <i class="fas fa-shopping-cart" style="font-size:1.5rem; color:var(--accent);"></i>
                </div>
                <h3 style="color:#fff; font-size:1.1rem; margin-bottom:0.5rem;">2. Chọn món</h3>
                <p style="color:#a0aec0; font-size:0.875rem;">Thêm các món vào giỏ hàng, điền địa chỉ và đặt hàng</p>
            </div>
            <div>
                <div style="width:70px; height:70px; background:rgba(32,191,107,0.15); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem; border:2px solid rgba(32,191,107,0.3);">
                    <i class="fas fa-motorcycle" style="font-size:1.5rem; color:var(--success);"></i>
                </div>
                <h3 style="color:#fff; font-size:1.1rem; margin-bottom:0.5rem;">3. Nhận hàng</h3>
                <p style="color:#a0aec0; font-size:0.875rem;">Shipper sẽ giao đến tận nơi, thưởng thức ngay</p>
            </div>
        </div>
    </div>
</section>
@endsection
