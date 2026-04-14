@extends('layouts.app')
@section('title', 'Chi tiết đơn #' . $order->order_number . ' - ResDeli')
@section('content')
<div class="container" style="padding: 2rem 1rem;">
    <div class="d-flex gap-2 mb-3" style="font-size:0.875rem;">
        <a href="{{ route('orders.index') }}" style="color:var(--text-muted); text-decoration:none;"><i class="fas fa-arrow-left"></i> Đơn hàng</a>
        <span style="color:var(--text-muted);">/</span>
        <span>{{ $order->order_number }}</span>
    </div>

    @if($errors->any())
    <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i><div>{{ $errors->first() }}</div></div>
    @endif

    <div style="display:grid; grid-template-columns:1fr 300px; gap:2rem; align-items:start;">
        <!-- Main -->
        <div>
            <!-- Status Card -->
            <div class="card" style="padding:2rem; margin-bottom:1.5rem;">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1.5">
                    <div>
                        <p style="font-size:0.8rem; color:var(--text-muted); margin-bottom:0.25rem;">MÃ ĐƠN HÀNG</p>
                        <h1 style="font-size:1.5rem; font-weight:800; letter-spacing:-0.5px;">{{ $order->order_number }}</h1>
                    </div>
                    {!! $order->status_badge !!}
                </div>
                <div style="font-size:0.875rem; color:var(--text-muted);">
                    Đặt lúc: {{ $order->created_at->format('H:i, d/m/Y') }}
                </div>

                <!-- Progress Steps -->
                @php
                    $steps = ['pending'=>0,'confirmed'=>1,'preparing'=>2,'delivering'=>3,'delivered'=>4,'cancelled'=>99];
                    $currentStep = $steps[$order->status] ?? 0;
                @endphp
                @if($order->status !== 'cancelled')
                <div style="margin-top:2rem; display:flex; gap:0; align-items:center; overflow-x:auto;">
                    @foreach(['Chờ xác nhận','Đã xác nhận','Đang chuẩn bị','Đang giao','Đã giao'] as $i => $label)
                    <div style="flex:1; text-align:center; position:relative; min-width:80px;">
                        @if($i > 0)
                        <div style="position:absolute; top:14px; left:0; right:50%; height:2px; background: {{ $i <= $currentStep ? 'var(--primary)' : 'var(--border)' }};"></div>
                        <div style="position:absolute; top:14px; left:50%; right:0; height:2px; background: {{ $i < $currentStep ? 'var(--primary)' : 'var(--border)' }};"></div>
                        @endif
                        @if($i == 0)
                        <div style="position:absolute; top:14px; left:50%; right:0; height:2px; background: {{ $i < $currentStep ? 'var(--primary)' : 'var(--border)' }};"></div>
                        @endif
                        <div style="width:28px; height:28px; border-radius:50%; margin:0 auto 0.5rem; display:flex; align-items:center; justify-content:center; position:relative; z-index:1; font-size:0.75rem; font-weight:700; border:2px solid {{ $i <= $currentStep ? 'var(--primary)' : 'var(--border)' }}; background: {{ $i <= $currentStep ? 'var(--primary)' : '#fff' }}; color: {{ $i <= $currentStep ? '#fff' : 'var(--text-muted)' }};">
                            {{ $i <= $currentStep ? '✓' : ($i+1) }}
                        </div>
                        <div style="font-size:0.72rem; color: {{ $i <= $currentStep ? 'var(--primary)' : 'var(--text-muted)' }}; font-weight: {{ $i <= $currentStep ? '700' : '400' }};">{{ $label }}</div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="alert alert-danger mt-2" style="margin-bottom:0;"><i class="fas fa-times-circle"></i><span>Đơn hàng đã bị hủy</span></div>
                @endif
            </div>

            <!-- Order Items -->
            <div class="card" style="padding:1.5rem; margin-bottom:1.5rem;">
                <h2 style="font-size:1rem; font-weight:800; margin-bottom:1.25rem; border-bottom:1px solid var(--border); padding-bottom:0.75rem;">
                    <i class="fas fa-utensils" style="color:var(--primary);"></i> Món đã đặt
                </h2>
                @foreach($order->items as $item)
                <div style="display:flex; justify-content:space-between; padding:0.75rem 0; border-bottom:1px solid var(--border); align-items:center;">
                    <div>
                        <strong style="font-size:0.875rem;">{{ $item->item_name }}</strong>
                        <span style="color:var(--text-muted); font-size:0.8rem;"> × {{ $item->quantity }}</span>
                    </div>
                    <span style="font-weight:700;">{{ number_format($item->subtotal) }}đ</span>
                </div>
                @endforeach
                <div class="d-flex justify-content-between" style="padding-top:0.75rem; font-size:0.875rem; color:var(--text-muted);">
                    <span>Tạm tính</span><span>{{ number_format($order->subtotal) }}đ</span>
                </div>
                <div class="d-flex justify-content-between" style="padding:0.25rem 0; font-size:0.875rem; color:var(--text-muted);">
                    <span>Phí giao hàng</span><span>{{ number_format($order->delivery_fee) }}đ</span>
                </div>
                <div class="d-flex justify-content-between" style="padding-top:0.75rem; border-top:1px solid var(--border); font-size:1.1rem; font-weight:800;">
                    <span>Tổng cộng</span><span style="color:var(--primary);">{{ number_format($order->total) }}đ</span>
                </div>
            </div>

            <!-- Review Form (only for delivered orders without review) -->
            @if($order->status === 'delivered' && !$order->review)
            <div class="card" style="padding:1.5rem; margin-bottom:1.5rem;">
                <h2 style="font-size:1rem; font-weight:800; margin-bottom:1.25rem;"><i class="fas fa-star" style="color:#f7b731;"></i> Đánh giá trải nghiệm</h2>
                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <input type="hidden" name="restaurant_id" value="{{ $order->restaurant_id }}">
                    <div class="form-group">
                        <label class="form-label">Số sao *</label>
                        <div class="star-select">
                            @for($s=5; $s>=1; $s--)
                            <input type="radio" id="star{{ $s }}" name="rating" value="{{ $s }}">
                            <label for="star{{ $s }}">★</label>
                            @endfor
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nhận xét (tùy chọn)</label>
                        <textarea name="comment" class="form-control" placeholder="Chia sẻ trải nghiệm của bạn..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Gửi đánh giá</button>
                </form>
            </div>
            @elseif($order->review)
            <div class="card" style="padding:1.5rem; margin-bottom:1.5rem; background:var(--primary-light);">
                <h2 style="font-size:1rem; font-weight:800; margin-bottom:1rem;"><i class="fas fa-star" style="color:#f7b731;"></i> Đánh giá của bạn</h2>
                <div class="stars" style="margin-bottom:0.5rem;">
                    @for($i=1; $i<=5; $i++)
                        <i class="fas fa-star {{ $i <= $order->review->rating ? '' : 'empty' }}"></i>
                    @endfor
                </div>
                @if($order->review->comment)
                <p style="font-size:0.875rem;">{{ $order->review->comment }}</p>
                @endif
                @if(!$order->review->is_approved)
                <p style="font-size:0.8rem; color:var(--text-muted); margin-top:0.5rem; font-style:italic;">Đang chờ duyệt...</p>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div>
            <div class="card" style="padding:1.5rem; margin-bottom:1rem;">
                <h3 style="font-size:0.95rem; font-weight:800; margin-bottom:1rem; border-bottom:1px solid var(--border); padding-bottom:0.75rem;">
                    <i class="fas fa-store" style="color:var(--primary);"></i> Nhà hàng
                </h3>
                <div style="display:flex; gap:0.75rem; align-items:center; margin-bottom:1rem;">
                    <img src="{{ $order->restaurant->image_url }}" alt="" style="width:50px;height:50px;border-radius:var(--radius-sm);object-fit:cover;" onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=100&h=100&fit=crop'">
                    <div>
                        <strong style="font-size:0.875rem;">{{ $order->restaurant->name }}</strong>
                        <div style="font-size:0.78rem; color:var(--text-muted);">{{ $order->restaurant->city }}</div>
                    </div>
                </div>
                <a href="{{ route('restaurants.show', $order->restaurant->slug) }}" class="btn btn-light btn-sm btn-block text-center">Xem nhà hàng</a>
            </div>

            <div class="card" style="padding:1.5rem; margin-bottom:1rem;">
                <h3 style="font-size:0.95rem; font-weight:800; margin-bottom:1rem; border-bottom:1px solid var(--border); padding-bottom:0.75rem;">
                    <i class="fas fa-shipping-fast" style="color:var(--primary);"></i> Thông tin giao hàng
                </h3>
                <div style="font-size:0.875rem;">
                    <div style="margin-bottom:0.5rem;"><i class="fas fa-map-marker-alt" style="width:18px; color:var(--primary);"></i> {{ $order->delivery_address }}</div>
                    <div style="margin-bottom:0.5rem;"><i class="fas fa-phone" style="width:18px; color:var(--primary);"></i> {{ $order->phone }}</div>
                    <div style="margin-bottom:0.5rem;"><i class="fas fa-credit-card" style="width:18px; color:var(--primary);"></i>
                        @switch($order->payment_method)
                            @case('cash')
                                💵 Tiền mặt khi nhận hàng
                                @break
                            @case('momo')
                                📱 MoMo
                                @break
                            @case('bank_transfer')
                                🏦 Chuyển khoản
                                @break
                            @default
                                {{ $order->payment_method }}
                        @endswitch
                    </div>
                    @if($order->notes)
                    <div><i class="fas fa-sticky-note" style="width:18px; color:var(--primary);"></i> {{ $order->notes }}</div>
                    @endif
                </div>
            </div>

            @if($order->status === 'pending')
            <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?')">
                @csrf
                <button type="submit" class="btn btn-danger btn-block">
                    <i class="fas fa-times"></i> Hủy đơn hàng
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
