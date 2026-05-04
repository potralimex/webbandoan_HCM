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
                        'bank_transfer' => ['icon' => '🏦', 'label' => 'Chuyển khoản ngân hàng (VietQR)', 'desc' => 'Quét mã QR để chuyển khoản ngay'],
                        'momo'          => ['icon' => '📱', 'label' => 'Ví MoMo',                       'desc' => 'Thanh toán qua ví điện tử MoMo (mock)'],
                        'zalopay'       => ['icon' => '💙', 'label' => 'ZaloPay',                       'desc' => 'Thanh toán qua ZaloPay (mock)'],
                    ];
                    @endphp

                    @foreach($methods as $value => $method)
                    <label style="display:flex; align-items:center; gap:1rem; padding:1rem; border:2px solid var(--border); border-radius:var(--radius-sm); margin-bottom:0.75rem; cursor:pointer; transition:all 0.2s;" class="payment-option" data-method="{{ $value }}">
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

                    {{-- VietQR Panel: bank_transfer --}}
                    <div id="panel-bank_transfer" style="display:none; margin-top:1rem; padding:1.5rem; background:linear-gradient(135deg,#f0f7ff,#e8f4fd); border:2px solid #2d98da; border-radius:var(--radius); text-align:center;">
                        <div style="font-size:0.85rem; font-weight:700; color:#1a6b8a; margin-bottom:1rem;">
                            <i class="fas fa-qrcode"></i> Quét mã QR để chuyển khoản
                        </div>
                        <img src="https://img.vietqr.io/image/{{ config('payment.bank_id','MB') }}-{{ config('payment.account_number','0123456789') }}-compact2.png?amount={{ $total }}&addInfo=ResDeli+{{ urlencode(Auth::user()->name) }}&accountName={{ urlencode(config('payment.account_name','RESDELI')) }}"
                             alt="VietQR"
                             style="width:220px; height:220px; border-radius:var(--radius-sm); border:4px solid #fff; box-shadow:0 4px 15px rgba(0,0,0,0.1);">
                        <div style="margin-top:1rem; font-size:0.85rem; color:#333; line-height:2;">
                            <div><strong>Ngân hàng:</strong> {{ config('payment.bank_name','MB Bank') }}</div>
                            <div><strong>Số TK:</strong> <span style="font-family:monospace; font-size:1rem; color:#2d98da; font-weight:700;">{{ config('payment.account_number','0123456789') }}</span></div>
                            <div><strong>Chủ TK:</strong> {{ config('payment.account_name','RESDELI') }}</div>
                            <div><strong>Số tiền:</strong> <span style="color:var(--primary); font-weight:700; font-size:1rem;">{{ number_format($total) }}đ</span></div>
                            <div><strong>Nội dung:</strong> <span style="font-family:monospace; background:#fff; padding:2px 8px; border-radius:4px;">ResDeli {{ Auth::user()->name }}</span></div>
                        </div>
                        <div style="margin-top:1rem; padding:0.75rem; background:#fff3cd; border-radius:var(--radius-sm); font-size:0.78rem; color:#856404;">
                            <i class="fas fa-info-circle"></i> Chuyển khoản xong nhấn <strong>"Xác nhận đặt hàng"</strong>.
                        </div>
                    </div>

                    {{-- MoMo Panel --}}
                    <div id="panel-momo" style="display:none; margin-top:1rem; padding:1.5rem; background:linear-gradient(135deg,#fdf0f8,#fce4f5); border:2px solid #ae2070; border-radius:var(--radius); text-align:center;">
                        <div style="font-size:0.85rem; font-weight:700; color:#ae2070; margin-bottom:1rem;">
                            <i class="fas fa-qrcode"></i> Quét mã QR thanh toán MoMo
                        </div>
                        {{-- QR encode deeplink MoMo --}}
                        @php
                            $momoPhone  = config('payment.momo_phone', '0123456789');
                            $momoName   = config('payment.momo_name', 'RESDELI');
                            $momoLink   = "momo://app?action=payWithApp&isScanQR=true&phone={$momoPhone}&amount={$total}&note=ResDeli+" . urlencode(Auth::user()->name) . "&username={$momoPhone}";
                            $momoQR     = "https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=" . urlencode($momoLink);
                        @endphp
                        <img src="{{ $momoQR }}" alt="MoMo QR"
                             style="width:220px; height:220px; border-radius:var(--radius-sm); border:4px solid #fff; box-shadow:0 4px 15px rgba(174,32,112,0.2);">
                        <div style="margin-top:1rem; font-size:0.85rem; color:#333; line-height:2;">
                            <div style="display:inline-flex; align-items:center; gap:0.5rem; background:#ae2070; color:#fff; padding:0.4rem 1rem; border-radius:50px; font-weight:700; font-size:0.9rem;">
                                <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" style="width:20px; height:20px; border-radius:50%;"> MoMo
                            </div>
                            <div style="margin-top:0.75rem;"><strong>Số điện thoại:</strong> <span style="font-family:monospace; font-size:1rem; color:#ae2070; font-weight:700;">{{ $momoPhone }}</span></div>
                            <div><strong>Tên:</strong> {{ $momoName }}</div>
                            <div><strong>Số tiền:</strong> <span style="color:var(--primary); font-weight:700; font-size:1rem;">{{ number_format($total) }}đ</span></div>
                            <div><strong>Nội dung:</strong> <span style="font-family:monospace; background:#fff; padding:2px 8px; border-radius:4px;">ResDeli {{ Auth::user()->name }}</span></div>
                        </div>
                        <div style="margin-top:1rem; padding:0.75rem; background:#fff3cd; border-radius:var(--radius-sm); font-size:0.78rem; color:#856404;">
                            <i class="fas fa-info-circle"></i> Mở app MoMo → Quét mã → Chuyển tiền → Nhấn <strong>"Xác nhận đặt hàng"</strong>.
                        </div>
                    </div>

                    {{-- ZaloPay Panel --}}
                    <div id="panel-zalopay" style="display:none; margin-top:1rem; padding:1.5rem; background:linear-gradient(135deg,#f0f8ff,#dbeeff); border:2px solid #0068ff; border-radius:var(--radius); text-align:center;">
                        <div style="font-size:0.85rem; font-weight:700; color:#0068ff; margin-bottom:1rem;">
                            <i class="fas fa-qrcode"></i> Quét mã QR thanh toán ZaloPay
                        </div>
                        @php
                            $zaloPhone = config('payment.zalopay_phone', '0123456789');
                            $zaloName  = config('payment.zalopay_name', 'RESDELI');
                            $zaloLink  = "zalopay://transfer?phone={$zaloPhone}&amount={$total}&description=ResDeli+" . urlencode(Auth::user()->name);
                            $zaloQR    = "https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=" . urlencode($zaloLink);
                        @endphp
                        <img src="{{ $zaloQR }}" alt="ZaloPay QR"
                             style="width:220px; height:220px; border-radius:var(--radius-sm); border:4px solid #fff; box-shadow:0 4px 15px rgba(0,104,255,0.2);">
                        <div style="margin-top:1rem; font-size:0.85rem; color:#333; line-height:2;">
                            <div style="display:inline-flex; align-items:center; gap:0.5rem; background:#0068ff; color:#fff; padding:0.4rem 1rem; border-radius:50px; font-weight:700; font-size:0.9rem;">
                                💙 ZaloPay
                            </div>
                            <div style="margin-top:0.75rem;"><strong>Số điện thoại:</strong> <span style="font-family:monospace; font-size:1rem; color:#0068ff; font-weight:700;">{{ $zaloPhone }}</span></div>
                            <div><strong>Tên:</strong> {{ $zaloName }}</div>
                            <div><strong>Số tiền:</strong> <span style="color:var(--primary); font-weight:700; font-size:1rem;">{{ number_format($total) }}đ</span></div>
                            <div><strong>Nội dung:</strong> <span style="font-family:monospace; background:#fff; padding:2px 8px; border-radius:4px;">ResDeli {{ Auth::user()->name }}</span></div>
                        </div>
                        <div style="margin-top:1rem; padding:0.75rem; background:#fff3cd; border-radius:var(--radius-sm); font-size:0.78rem; color:#856404;">
                            <i class="fas fa-info-circle"></i> Mở app ZaloPay → Quét mã → Chuyển tiền → Nhấn <strong>"Xác nhận đặt hàng"</strong>.
                        </div>
                    </div>
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
const ALL_PANELS = ['bank_transfer', 'momo', 'zalopay'];

function selectPayment(method) {
    // Reset tất cả option
    document.querySelectorAll('.payment-option').forEach(l => {
        l.style.borderColor = 'var(--border)';
        l.style.background = '';
    });

    // Highlight option được chọn
    const selected = document.querySelector(`.payment-option[data-method="${method}"]`);
    if (selected) {
        selected.style.borderColor = 'var(--primary)';
        selected.style.background = 'var(--primary-light)';
    }

    // Ẩn tất cả panel QR
    ALL_PANELS.forEach(m => {
        const p = document.getElementById('panel-' + m);
        if (p) p.style.display = 'none';
    });

    // Hiện panel của method được chọn (nếu có)
    const panel = document.getElementById('panel-' + method);
    if (panel) {
        panel.style.display = 'block';
        setTimeout(() => panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' }), 100);
    }
}

document.querySelectorAll('.payment-option').forEach(label => {
    label.addEventListener('click', () => {
        const method = label.dataset.method;
        label.querySelector('input').checked = true;
        selectPayment(method);
    });
});

// Init on load
const checkedInput = document.querySelector('.payment-option input:checked');
if (checkedInput) selectPayment(checkedInput.value);
</script>
@endsection
