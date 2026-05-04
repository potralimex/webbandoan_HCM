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

.cart-sidebar { background: #fff; border-radius: var(--radius); box-shadow: var(--shadow); padding: 1.5rem; position: sticky; top: 80px; }
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
            <a href="#cart" class="cat-btn"><i class="fas fa-shopping-cart"></i> Giỏ hàng</a>
            @foreach($menuItems as $catName => $items)
                <a href="#cat-{{ Str::slug($catName) }}" class="cat-btn">{{ $catName }}</a>
            @endforeach
            <a href="#reviews" class="cat-btn"><i class="fas fa-star"></i> Đánh giá</a>
        </div>
    </div>
</div>

<div class="container" style="padding: 2rem 1rem;">
    <div style="display:grid; grid-template-columns:1fr 360px; gap:2rem; align-items:start;">
        <!-- MENU -->
        <div>
            @if($menuItems->isEmpty())
                <div class="text-center" style="padding:3rem; background:#fff; border-radius:var(--radius);">
                    <div style="font-size:3rem; margin-bottom:1rem;">🍽️</div>
                    <h3 style="color:var(--text-muted);">Nhà hàng chưa có món ăn</h3>
                </div>
            @endif

            @foreach($menuItems as $catName => $items)
            <div id="cat-{{ Str::slug($catName) }}" style="margin-bottom:2rem;">
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
                        <button onclick="addToCart({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->effective_price }})" class="btn btn-primary btn-sm" style="white-space:nowrap;">
                            <i class="fas fa-plus"></i> Thêm
                        </button>
                        @else
                        <span class="badge badge-secondary">Đóng cửa</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach

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
            <div class="cart-sidebar">
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

                    <button id="checkoutBtn" class="btn btn-primary btn-block" style="margin-top:1rem;" onclick="showCheckout()">
                        <i class="fas fa-check"></i> Đặt hàng ngay
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CHECKOUT MODAL -->
<div id="checkoutModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:2000; overflow-y:auto; padding:2rem 1rem;">
    <div style="max-width:540px; margin:auto; background:#fff; border-radius:var(--radius); overflow:hidden; animation:fadeIn 0.3s ease;">
        <div style="background:linear-gradient(135deg,var(--primary),var(--primary-dark)); padding:1.5rem; color:#fff;">
            <div class="d-flex align-items-center justify-content-between">
                <h2 style="font-size:1.15rem; font-weight:800; margin:0;"><i class="fas fa-motorcycle"></i> Xác nhận đơn hàng</h2>
                <button onclick="closeModal()" style="background:none;border:none;color:#fff;font-size:1.25rem;cursor:pointer;">✕</button>
            </div>
        </div>
        <div style="padding:1.5rem;">
            @auth
            <form id="checkoutForm" action="{{ route('orders.store') }}" method="POST">
                @csrf
                <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
                <div id="cartFormItems"></div>

                <div class="form-group">
                    <label class="form-label"><i class="fas fa-map-marker-alt"></i> Địa chỉ giao hàng *</label>
                    <input type="text" name="delivery_address" class="form-control" required placeholder="Số nhà, đường, phường, quận...">
                </div>
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-phone"></i> Số điện thoại *</label>
                    <input type="tel" name="phone" class="form-control" required value="{{ Auth::user()->phone }}" placeholder="0901234567">
                </div>
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-sticky-note"></i> Ghi chú (tùy chọn)</label>
                    <textarea name="notes" class="form-control" placeholder="Ít cay, không hành, giao trước 12h..." style="height:80px;"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-credit-card"></i> Phương thức thanh toán *</label>
                    <select name="payment_method" class="form-control" required>
                        <option value="cash">💵 Tiền mặt khi nhận hàng</option>
                        <option value="momo">📱 MoMo</option>
                        <option value="bank_transfer">🏦 Chuyển khoản</option>
                    </select>
                </div>

                <div style="background:var(--bg); border-radius:var(--radius-sm); padding:1rem; margin-bottom:1.25rem;">
                    <div class="d-flex justify-content-between mb-1" style="font-size:0.875rem;">
                        <span>Tạm tính:</span><strong id="modalSubtotal">0đ</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1" style="font-size:0.875rem;">
                        <span>Phí ship:</span><strong>{{ number_format($restaurant->delivery_fee) }}đ</strong>
                    </div>
                    <div class="d-flex justify-content-between" style="font-weight:800; font-size:1rem;">
                        <span>Tổng:</span><span style="color:var(--primary);" id="modalTotal">0đ</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="padding:0.85rem;">
                    <i class="fas fa-check"></i> Xác nhận đặt hàng
                </button>
            </form>
            @else
            <div class="text-center" style="padding:2rem;">
                <i class="fas fa-lock" style="font-size:2.5rem; color:var(--primary-dark); margin-bottom:1rem;"></i>
                <h3 style="margin-bottom:0.5rem;">Vui lòng đăng nhập</h3>
                <p style="color:var(--text-muted); margin-bottom:1.5rem;">Bạn cần đăng nhập để đặt hàng</p>
                <a href="{{ route('login') }}" class="btn btn-primary">Đăng nhập ngay</a>
            </div>
            @endauth
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const DELIVERY_FEE = {{ $restaurant->delivery_fee }};
const MIN_ORDER = {{ $restaurant->min_order }};

// Seed từ session — giữ cart khi reload trang
let cart = {};
@auth
@php
    $sessionCart = collect(session('cart', []))
        ->filter(fn($item) => \App\Models\MenuItem::find($item['id'])?->restaurant_id == $restaurant->id)
        ->values();
@endphp
@foreach($sessionCart as $item)
cart[{{ $item['id'] }}] = {
    id: {{ $item['id'] }},
    name: @json($item['name']),
    price: {{ $item['price'] }},
    qty: {{ $item['quantity'] }}
};
@endforeach
@endauth

// Render ngay khi load
renderCart();

function addToCart(id, name, price) {
    @auth
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ menu_item_id: id, quantity: 1 })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Update navbar badge
            updateBadge(data.count);
            // Also update local cart for sidebar
            if (!cart[id]) cart[id] = { id, name, price, qty: 0 };
            cart[id].qty++;
            renderCart();
            showToast(data.message);
        }
    });
    @else
    window.location.href = '{{ route("login") }}';
    @endauth
}

