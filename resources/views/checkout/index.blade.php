@extends('layouts.app')
@section('title', 'Thanh toán - ResDeli')

@section('content')
<div class="container" style="padding:2rem 1rem;">
    <div class="d-flex gap-2 mb-3" style="font-size:0.875rem;">
        <a href="{{ route('cart.index') }}" style="color:var(--text-muted); text-decoration:none;">
            <i class="fas fa-arrow-left"></i> Giỏ hàng
        </a>
        <span style="color:var(--text-muted);">/</span>
        <span>Thanh toán</span>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <div>{{ $errors->first() }}</div>
    </div>
    @endif

    <div style="display:grid; grid-template-columns:1fr 340px; gap:2rem; align-items:start;">
        <!-- Checkout Form -->
        <div>
            <form action="{{ route('checkout.place') }}" method="POST" id="checkoutForm">
                @csrf

                <!-- Delivery Info -->
                <div class="card" style="padding:1.75rem; margin-bottom:1.5rem;">
                    <h2 style="font-size:1rem; font-weight:800; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid var(--border);">
                        <i class="fas fa-shipping-fast" style="color:var(--primary);"></i> Thông tin giao hàng
                    </h2>
                    <div class="form-group">
                        <label class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled style="background:var(--bg);">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Số điện thoại *</label>
                        <input type="tel" name="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                               value="{{ old('phone', Auth::user()->phone) }}" required placeholder="0901234567">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Địa chỉ giao hàng *</label>
                        <input type="text" name="delivery_address" class="form-control {{ $errors->has('delivery_address') ? 'is-invalid' : '' }}"
                               value="{{ old('delivery_address') }}" required placeholder="Số nhà, đường, phường, quận, thành phố...">
                        @error('delivery_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Ghi chú (tùy chọn)</label>
                        <textarea name="notes" class="form-control" placeholder="Ít cay, không hành, giao trước 12h..." style="height:80px;">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card" style="padding:1.75rem; margin-bottom:1.5rem;">
                    <h2 style="font-size:1rem; font-weight:800; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid var(--border);">
                        <i class="fas fa-credit-card" style="color:var(--primary);"></i> Phương thức thanh toán
                    </h2>

                    @php
                    $methods = [
                        'cod'           => ['icon' => '💵', 'label' => 'Tiền mặt khi nhận hàng (COD)', 'desc' => 'Thanh toán khi nhận được hàng'],
                        'bank_transfer' => ['icon' => '🏦', 'label' => 'Chuyển khoản ngân hàng',       'desc' => 'Chuyển khoản trước khi giao hàng'],
                        'momo'          => ['icon' => '📱', 'label' => 'Ví MoMo',                       'desc' => 'Thanh toán qua ví điện tử MoMo (mock)'],
                        'zalopay'       => ['icon' => '💙', 'label' => 'ZaloPay',                       'desc' => 'Thanh toán qua ZaloPay (mock)'],
                    ];
                    @endphp

                    @foreach($methods as $value => $method)
                    <label style="display:flex; align-items:center; gap:1rem; padding:1rem; border:2px solid var(--border); border-radius:var(--radius-sm); margin-bottom:0.75rem; cursor:pointer; transition:all 0.2s;" class="payment-option">
                        <input type="radio" name="payment_method" value="{{ $value }}" {{ old('payment_method', 'cod') === $value ? 'checked' : '' }}
                               style="accent-color:var(--primary); width:18px; height:18px; flex-shrink:0;">
                        <span style="font-size:1.5rem;">{{ $method['icon'] }}</span>
                        <div>
                            <div style="font-weight:600; font-size:0.9rem;">{{ $method['label'] }}</div>
                            <div style="font-size:0.78rem; color:var(--text-muted);">{{ $method['desc'] }}</div>
                        </div>
                    </label>
                    @endforeach
                    @error('payment_method')<div class="invalid-feedback" style="display:block;">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="padding:1rem; font-size:1rem;">
                    <i class="fas fa-check-circle"></i> Xác nhận đặt hàng
                </button>
            </form>
        </div>

        <!-- Order Summary -->
        <div>
            <div class="card" style="padding:1.5rem; position:sticky; top:80px;">
                <h3 style="font-size:1rem; font-weight:800; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid var(--border);">
                    <i class="fas fa-receipt" style="color:var(--primary);"></i> Đơn hàng của bạn
                </h3>

                @foreach($cart as $id => $item)
                <div style="display:flex; justify-content:space-between; align-items:center; padding:0.6rem 0; border-bottom:1px solid var(--border); gap:0.5rem;">
                    <div style="display:flex; align-items:center; gap:0.6rem; flex:1; min-width:0;">
                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                             style="width:40px; height:40px; border-radius:var(--radius-sm); object-fit:cover; flex-shrink:0;"
                             onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=80&h=80&fit=crop'">
                        <div style="min-width:0;">
                            <div style="font-size:0.85rem; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $item['name'] }}</div>
                            <div style="font-size:0.75rem; color:var(--text-muted);">x{{ $item['quantity'] }}</div>
                        </div>
                    </div>
                    <span style="font-weight:700; font-size:0.875rem; color:var(--primary); flex-shrink:0;">
                        {{ number_format($item['price'] * $item['quantity']) }}đ
                    </span>
                </div>
                @endforeach

                <div class="d-flex justify-content-between mt-2" style="font-size:0.875rem;">
                    <span style="color:var(--text-muted);">Tạm tính:</span>
                    <strong>{{ number_format($total) }}đ</strong>
                </div>
                <div class="d-flex justify-content-between mt-1" style="font-size:0.875rem;">
                    <span style="color:var(--text-muted);">Phí giao hàng:</span>
                    <span style="color:var(--success); font-weight:600;">Miễn phí</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between" style="font-size:1.1rem; font-weight:800;">
                    <span>Tổng cộng:</span>
                    <span style="color:var(--primary);">{{ number_format($total) }}đ</span>
                </div>

                <a href="{{ route('cart.index') }}" class="btn btn-outline btn-block btn-sm" style="margin-top:1rem;">
                    <i class="fas fa-edit"></i> Sửa giỏ hàng
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.payment-option:has(input:checked) {
    border-color: var(--primary);
    background: var(--primary-light);
}
</style>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.payment-option').forEach(label => {
    label.addEventListener('click', () => {
        document.querySelectorAll('.payment-option').forEach(l => {
            l.style.borderColor = 'var(--border)';
            l.style.background = '';
        });
        label.style.borderColor = 'var(--primary)';
        label.style.background = 'var(--primary-light)';
    });
});
// Init selected
document.querySelectorAll('.payment-option input:checked').forEach(input => {
    input.closest('.payment-option').style.borderColor = 'var(--primary)';
    input.closest('.payment-option').style.background = 'var(--primary-light)';
});
</script>
@endsection
