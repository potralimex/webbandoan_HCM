@extends('layouts.app')
@section('title', 'Giỏ hàng - ResDeli')

@section('content')
<div class="container" style="padding:2rem 1rem;">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 style="font-size:1.6rem; font-weight:800;">
            <i class="fas fa-shopping-cart" style="color:var(--primary);"></i> Giỏ hàng
        </h1>
        @if(!empty($cart))
        <button onclick="clearCart()" class="btn btn-outline btn-sm" style="color:var(--danger); border-color:var(--danger);">
            <i class="fas fa-trash"></i> Xóa tất cả
        </button>
        @endif
    </div>

    @if(session('error'))
    <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    @if(empty($cart))
    <div class="card text-center" style="padding:4rem 2rem;">
        <div style="font-size:4rem; margin-bottom:1rem;">🛒</div>
        <h3 style="color:var(--text-muted); margin-bottom:0.5rem;">Giỏ hàng trống</h3>
        <p style="color:var(--text-muted); margin-bottom:1.5rem;">Hãy thêm món ăn từ các nhà hàng!</p>
        <a href="{{ route('home') }}" class="btn btn-primary">🍜 Khám phá nhà hàng</a>
    </div>
    @else
    <div style="display:grid; grid-template-columns:1fr 320px; gap:2rem; align-items:start;">
        <!-- Cart Items -->
        <div class="card" style="overflow:hidden;">
            <div class="table-responsive">
                <table class="table" id="cartTable">
                    <thead>
                        <tr>
                            <th>Món ăn</th>
                            <th style="text-align:center;">Số lượng</th>
                            <th style="text-align:right;">Thành tiền</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="cartBody">
                        @foreach($cart as $id => $item)
                        <tr id="row-{{ $id }}">
                            <td>
                                <div style="display:flex; align-items:center; gap:0.75rem;">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                                         style="width:56px; height:56px; border-radius:var(--radius-sm); object-fit:cover;"
                                         onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=100&h=100&fit=crop'">
                                    <div>
                                        <div style="font-weight:600; font-size:0.9rem;">{{ $item['name'] }}</div>
                                        <div style="font-size:0.8rem; color:var(--primary);">{{ number_format($item['price']) }}đ / món</div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align:center;">
                                <div style="display:inline-flex; align-items:center; gap:0.4rem;">
                                    <button class="qty-btn" onclick="updateQty({{ $id }}, -1)">−</button>
                                    <input type="number" id="qty-{{ $id }}" value="{{ $item['quantity'] }}" min="1"
                                           style="width:48px; text-align:center; border:1.5px solid var(--border); border-radius:var(--radius-sm); padding:0.3rem; font-weight:700;"
                                           onchange="setQty({{ $id }}, this.value)">
                                    <button class="qty-btn" onclick="updateQty({{ $id }}, 1)">+</button>
                                </div>
                            </td>
                            <td style="text-align:right; font-weight:700; color:var(--primary);" id="sub-{{ $id }}">
                                {{ number_format($item['price'] * $item['quantity']) }}đ
                            </td>
                            <td style="text-align:center;">
                                <button onclick="removeItem({{ $id }})" style="background:none; border:none; color:var(--danger); cursor:pointer; font-size:1rem; padding:0.25rem;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary -->
        <div>
            <div class="card" style="padding:1.5rem;">
                <h3 style="font-size:1rem; font-weight:800; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid var(--border);">
                    <i class="fas fa-receipt" style="color:var(--primary);"></i> Tóm tắt đơn hàng
                </h3>
                <div class="d-flex justify-content-between mb-2" style="font-size:0.875rem;">
                    <span style="color:var(--text-muted);">Tạm tính:</span>
                    <strong id="summaryTotal">{{ number_format($total) }}đ</strong>
                </div>
                <div class="d-flex justify-content-between mb-2" style="font-size:0.875rem;">
                    <span style="color:var(--text-muted);">Phí giao hàng:</span>
                    <span style="color:var(--success); font-weight:600;">Tính khi thanh toán</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between" style="font-size:1.1rem; font-weight:800;">
                    <span>Tổng cộng:</span>
                    <span style="color:var(--primary);" id="summaryGrand">{{ number_format($total) }}đ</span>
                </div>
                <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-block" style="margin-top:1.25rem; padding:0.85rem;">
                    <i class="fas fa-lock"></i> Tiến hành thanh toán
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline btn-block" style="margin-top:0.75rem;">
                    <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('styles')
<style>
.qty-btn {
    width:30px; height:30px; border-radius:50%; border:1.5px solid var(--border);
    background:#fff; cursor:pointer; font-size:0.9rem; display:inline-flex;
    align-items:center; justify-content:center; transition:all 0.2s;
}
.qty-btn:hover { border-color:var(--primary); color:var(--primary); }
</style>
@endsection

@section('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

function updateQty(id, delta) {
    const input = document.getElementById('qty-' + id);
    const newQty = Math.max(0, parseInt(input.value) + delta);
    if (newQty === 0) { removeItem(id); return; }
    input.value = newQty;
    sendUpdate(id, newQty);
}

function setQty(id, val) {
    const qty = parseInt(val);
    if (isNaN(qty) || qty <= 0) { removeItem(id); return; }
    sendUpdate(id, qty);
}

function sendUpdate(id, qty) {
    fetch('{{ route("cart.update") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ menu_item_id: id, quantity: qty })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const subEl = document.getElementById('sub-' + id);
            if (subEl) subEl.textContent = formatVND(data.subtotal);
            document.getElementById('summaryTotal').textContent = formatVND(data.total);
            document.getElementById('summaryGrand').textContent = formatVND(data.total);
            updateNavCount(data.count);
        }
    });
}

function removeItem(id) {
    fetch('{{ route("cart.remove") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ menu_item_id: id })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const row = document.getElementById('row-' + id);
            if (row) row.remove();
            document.getElementById('summaryTotal').textContent = formatVND(data.total);
            document.getElementById('summaryGrand').textContent = formatVND(data.total);
            updateNavCount(data.count);
            if (data.count === 0) location.reload();
        }
    });
}

function clearCart() {
    if (!confirm('Xóa toàn bộ giỏ hàng?')) return;
    fetch('{{ route("cart.clear") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json())
    .then(() => { updateNavCount(0); location.reload(); });
}

function formatVND(n) {
    return Number(n).toLocaleString('vi-VN') + 'đ';
}

function updateNavCount(count) {
    const badge = document.getElementById('cartBadge');
    if (badge) badge.textContent = count;
}
</script>
@endsection