function updateQty(id, delta) {
    if (!cart[id]) return;
    cart[id].qty += delta;
    if (cart[id].qty <= 0) {
        delete cart[id];
        fetch('{{ route("cart.remove") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ menu_item_id: id })
        }).then(r => r.json()).then(data => updateBadge(data.count));
    } else {
        fetch('{{ route("cart.update") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ menu_item_id: id, quantity: cart[id].qty })
        }).then(r => r.json()).then(data => updateBadge(data.count));
    }
    renderCart();
}

function updateBadge(count) {
    const badge = document.getElementById('cartBadge');
    if (badge) { badge.textContent = count; badge.style.display = count > 0 ? 'flex' : 'none'; }
}

function renderCart() {
    const cartItemsEl = document.getElementById('cartItems');
    const emptyCart = document.getElementById('emptyCart');
    const cartSummary = document.getElementById('cartSummary');
    const cartFormItems = document.getElementById('cartFormItems');
    const minOrderWarning = document.getElementById('minOrderWarning');

    const items = Object.values(cart);

    if (items.length === 0) {
        emptyCart.style.display = 'block';
        cartSummary.style.display = 'none';
        cartItemsEl.innerHTML = '';
        cartItemsEl.appendChild(emptyCart);
        return;
    }

    emptyCart.style.display = 'none';
    cartSummary.style.display = 'block';

    let subtotal = 0;
    let html = '';
    let formHtml = '';

    items.forEach((item, idx) => {
        subtotal += item.price * item.qty;
        html += `<div class="cart-item">
            <div style="flex:1; min-width:0;">
                <div style="font-size:0.85rem; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${item.name}</div>
                <div style="font-size:0.8rem; color:var(--primary);">${Number(item.price * item.qty).toLocaleString('vi-VN')}đ</div>
            </div>
            <div class="qty-control">
                <button class="qty-btn" onclick="updateQty(${item.id}, -1)">−</button>
                <span class="qty-input">${item.qty}</span>
                <button class="qty-btn" onclick="updateQty(${item.id}, 1)">+</button>
            </div>
        </div>`;
        formHtml += `<input type="hidden" name="items[${idx}][id]" value="${item.id}">
                     <input type="hidden" name="items[${idx}][qty]" value="${item.qty}">`;
    });

    const total = subtotal + DELIVERY_FEE;
    cartItemsEl.innerHTML = html;
    if (cartFormItems) cartFormItems.innerHTML = formHtml;

    document.getElementById('subtotalDisplay').textContent = Number(subtotal).toLocaleString('vi-VN') + 'đ';
    document.getElementById('totalDisplay').textContent = Number(total).toLocaleString('vi-VN') + 'đ';

    if (document.getElementById('modalSubtotal')) {
        document.getElementById('modalSubtotal').textContent = Number(subtotal).toLocaleString('vi-VN') + 'đ';
        document.getElementById('modalTotal').textContent = Number(total).toLocaleString('vi-VN') + 'đ';
    }

    if (minOrderWarning) {
        minOrderWarning.style.display = subtotal < MIN_ORDER ? 'block' : 'none';
    }
}

function showCheckout() {
    const items = Object.values(cart);
    if (items.length === 0) { alert('Vui lòng chọn ít nhất 1 món!'); return; }
    const subtotal = items.reduce((sum, i) => sum + i.price * i.qty, 0);
    if (subtotal < MIN_ORDER) { alert('Đơn hàng chưa đạt giá trị tối thiểu ' + Number(MIN_ORDER).toLocaleString('vi-VN') + 'đ'); return; }
    window.location.href = '{{ route("checkout.index") }}';
}

function showToast(msg) {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerHTML = `<i class="fas fa-check-circle toast-icon text-success"></i><span>${msg}</span><button class="toast-close" onclick="this.parentElement.remove()">✕</button>`;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

function closeModal() {
    document.getElementById('checkoutModal').style.display = 'none';
    document.body.style.overflow = '';
}

document.getElementById('checkoutModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// Favorite toggle
@auth
function toggleFavorite() {
    const btn = document.getElementById('favBtn');
    fetch('{{ route("restaurants.favorite", $restaurant->slug) }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
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
</script>
@endsection
