<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng mới #{{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f4f4; color: #333; }
        .wrapper { max-width: 620px; margin: 30px auto; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }

        /* Header */
        .header { background: linear-gradient(135deg, #FF6B35, #e55a26); padding: 32px 40px; text-align: center; }
        .header .logo { font-size: 28px; font-weight: 800; color: #fff; letter-spacing: -0.5px; }
        .header .logo span { color: #ffe0d4; }
        .header .subtitle { color: rgba(255,255,255,0.85); font-size: 14px; margin-top: 6px; }

        /* Alert banner */
        .alert-banner { background: #fff3ef; border-left: 4px solid #FF6B35; padding: 14px 40px; font-size: 14px; color: #c0392b; font-weight: 600; }

        /* Body */
        .body { padding: 32px 40px; }
        .section-title { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #FF6B35; margin-bottom: 12px; padding-bottom: 6px; border-bottom: 2px solid #fff3ef; }

        /* Info grid */
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 28px; }
        .info-item { background: #f8f9fa; border-radius: 8px; padding: 12px 16px; }
        .info-item .label { font-size: 11px; font-weight: 600; color: #999; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .info-item .value { font-size: 14px; font-weight: 600; color: #333; }
        .info-item .value.highlight { color: #FF6B35; font-size: 16px; }

        /* Order items table */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px; }
        .items-table thead tr { background: #FF6B35; color: #fff; }
        .items-table thead th { padding: 10px 14px; text-align: left; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        .items-table tbody tr { border-bottom: 1px solid #f0f0f0; }
        .items-table tbody tr:last-child { border-bottom: none; }
        .items-table tbody tr:nth-child(even) { background: #fafafa; }
        .items-table tbody td { padding: 10px 14px; vertical-align: middle; }
        .items-table .item-name { font-weight: 600; }
        .items-table .item-price { color: #666; font-size: 13px; }
        .items-table .text-right { text-align: right; }
        .items-table .subtotal { font-weight: 700; color: #FF6B35; }

        /* Totals */
        .totals { background: #f8f9fa; border-radius: 8px; padding: 16px 20px; margin-bottom: 28px; }
        .totals-row { display: flex; justify-content: space-between; padding: 5px 0; font-size: 14px; color: #666; }
        .totals-row.grand { border-top: 2px solid #e0e0e0; margin-top: 8px; padding-top: 12px; font-size: 17px; font-weight: 800; color: #FF6B35; }

        /* Delivery info */
        .delivery-box { background: #f0f7ff; border-radius: 8px; padding: 16px 20px; margin-bottom: 28px; font-size: 14px; }
        .delivery-box .row { display: flex; gap: 10px; margin-bottom: 6px; }
        .delivery-box .row:last-child { margin-bottom: 0; }
        .delivery-box .icon { width: 20px; flex-shrink: 0; text-align: center; }
        .delivery-box .text { color: #444; }

        /* CTA button */
        .cta { text-align: center; margin-bottom: 28px; }
        .cta a { display: inline-block; background: #FF6B35; color: #fff; text-decoration: none; padding: 13px 32px; border-radius: 8px; font-weight: 700; font-size: 15px; }

        /* Status badge */
        .badge { display: inline-block; padding: 3px 10px; border-radius: 50px; font-size: 12px; font-weight: 600; background: #fff3ef; color: #FF6B35; border: 1px solid #FF6B35; }

        /* Footer */
        .footer { background: #1a1a2e; padding: 24px 40px; text-align: center; }
        .footer p { color: #adb5bd; font-size: 12px; line-height: 1.8; }
        .footer .brand { color: #FF6B35; font-weight: 700; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Header --}}
    <div class="header">
        <div class="logo">🍜 Res<span>Deli</span></div>
        <div class="subtitle">Hệ thống đặt món ăn trực tuyến</div>
    </div>

    {{-- Alert --}}
    <div class="alert-banner">
        🔔 Có đơn hàng mới cần xử lý!
    </div>

    <div class="body">

        {{-- Order info --}}
        <div class="section-title">Thông tin đơn hàng</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="label">Mã đơn hàng</div>
                <div class="value highlight">{{ $order->order_number }}</div>
            </div>
            <div class="info-item">
                <div class="label">Trạng thái</div>
                <div class="value"><span class="badge">Chờ xác nhận</span></div>
            </div>
            <div class="info-item">
                <div class="label">Khách hàng</div>
                <div class="value">{{ $order->user?->name ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="label">Nhà hàng</div>
                <div class="value">{{ $order->restaurant?->name ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="label">Thời gian đặt</div>
                <div class="value">{{ $order->created_at->format('H:i, d/m/Y') }}</div>
            </div>
            <div class="info-item">
                <div class="label">Thanh toán</div>
                <div class="value">{{ $order->payment_method_label }}</div>
            </div>
        </div>

        {{-- Order items --}}
        <div class="section-title">Danh sách món</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên món</th>
                    <th class="text-right">Đơn giá</th>
                    <th class="text-right">SL</th>
                    <th class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $i => $item)
                <tr>
                    <td style="color:#999; font-size:13px;">{{ $i + 1 }}</td>
                    <td>
                        <div class="item-name">{{ $item->item_name }}</div>
                        @if($item->notes)
                            <div style="font-size:12px; color:#999; margin-top:2px;">{{ $item->notes }}</div>
                        @endif
                    </td>
                    <td class="text-right item-price">{{ number_format($item->item_price) }}đ</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right subtotal">{{ number_format($item->subtotal) }}đ</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="totals">
            <div class="totals-row">
                <span>Tạm tính</span>
                <span>{{ number_format($order->subtotal) }}đ</span>
            </div>
            <div class="totals-row">
                <span>Phí giao hàng</span>
                <span>{{ $order->delivery_fee > 0 ? number_format($order->delivery_fee).'đ' : 'Miễn phí' }}</span>
            </div>
            <div class="totals-row grand">
                <span>Tổng cộng</span>
                <span>{{ number_format($order->total) }}đ</span>
            </div>
        </div>

        {{-- Delivery info --}}
        <div class="section-title">Thông tin giao hàng</div>
        <div class="delivery-box">
            <div class="row">
                <div class="icon">📍</div>
                <div class="text">{{ $order->delivery_address }}</div>
            </div>
            <div class="row">
                <div class="icon">📞</div>
                <div class="text">{{ $order->phone }}</div>
            </div>
            @if($order->notes)
            <div class="row">
                <div class="icon">📝</div>
                <div class="text">{{ $order->notes }}</div>
            </div>
            @endif
        </div>

        {{-- CTA --}}
        <div class="cta">
            <a href="{{ config('app.url') }}/admin/orders">
                Xem & Xử lý đơn hàng →
            </a>
        </div>

    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>Email này được gửi tự động từ hệ thống <span class="brand">ResDeli</span>.<br>
        Vui lòng không trả lời email này.</p>
    </div>

</div>
</body>
</html>
