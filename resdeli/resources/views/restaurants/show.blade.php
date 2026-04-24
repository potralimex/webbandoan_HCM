@extends('layouts.app')
@section('title', $restaurant->name . ' - ResDeli')
@section('description', $restaurant->description)

@section('styles')
<style>
.menu-category-nav { position: sticky; top: 64px; background: #fff; z-index: 100; border-bottom: 1px solid var(--border); }
.menu-category-nav-inner { display: flex; gap: 0.25rem; overflow-x: auto; padding: 0.75rem 0; -webkit-overflow-scrolling: touch; scrollbar-width: none; }
.cat-btn { padding: 0.45rem 1.1rem; border-radius: 50px; border: 1.5px solid var(--border); background: #fff; font-size: 0.85rem; font-weight: 600; cursor: pointer; white-space: nowrap; transition: all 0.2s; text-decoration: none; color: var(--text); }
.cat-btn.active, .cat-btn:hover { background: var(--primary); color: #fff; border-color: var(--primary); }

.menu-item-card {
    display: flex;
    gap: 1rem;
    align-items: center;
    background: #fff;
    border-radius: var(--radius-sm);
    padding: 1rem;
    margin-bottom: 0.75rem;
    border: 1px solid var(--border);
    transition: all 0.2s;
}
.menu-item-card:hover { border-color: var(--primary); box-shadow: 0 2px 12px rgba(255,107,53,0.12); }
.menu-item-img { width: 90px; height: 90px; border-radius: var(--radius-sm); object-fit: cover; flex-shrink: 0; }
.menu-item-info { flex: 1; min-width: 0; }
.menu-item-name { font-weight: 700; font-size: 0.9rem; margin-bottom: 0.25rem; }
.menu-item-desc { color: var(--text-muted); font-size: 0.8rem; margin-bottom: 0.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.menu-item-price { font-size: 1rem; font-weight: 700; color: var(--primary); }
.menu-item-price del { font-size: 0.78rem; font-weight: 400; color: var(--text-muted); text-decoration: line-through; margin-left: 0.5rem; }

.cart-sidebar { background: #fff; border-radius: var(--radius); box-shadow: var(--shadow); padding: 1.5rem; position: sticky; top: 80px; max-height: calc(100vh - 100px); overflow-y: auto; }
.cart-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid var(--border); }
.cart-item:last-child { border: none; }
.qty-control { display: flex; align-items: center; gap: 0.4rem; }
.qty-btn { width: 28px; height: 28px; border-radius: 50%; border: 1.5px solid var(--border); background: #fff; cursor: pointer; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
.qty-btn:hover { border-color: var(--primary); color: var(--primary); }
.qty-input { width: 36px; text-align: center; font-weight: 700; font-size: 0.9rem; border: none; outline: none; }

.star-select { display: flex; gap: 0.25rem; flex-direction: row-reverse; justify-content: flex-end; }
.star-select input { display: none; }
.star-select label { font-size: 1.8rem; color: #dee2e6; cursor: pointer; transition: color 0.15s; }
.star-select input:checked ~ label, .star-select label:hover, .star-select label:hover ~ label { color: #f7b731; }

@media (max-width: 900px) {
    .content-grid { display: grid; grid-template-columns: 1fr !important; gap: 2rem !important; }
    .cart-sidebar { position: relative; top: 0; max-height: none; margin-top: 2rem; }
}
</style>
@endsection

@section('content')
<!-- Restaurant Header -->
<section style="background:linear-gradient(to bottom,rgba(0,0,0,0.6),rgba(0,0,0,0.75)), url('{{ $restaurant->image_url }}') center/cover no-repeat; padding:3.5rem 0 2.5rem; min-height:280px;">
    <div class="container">
        <div class="d-flex gap-2" style="margin-bottom:1rem;">
            <a href="{{ route('home') }}" style="color:rgba(255,255,255,0.7); text-decoration:none; font-size:0.85rem;">
                <i class="fas fa-home"></i> Trang chủ
            </a>
            <span style="color:rgba(255,255,255,0.4);">/</span>
            <span style="color:#fff; font-size:0.85rem;">{{ $restaurant->name }}</span>
        </div>
        <div style="display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:1.5rem;">
            <div>
                <h1 style="color:#fff; font-size:2.2rem; font-weight:800; margin-bottom:0.5rem;">{{ $restaurant->name }}</h1>
                <div style="display:flex; flex-wrap:wrap; gap:1rem; color:rgba(255,255,255,0.85); font-size:0.875rem;">
                    <span><i class="fas fa-map-marker-alt"></i> {{ $restaurant->address }}, {{ $restaurant->city }}</span>
                    <span><i class="fas fa-star" style="color:#f7b731;"></i> {{ number_format($restaurant->rating, 1) }} đánh giá</span>
                    <span><i class="fas fa-clock"></i> {{ $restaurant->delivery_time }} phút</span>
                    <span><i class="fas fa-motorcycle"></i> Ship: {{ $restaurant->delivery_fee > 0 ? number_format($restaurant->delivery_fee).'đ' : 'Miễn phí' }}</span>
                    <span class="badge {{ $restaurant->is_open ? 'badge-success' : 'badge-danger' }}" style="font-size:0.8rem;">
                        <i class="fas fa-circle" style="font-size:0.5rem;"></i> {{ $restaurant->is_open ? 'Đang mở' : 'Đóng cửa' }}
                    </span>
                </div>
            </div>
            <div>
                @auth
                <button id="favBtn" onclick="toggleFavorite()" class="btn {{ $isFavorited ? 'btn-danger' : 'btn-light' }}" style="border:none;">
                    <i class="fas fa-heart"></i> {{ $isFavorited ? 'Đã thích' : 'Yêu thích' }}
                </button>
                @endauth
            </div>
        </div>
    </div>
</section>

<!-- Category Nav -->
<div class="menu-category-nav">
    <div class="container">
        <div class="menu-category-nav-inner">
            <button class="cat-btn active" data-category="all"><i class="fas fa-utensils"></i> Tất cả</button>
            <a href="#cart" class="cat-btn"><i class="fas fa-shopping-cart"></i> Giỏ hàng</a>
            @foreach($menuItems as $catName => $items)
                @if($catName !== 'Bánh ngọt')
                <button class="cat-btn" data-category="{{ Str::slug($catName) }}">{{ $catName }}</button>
                @endif
            @endforeach
            <a href="#reviews" class="cat-btn"><i class="fas fa-star"></i> Đánh giá</a>
        </div>
    </div>
</div>

<div class="container" style="padding: 2rem 1rem;">
    <div class="content-grid" style="display:grid; grid-template-columns:1fr 360px; gap:2rem; align-items:start;">
        <!-- MENU -->
        <div>
            @if($menuItems->isEmpty())
                <div class="text-center" style="padding:3rem; background:#fff; border-radius:var(--radius);">
                    <div style="font-size:3rem; margin-bottom:1rem;">🍽️</div>
                    <h3 style="color:var(--text-muted);">Nhà hàng chưa có món ăn</h3>
                </div>
            @endif

            @foreach($menuItems as $catName => $items)
                @if($catName !== 'Bánh ngọt')
            <div id="cat-{{ Str::slug($catName) }}" class="menu-category" style="margin-bottom:2rem;">
                <h2 style="font-size:1.15rem; font-weight:800; margin-bottom:1rem; padding-bottom:0.75rem; border-bottom:2px solid var(--primary); color:var(--text);">
                    {{ $catName }}
                    <span style="font-weight:400; font-size:0.85rem; color:var(--text-muted);">({{ $items->count() }} món)</span>
                </h2>
                @foreach($items as $item)
                <div class="menu-item-card">
                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="menu-item-img" onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=200&h=200&fit=crop'">
                    <div class="menu-item-info">
                        <div class="menu-item-name">
                            {{ $item->name }}
                            @foreach($item->tags as $tag)
                                <span style="display:inline-block; padding:0.1rem 0.5rem; border-radius:50px; font-size:0.7rem; font-weight:600; background:{{ $tag->color }}20; color:{{ $tag->color }}; margin-left:0.25rem;">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                        <div class="menu-item-desc">{{ $item->description }}</div>
                        <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
                            <div class="menu-item-price">
                                {{ number_format($item->effective_price) }}đ
                                @if($item->sale_price)
                                    <del>{{ number_format($item->price) }}đ</del>
                                @endif
                            </div>
                            @if($item->calories)
                                <span style="font-size:0.75rem; color:var(--text-muted);"><i class="fas fa-fire"></i> {{ $item->calories }} kcal</span>
                            @endif
                            @if($item->prep_time)
                                <span style="font-size:0.75rem; color:var(--text-muted);"><i class="fas fa-clock"></i> {{ $item->prep_time }} phút</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        @if($restaurant->is_open)
                        <button
                            class="btn btn-primary btn-sm add-to-cart-btn"
                            data-id="{{ $item->id }}"
                            data-name="{{ $item->name }}"
                            data-price="{{ $item->effective_price }}"
                            data-restaurant-id="{{ $restaurant->id }}"
                            data-restaurant-name="{{ $restaurant->name }}"
                            data-restaurant-fee="{{ $restaurant->delivery_fee }}"
                            data-restaurant-min-order="{{ $restaurant->min_order }}"
                            onclick="event.preventDefault(); event.stopPropagation(); addToCart({{ (int) $item->id }}, {!! json_encode($item->name) !!}, {{ (float) $item->effective_price }}, {{ (int) $restaurant->id }}, {!! json_encode($restaurant->name) !!}, {{ (float) $restaurant->delivery_fee }}, {{ (float) $restaurant->min_order }}); return false;"
                            style="white-space:nowrap;"
                        >
                            <i class="fas fa-plus"></i> Thêm
                        </button>
                        @else
                        <span class="badge badge-secondary">Đóng cửa</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
                @endif
            @endforeach

            @if(isset($menuItems['Bánh ngọt']))
            <div id="cat-banh-ngot" class="menu-category" style="display:none; margin-bottom:2rem;">
                <h2 style="font-size:1.15rem; font-weight:800; margin-bottom:1rem; padding-bottom:0.75rem; border-bottom:2px solid var(--primary); color:var(--text);">
                    Bánh ngọt
                    <span style="font-weight:400; font-size:0.85rem; color:var(--text-muted);">({{ $menuItems['Bánh ngọt']->count() }} món)</span>
                </h2>
                @foreach($menuItems['Bánh ngọt'] as $item)
                <div class="menu-item-card">
                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="menu-item-img" onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=200&h=200&fit=crop'">
                    <div class="menu-item-info">
                        <div class="menu-item-name">
                            {{ $item->name }}
                            @foreach($item->tags as $tag)
                                <span style="display:inline-block; padding:0.1rem 0.5rem; border-radius:50px; font-size:0.7rem; font-weight:600; background:{{ $tag->color }}20; color:{{ $tag->color }}; margin-left:0.25rem;">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                        <div class="menu-item-desc">{{ $item->description }}</div>
                        <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
                            <div class="menu-item-price">
                                {{ number_format($item->effective_price) }}đ
                                @if($item->sale_price)
                                    <del>{{ number_format($item->price) }}đ</del>
                                @endif
                            </div>
                            @if($item->calories)
                                <span style="font-size:0.75rem; color:var(--text-muted);"><i class="fas fa-fire"></i> {{ $item->calories }} kcal</span>
                            @endif
                            @if($item->prep_time)
                                <span style="font-size:0.75rem; color:var(--text-muted);"><i class="fas fa-clock"></i> {{ $item->prep_time }} phút</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        @if($restaurant->is_open)
                        <button class="btn btn-primary btn-sm add-to-cart-btn" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-price="{{ $item->effective_price }}" data-restaurant-id="{{ $restaurant->id }}" data-restaurant-name="{{ $restaurant->name }}" data-restaurant-fee="{{ $restaurant->delivery_fee }}" data-restaurant-min-order="{{ $restaurant->min_order }}" style="white-space:nowrap;">
                            <i class="fas fa-plus"></i> Thêm
                        </button>
                        @else
                        <span class="badge badge-secondary">Đóng cửa</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- REVIEWS SECTION -->
            <div id="reviews" style="margin-top:2rem;">
                <h2 style="font-size:1.15rem; font-weight:800; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:2px solid var(--primary);">
                    <i class="fas fa-star" style="color:#f7b731;"></i> Đánh giá
                </h2>

                @if($reviews->count() > 0)
                @foreach($reviews as $review)
                <div style="padding:1.25rem; background:#fff; border-radius:var(--radius-sm); border:1px solid var(--border); margin-bottom:0.75rem;">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <img src="{{ $review->user->avatar_url }}" alt="" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                        <div>
                            <strong style="font-size:0.9rem;">{{ $review->user->name }}</strong>
                            <div class="stars" style="font-size:0.85rem;">
                                @for($i=1; $i<=5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? '' : 'empty' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <span style="margin-left:auto; font-size:0.75rem; color:var(--text-muted);">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    @if($review->comment)
                    <p style="font-size:0.875rem; color:var(--text); margin:0;">{{ $review->comment }}</p>
                    @endif
                </div>
                @endforeach
                @else
                <div class="text-center" style="padding:2rem; color:var(--text-muted);">
                    <i class="fas fa-star" style="font-size:2rem; opacity:0.3; margin-bottom:0.75rem;"></i>
                    <p>Chưa có đánh giá nào. Hãy là người đầu tiên!</p>
                </div>
                @endif
            </div>
        </div>

        <!-- CART SIDEBAR -->
        <div id="cart">
            <div class="cart-sidebar" id="cartSidebar">
                <h3 style="font-size:1rem; font-weight:800; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid var(--border);">
                    <i class="fas fa-shopping-cart" style="color:var(--primary);"></i> Giỏ hàng của bạn
                </h3>

                <div id="cartItems" style="min-height:100px;">
                    <div id="emptyCart" class="text-center" style="padding:2rem 0; color:var(--text-muted);">
                        <i class="fas fa-shopping-cart" style="font-size:2rem; opacity:0.3; margin-bottom:0.75rem;"></i>
                        <p style="font-size:0.875rem;">Chưa có món nào. Thêm món ăn bạn muốn!</p>
                    </div>
                </div>

                <div id="cartSummary" style="display:none;">
                    <hr>
                    <div class="d-flex justify-content-between mb-1" style="font-size:0.875rem;">
                        <span style="color:var(--text-muted);">Tạm tính:</span>
                        <strong id="subtotalDisplay">0đ</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1" style="font-size:0.875rem;">
                        <span style="color:var(--text-muted);">Phí giao hàng:</span>
                        <strong>{{ number_format($restaurant->delivery_fee) }}đ</strong>
                    </div>
                    <div class="d-flex justify-content-between" style="font-size:1rem; font-weight:800; margin-top:0.5rem; padding-top:0.5rem; border-top:1px solid var(--border);">
                        <span>Tổng cộng:</span>
                        <span style="color:var(--primary);" id="totalDisplay">0đ</span>
                    </div>

                    @if($restaurant->min_order > 0)
                    <p id="minOrderWarning" style="font-size:0.78rem; color:var(--danger); margin-top:0.5rem; display:none;">
                        <i class="fas fa-exclamation-circle"></i> Đơn hàng tối thiểu: {{ number_format($restaurant->min_order) }}đ
                    </p>
                    @endif

                    <button id="checkoutBtn" class="btn btn-primary btn-block" style="margin-top:1rem;" onclick="showAddressModal()">
                        <i class="fas fa-check"></i> Đặt hàng ngay
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CHECKOUT MODAL -->
<div id="addressModal" style="display:none; position:fixed; z-index:2000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; overflow-y:auto; padding:2rem 1rem;">
    <div class="card" style="width:100%; max-width:500px; margin:auto; padding:2rem;">
        <h3 style="margin-bottom:1.5rem;"><i class="fas fa-motorcycle"></i> Xác nhận đơn hàng</h3>
        
        @auth
        <form id="checkoutForm" action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div id="cartFormItems"></div>

            <div class="form-group">
                <label class="form-label"><i class="fas fa-map-marker-alt"></i> Địa chỉ giao hàng *</label>
                <input type="text" name="delivery_address" id="deliveryAddress" class="form-control" required placeholder="Số nhà, đường, phường, quận..." style="padding:0.75rem; border-radius:var(--radius-sm); border:1px solid var(--border); margin-bottom:1rem;">
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fas fa-phone"></i> Số điện thoại *</label>
                <input type="tel" name="phone" class="form-control" required value="{{ Auth::user()->phone }}" placeholder="0901234567" style="padding:0.75rem; border-radius:var(--radius-sm); border:1px solid var(--border); margin-bottom:1rem;">
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fas fa-sticky-note"></i> Ghi chú (tùy chọn)</label>
                <textarea name="notes" class="form-control" placeholder="Ít cay, không hành, giao trước 12h..." style="height:80px; padding:0.75rem; border-radius:var(--radius-sm); border:1px solid var(--border); margin-bottom:1rem;"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fas fa-credit-card"></i> Phương thức thanh toán *</label>
                <select name="payment_method" class="form-control" required style="padding:0.75rem; border-radius:var(--radius-sm); border:1px solid var(--border); margin-bottom:1.5rem;">
                    <option value="cash">💵 Tiền mặt khi nhận hàng</option>
                    <option value="momo">📱 MoMo</option>
                    <option value="bank_transfer">🏦 Chuyển khoản</option>
                </select>
            </div>

            <div style="background:var(--bg); border-radius:var(--radius-sm); padding:1rem; margin-bottom:1.5rem;">
                <div class="d-flex justify-content-between mb-1" style="font-size:0.875rem;">
                    <span>Tạm tính:</span><strong id="modalSubtotal">0đ</strong>
                </div>
                <div class="d-flex justify-content-between mb-1" style="font-size:0.875rem;">
                    <span>Phí ship:</span><strong id="modalDeliveryFee">0đ</strong>
                </div>
                <div class="d-flex justify-content-between" style="font-weight:800; font-size:1rem;">
                    <span>Tổng:</span><span style="color:var(--primary);" id="modalTotal">0đ</span>
                </div>
            </div>

            <div style="display:flex; gap:1rem;">
                <button type="button" class="btn btn-outline" onclick="closeAddressModal()" style="flex:1;">Hủy</button>
                <button type="submit" class="btn btn-primary" style="flex:1;">Xác nhận đặt hàng</button>
            </div>
        </form>
        @else
        <div class="text-center" style="padding:2rem;">
            <i class="fas fa-lock" style="font-size:2.5rem; color:var(--primary); margin-bottom:1rem;"></i>
            <h3 style="margin-bottom:0.5rem;">Vui lòng đăng nhập</h3>
            <p style="color:var(--text-muted); margin-bottom:1.5rem;">Bạn cần đăng nhập để đặt hàng</p>
            <a href="{{ route('login') }}" class="btn btn-primary">Đăng nhập ngay</a>
        </div>
        @endauth
    </div>
</div>
@endsection

@section('scripts')
<script>
// ES5-friendly cart script (avoids arrow functions / template literals)
var STORAGE_KEY = 'resdeli_cart';
var CURRENT_RESTAURANT = {
    id: {{ $restaurant->id }},
    name: {!! json_encode($restaurant->name) !!},
    deliveryFee: {{ $restaurant->delivery_fee }},
    minOrder: {{ $restaurant->min_order }}
};
var cart = {};

// Polyfills for older browsers
if (!Element.prototype.matches) {
    Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
}
if (!Element.prototype.closest) {
    Element.prototype.closest = function (s) {
        var el = this;
        while (el && el.nodeType === 1) {
            if (el.matches(s)) return el;
            el = el.parentElement || el.parentNode;
        }
        return null;
    };
}
if (!Object.values) {
    Object.values = function (obj) {
        var keys = Object.keys(obj);
        var out = [];
        for (var i = 0; i < keys.length; i++) out.push(obj[keys[i]]);
        return out;
    };
}

function saveCart() {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
}

function loadCart() {
    var stored = localStorage.getItem(STORAGE_KEY);
    cart = stored ? JSON.parse(stored) : {};

    // Normalize legacy cart items (older versions might miss restaurant fields)
    if (cart && typeof cart === 'object') {
        var keys = Object.keys(cart);
        for (var i = 0; i < keys.length; i++) {
            var key = keys[i];
            var item = cart[key];
            if (!item || typeof item !== 'object') return;

            item.id = Number((item.id !== undefined && item.id !== null) ? item.id : key);
            item.qty = Number((item.qty !== undefined && item.qty !== null) ? item.qty : 0);
            item.price = Number((item.price !== undefined && item.price !== null) ? item.price : 0);

            if (item.restaurant_id == null || Number.isNaN(Number(item.restaurant_id))) {
                item.restaurant_id = CURRENT_RESTAURANT.id;
            } else {
                item.restaurant_id = Number(item.restaurant_id);
            }

            if (!item.restaurant_name) item.restaurant_name = CURRENT_RESTAURANT.name;

            if (item.restaurant_delivery_fee == null || Number.isNaN(Number(item.restaurant_delivery_fee))) {
                item.restaurant_delivery_fee = Number(CURRENT_RESTAURANT.deliveryFee);
            } else {
                item.restaurant_delivery_fee = Number(item.restaurant_delivery_fee);
            }

            if (item.restaurant_min_order == null || Number.isNaN(Number(item.restaurant_min_order))) {
                item.restaurant_min_order = Number(CURRENT_RESTAURANT.minOrder);
            } else {
                item.restaurant_min_order = Number(item.restaurant_min_order);
            }
        }
    }
}

function addToCart(id, name, price, restaurantId, restaurantName, restaurantFee, restaurantMinOrder) {
    if (!cart[id]) {
        cart[id] = {
            id,
            name,
            price,
            qty: 0,
            restaurant_id: restaurantId,
            restaurant_name: restaurantName,
            restaurant_delivery_fee: restaurantFee,
            restaurant_min_order: restaurantMinOrder,
        };
    }
    cart[id].qty++;
    saveCart();
    renderCart();

    // Visual feedback
    var cartEl = document.getElementById('cartSidebar') || document.getElementById('cart');
    if (cartEl) {
        cartEl.style.transform = 'scale(1.02)';
        cartEl.style.transition = 'transform 0.15s ease';
        setTimeout(function () {
            cartEl.style.transform = 'scale(1)';
        }, 180);
    }
}

function updateQty(id, delta) {
    if (!cart[id]) return;
    cart[id].qty += delta;
    if (cart[id].qty <= 0) delete cart[id];
    saveCart();
    renderCart();
}

function renderCart() {
    var cartItemsEl = document.getElementById('cartItems');
    var emptyCart = document.getElementById('emptyCart');
    var cartSummary = document.getElementById('cartSummary');
    var minOrderWarning = document.getElementById('minOrderWarning');

    var items = Object.values(cart);

    if (items.length === 0) {
        if (emptyCart) emptyCart.style.display = 'block';
        if (cartSummary) cartSummary.style.display = 'none';
        if (cartItemsEl) {
            cartItemsEl.innerHTML = '';
            if (emptyCart) cartItemsEl.appendChild(emptyCart);
        }
        return;
    }

    if (emptyCart) emptyCart.style.display = 'none';
    if (cartSummary) cartSummary.style.display = 'block';

    var subtotal = 0;
    var html = '';
    var restaurantGroups = {};

    for (var i = 0; i < items.length; i++) {
        var item = items[i];
        subtotal += item.price * item.qty;
        html += ''
            + '<div class="cart-item">'
            + '  <div style="flex:1; min-width:0;">'
            + '    <div style="font-size:0.85rem; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">' + item.name + '</div>'
            + '    <div style="font-size:0.75rem; color:var(--text-muted);">' + item.restaurant_name + '</div>'
            + '    <div style="font-size:0.8rem; color:var(--primary);">' + Number(item.price * item.qty).toLocaleString('vi-VN') + 'đ</div>'
            + '  </div>'
            + '  <div class="qty-control">'
            + '    <button class="qty-btn" onclick="updateQty(' + item.id + ', -1)">−</button>'
            + '    <span class="qty-input">' + item.qty + '</span>'
            + '    <button class="qty-btn" onclick="updateQty(' + item.id + ', 1)">+</button>'
            + '  </div>'
            + '</div>';

        if (!restaurantGroups[item.restaurant_id]) {
            restaurantGroups[item.restaurant_id] = {
                subtotal: 0,
                delivery_fee: Number(item.restaurant_delivery_fee),
                min_order: Number(item.restaurant_min_order),
                name: item.restaurant_name,
            };
        }
        restaurantGroups[item.restaurant_id].subtotal += item.price * item.qty;
    }

    var groups = Object.values(restaurantGroups);
    var totalDeliveryFee = 0;
    for (var g = 0; g < groups.length; g++) totalDeliveryFee += Number(groups[g].delivery_fee || 0);
    var total = subtotal + totalDeliveryFee;

    if (cartItemsEl) cartItemsEl.innerHTML = html;

    var subtotalDisplay = document.getElementById('subtotalDisplay');
    var totalDisplay = document.getElementById('totalDisplay');
    var deliveryFeeDisplay = null;
    if (subtotalDisplay && subtotalDisplay.parentElement && subtotalDisplay.parentElement.nextElementSibling) {
        deliveryFeeDisplay = subtotalDisplay.parentElement.nextElementSibling.querySelector('strong');
    }

    if (subtotalDisplay) subtotalDisplay.textContent = Number(subtotal).toLocaleString('vi-VN') + 'đ';
    if (deliveryFeeDisplay) deliveryFeeDisplay.textContent = Number(totalDeliveryFee).toLocaleString('vi-VN') + 'đ';
    if (totalDisplay) totalDisplay.textContent = Number(total).toLocaleString('vi-VN') + 'đ';

    if (minOrderWarning) {
        var warnings = [];
        for (var w = 0; w < groups.length; w++) {
            if (Number(groups[w].subtotal) < Number(groups[w].min_order)) {
                warnings.push(groups[w].name + ' cần tối thiểu ' + Number(groups[w].min_order).toLocaleString('vi-VN') + 'đ');
            }
        }

        if (warnings.length > 0) {
            minOrderWarning.textContent = 'Cảnh báo: ' + warnings.join('; ') + '.';
            minOrderWarning.style.display = 'block';
        } else {
            minOrderWarning.style.display = 'none';
        }
    }
}

function showAddressModal() {
    var items = Object.values(cart);
    if (items.length === 0) {
        alert('Vui lòng chọn ít nhất 1 món!');
        return;
    }

    var restaurantGroups = {};
    for (var i = 0; i < items.length; i++) {
        var item = items[i];
        if (!restaurantGroups[item.restaurant_id]) {
            restaurantGroups[item.restaurant_id] = {
                subtotal: 0,
                delivery_fee: Number(item.restaurant_delivery_fee),
                min_order: Number(item.restaurant_min_order),
                name: item.restaurant_name,
            };
        }
        restaurantGroups[item.restaurant_id].subtotal += item.price * item.qty;
    }

    var groups = Object.values(restaurantGroups);
    var warnings = [];
    for (var w = 0; w < groups.length; w++) {
        if (Number(groups[w].subtotal) < Number(groups[w].min_order)) {
            warnings.push(groups[w].name + ' cần tối thiểu ' + Number(groups[w].min_order).toLocaleString('vi-VN') + 'đ');
        }
    }

    if (warnings.length > 0) {
        alert('Không thể đặt hàng: ' + warnings.join('; '));
        return;
    }

    var subtotal = 0;
    for (var si = 0; si < items.length; si++) subtotal += Number(items[si].price) * Number(items[si].qty);
    var totalDelivery = 0;
    for (var gd = 0; gd < groups.length; gd++) totalDelivery += Number(groups[gd].delivery_fee || 0);
    var total = subtotal + totalDelivery;

    var cartFormItems = document.getElementById('cartFormItems');
    if (cartFormItems) {
        var formHtml = '';
        for (var idx = 0; idx < items.length; idx++) {
            var it = items[idx];
            formHtml += ''
                + '<input type="hidden" name="items[' + idx + '][id]" value="' + it.id + '">'
                + '<input type="hidden" name="items[' + idx + '][qty]" value="' + it.qty + '">'
                + '<input type="hidden" name="items[' + idx + '][restaurant_id]" value="' + it.restaurant_id + '">';
        }
        cartFormItems.innerHTML = formHtml;
    }

    var modalSubtotal = document.getElementById('modalSubtotal');
    var modalDeliveryFee = document.getElementById('modalDeliveryFee');
    var modalTotal = document.getElementById('modalTotal');
    
    if (modalSubtotal) modalSubtotal.textContent = Number(subtotal).toLocaleString('vi-VN') + 'đ';
    if (modalDeliveryFee) modalDeliveryFee.textContent = Number(totalDelivery).toLocaleString('vi-VN') + 'đ';
    if (modalTotal) modalTotal.textContent = Number(total).toLocaleString('vi-VN') + 'đ';

    renderCart();
    var modal = document.getElementById('addressModal');
    if (modal) {
        modal.style.display = 'flex';
    }
    document.body.style.overflow = 'hidden';
}

// Alias for older UI code snippets
function handleOrderClick() {
    showAddressModal();
}

function closeAddressModal() {
    var modal = document.getElementById('addressModal');
    if (modal) {
        modal.style.display = 'none';
    }
    document.body.style.overflow = '';
}

// Optional explicit confirm hook (in case button is changed to onclick="confirmOrder()")
function confirmOrder() {
    var addressEl = document.getElementById('deliveryAddress');
    var address = addressEl ? String(addressEl.value || '').trim() : '';

    if (!address) {
        alert('Vui lòng nhập địa chỉ giao hàng.');
        return;
    }

    var form = document.getElementById('checkoutForm');
    if (form) form.submit();
}

document.addEventListener('DOMContentLoaded', function() {
    // Add to cart via click delegation (more reliable than per-button listeners)
    document.addEventListener('click', function(e) {
        var btn = e.target && e.target.closest ? e.target.closest('.add-to-cart-btn') : null;
        if (!btn) return;

        e.preventDefault();

        try {
            var id = parseInt(btn.getAttribute('data-id') || '', 10);
            var name = btn.getAttribute('data-name') || '';
            var price = parseFloat(btn.getAttribute('data-price') || '0');

            var restaurantId = parseInt(btn.getAttribute('data-restaurant-id') || String(CURRENT_RESTAURANT.id), 10);
            var restaurantName = btn.getAttribute('data-restaurant-name') || CURRENT_RESTAURANT.name;
            var restaurantFee = parseFloat(btn.getAttribute('data-restaurant-fee') || String(CURRENT_RESTAURANT.deliveryFee));
            var restaurantMinOrder = parseFloat(btn.getAttribute('data-restaurant-min-order') || String(CURRENT_RESTAURANT.minOrder));

            if (!id || !name || isNaN(price)) {
                alert('Không thể thêm món vào giỏ hàng. Vui lòng tải lại trang.');
                return;
            }

            addToCart(id, name, price, restaurantId, restaurantName, restaurantFee, restaurantMinOrder);
        } catch (err) {
            console.error(err);
            alert('Có lỗi khi thêm món vào giỏ hàng. Vui lòng tải lại trang.');
        }
    }, true);

    // Category filtering
    var catBtns = document.querySelectorAll('.cat-btn[data-category]');
    for (var i = 0; i < catBtns.length; i++) {
        catBtns[i].addEventListener('click', function() {
            var category = this.getAttribute('data-category');
            filterCategory(category);

            // Update active button
            var allBtns = document.querySelectorAll('.cat-btn');
            for (var j = 0; j < allBtns.length; j++) allBtns[j].classList.remove('active');
            this.classList.add('active');
        });
    }

    var form = document.getElementById('checkoutForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            var addressEl = document.querySelector('input[name="delivery_address"]');
            var phoneEl = document.querySelector('input[name="phone"]');
            var address = addressEl ? String(addressEl.value || '').trim() : '';
            var phone = phoneEl ? String(phoneEl.value || '').trim() : '';

            if (!address) {
                alert('Vui lòng nhập địa chỉ giao hàng.');
                return;
            }

            if (!phone) {
                alert('Vui lòng nhập số điện thoại.');
                return;
            }

            // Submit form
            form.submit();
        });
    }
});

function filterCategory(category) {
    var categories = document.querySelectorAll('.menu-category');
    if (category === 'all') {
        for (var i = 0; i < categories.length; i++) categories[i].style.display = 'block';
    } else if (category === 'do-uong') {
        for (var i = 0; i < categories.length; i++) {
            var cat = categories[i];
            if (cat.id === 'cat-do-uong' || cat.id === 'cat-banh-ngot') cat.style.display = 'block';
            else cat.style.display = 'none';
        }
    } else {
        for (var i = 0; i < categories.length; i++) {
            var cat = categories[i];
            if (cat.id === 'cat-' + category) cat.style.display = 'block';
            else cat.style.display = 'none';
        }
    }
}

var addressModal = document.getElementById('addressModal');
if (addressModal) {
    addressModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddressModal();
        }
    });
}

// Favorite toggle
@auth
function toggleFavorite() {
    var btn = document.getElementById('favBtn');
    fetch('{{ route("restaurants.favorite", $restaurant->slug) }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.favorited) {
            btn.innerHTML = '<i class="fas fa-heart"></i> Đã thích';
            btn.className = 'btn btn-danger';
        } else {
            btn.innerHTML = '<i class="fas fa-heart"></i> Yêu thích';
            btn.className = 'btn btn-light';
        }
    });
}
@endauth

// Load cart on page load
loadCart();
renderCart();
</script>
@endsection
