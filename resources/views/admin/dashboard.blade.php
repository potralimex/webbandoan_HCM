@extends('layouts.admin')
@section('title', 'Dashboard')

@section('styles')
<style>
.kpi-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:1.25rem; margin-bottom:1.5rem; }
.kpi-card {
    background:#fff; border-radius:12px; padding:1.25rem 1.5rem;
    box-shadow:0 2px 12px rgba(0,0,0,0.07); position:relative; overflow:hidden;
}
.kpi-card::before {
    content:''; position:absolute; top:0; left:0; right:0; height:3px;
}
.kpi-card.blue::before   { background:linear-gradient(90deg,#2d98da,#74b9ff); }
.kpi-card.green::before  { background:linear-gradient(90deg,#20bf6b,#26de81); }
.kpi-card.orange::before { background:linear-gradient(90deg,#FF6B35,#fd9644); }
.kpi-card.red::before    { background:linear-gradient(90deg,#eb3b5a,#fc5c65); }

.kpi-label { font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; color:#6c757d; margin-bottom:0.4rem; }
.kpi-value { font-size:1.9rem; font-weight:800; line-height:1.1; color:#212529; }
.kpi-sub { font-size:0.75rem; color:#6c757d; margin-top:0.4rem; display:flex; align-items:center; gap:0.3rem; }
.kpi-change { font-weight:700; }
.kpi-change.up   { color:#20bf6b; }
.kpi-change.down { color:#eb3b5a; }
.kpi-icon { position:absolute; right:1.25rem; top:50%; transform:translateY(-50%); font-size:2.2rem; opacity:0.1; }

.charts-row { display:grid; grid-template-columns:2fr 1fr; gap:1.25rem; margin-bottom:1.25rem; }
.charts-row-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:1.25rem; margin-bottom:1.25rem; }
.bottom-row { display:grid; grid-template-columns:1.4fr 1fr; gap:1.25rem; }

.chart-card { background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.07); overflow:hidden; }
.chart-header { padding:1rem 1.25rem 0.75rem; border-bottom:1px solid #f0f0f0; display:flex; align-items:center; justify-content:space-between; }
.chart-title { font-size:0.88rem; font-weight:800; color:#212529; }
.chart-subtitle { font-size:0.72rem; color:#6c757d; margin-top:0.15rem; }
.chart-body { padding:1rem 1.25rem 1.25rem; }
.chart-body canvas { max-height:220px; }

.top-rest-item { display:flex; align-items:center; gap:0.85rem; padding:0.7rem 1.25rem; border-bottom:1px solid #f5f5f5; }
.top-rest-item:last-child { border-bottom:none; }
.top-rank { font-size:0.85rem; font-weight:800; min-width:22px; }
.top-rank.gold   { color:#f7b731; }
.top-rank.silver { color:#a0aec0; }
.top-rank.bronze { color:#cd7f32; }
.top-rank.other  { color:#cbd5e0; }
.top-rest-info { flex:1; min-width:0; }
.top-rest-name { font-size:0.82rem; font-weight:700; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.top-rest-meta { font-size:0.7rem; color:#6c757d; }
.top-rest-rev { font-size:0.82rem; font-weight:800; color:#FF6B35; white-space:nowrap; }

.stat-mini-grid { display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; padding:1rem 1.25rem; }
.stat-mini { background:#f8f9fa; border-radius:8px; padding:0.85rem; text-align:center; }
.stat-mini-val { font-size:1.3rem; font-weight:800; color:#212529; }
.stat-mini-lbl { font-size:0.68rem; color:#6c757d; font-weight:600; text-transform:uppercase; letter-spacing:0.4px; margin-top:0.2rem; }

.recent-table { width:100%; border-collapse:collapse; }
.recent-table th { background:#f8f9fa; padding:0.65rem 1rem; font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:#6c757d; text-align:left; border-bottom:2px solid #e9ecef; }
.recent-table td { padding:0.75rem 1rem; border-bottom:1px solid #f5f5f5; font-size:0.82rem; vertical-align:middle; }
.recent-table tr:last-child td { border-bottom:none; }
.recent-table tr:hover td { background:#fafafa; }

@media(max-width:1200px) {
    .kpi-grid { grid-template-columns:repeat(2,1fr); }
    .charts-row, .charts-row-3, .bottom-row { grid-template-columns:1fr; }
}
</style>
@endsection

@section('content')

{{-- ── KPI CARDS ── --}}
@php
    function pctChange($new, $old) {
        if ($old == 0) return $new > 0 ? 100 : 0;
        return round(($new - $old) / $old * 100, 1);
    }
    $revChange  = pctChange($stats['revenueThisMonth'],   $stats['revenueLastMonth']);
    $ordChange  = pctChange($stats['ordersThisMonth'],    $stats['ordersLastMonth']);
    $usrChange  = pctChange($stats['usersThisMonth'],     $stats['usersLastMonth']);
    $canChange  = pctChange($stats['cancelledThisMonth'], $stats['cancelledLastMonth']);
@endphp

<div class="kpi-grid">
    <div class="kpi-card orange">
        <div class="kpi-label">Doanh thu tháng này</div>
        <div class="kpi-value">{{ number_format($stats['revenueThisMonth']/1000000, 1) }}M</div>
        <div class="kpi-sub">
            <span class="kpi-change {{ $revChange >= 0 ? 'up' : 'down' }}">
                {{ $revChange >= 0 ? '▲' : '▼' }} {{ abs($revChange) }}%
            </span>
            so với tháng trước
        </div>
        <i class="fas fa-coins kpi-icon"></i>
    </div>
    <div class="kpi-card blue">
        <div class="kpi-label">Đơn hàng tháng này</div>
        <div class="kpi-value">{{ number_format($stats['ordersThisMonth']) }}</div>
        <div class="kpi-sub">
            <span class="kpi-change {{ $ordChange >= 0 ? 'up' : 'down' }}">
                {{ $ordChange >= 0 ? '▲' : '▼' }} {{ abs($ordChange) }}%
            </span>
            so với tháng trước
        </div>
        <i class="fas fa-receipt kpi-icon"></i>
    </div>
    <div class="kpi-card green">
        <div class="kpi-label">Người dùng mới</div>
        <div class="kpi-value">{{ number_format($stats['usersThisMonth']) }}</div>
        <div class="kpi-sub">
            <span class="kpi-change {{ $usrChange >= 0 ? 'up' : 'down' }}">
                {{ $usrChange >= 0 ? '▲' : '▼' }} {{ abs($usrChange) }}%
            </span>
            so với tháng trước
        </div>
        <i class="fas fa-user-plus kpi-icon"></i>
    </div>
    <div class="kpi-card red">
        <div class="kpi-label">Đơn hủy tháng này</div>
        <div class="kpi-value">{{ number_format($stats['cancelledThisMonth']) }}</div>
        <div class="kpi-sub">
            <span class="kpi-change {{ $canChange <= 0 ? 'up' : 'down' }}">
                {{ $canChange >= 0 ? '▲' : '▼' }} {{ abs($canChange) }}%
            </span>
            so với tháng trước
        </div>
        <i class="fas fa-times-circle kpi-icon"></i>
    </div>
</div>

{{-- ── ROW 1: Line chart + Donut ── --}}
<div class="charts-row">
    {{-- Doanh thu theo tháng --}}
    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title"><i class="fas fa-chart-line" style="color:#FF6B35;margin-right:6px;"></i>Doanh thu theo tháng</div>
                <div class="chart-subtitle">12 tháng gần nhất (đơn đã giao)</div>
            </div>
            <span style="font-size:0.75rem;color:#6c757d;">{{ now()->format('Y') }}</span>
        </div>
        <div class="chart-body">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Trạng thái đơn hàng --}}
    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title"><i class="fas fa-chart-pie" style="color:#2d98da;margin-right:6px;"></i>Trạng thái đơn hàng</div>
                <div class="chart-subtitle">Tổng tất cả đơn</div>
            </div>
        </div>
        <div class="chart-body" style="display:flex;flex-direction:column;align-items:center;">
            <canvas id="statusChart" style="max-height:180px;max-width:180px;"></canvas>
            <div id="statusLegend" style="margin-top:0.75rem;width:100%;"></div>
        </div>
    </div>
</div>

{{-- ── ROW 2: Bar chart đơn hàng + Donut thanh toán + Mini stats ── --}}
<div class="charts-row-3">
    {{-- Đơn hàng 30 ngày --}}
    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title"><i class="fas fa-chart-bar" style="color:#20bf6b;margin-right:6px;"></i>Đơn hàng 30 ngày qua</div>
                <div class="chart-subtitle">Số lượng đơn theo ngày</div>
            </div>
        </div>
        <div class="chart-body">
            <canvas id="dailyChart"></canvas>
        </div>
    </div>

    {{-- Phương thức thanh toán --}}
    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title"><i class="fas fa-credit-card" style="color:#a55eea;margin-right:6px;"></i>Phương thức thanh toán</div>
                <div class="chart-subtitle">Tỉ lệ sử dụng</div>
            </div>
        </div>
        <div class="chart-body" style="display:flex;flex-direction:column;align-items:center;">
            <canvas id="paymentChart" style="max-height:180px;max-width:180px;"></canvas>
            <div id="paymentLegend" style="margin-top:0.75rem;width:100%;"></div>
        </div>
    </div>

    {{-- Tổng quan nhanh --}}
    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title"><i class="fas fa-tachometer-alt" style="color:#f7b731;margin-right:6px;"></i>Tổng quan hệ thống</div>
                <div class="chart-subtitle">Số liệu tích lũy</div>
            </div>
        </div>
        <div class="stat-mini-grid">
            <div class="stat-mini">
                <div class="stat-mini-val" style="color:#FF6B35;">{{ number_format($stats['revenue']/1000000,1) }}M</div>
                <div class="stat-mini-lbl">Tổng doanh thu</div>
            </div>
            <div class="stat-mini">
                <div class="stat-mini-val" style="color:#2d98da;">{{ number_format($stats['orders']) }}</div>
                <div class="stat-mini-lbl">Tổng đơn hàng</div>
            </div>
            <div class="stat-mini">
                <div class="stat-mini-val" style="color:#20bf6b;">{{ number_format($stats['users']) }}</div>
                <div class="stat-mini-lbl">Người dùng</div>
            </div>
            <div class="stat-mini">
                <div class="stat-mini-val" style="color:#a55eea;">{{ number_format($stats['restaurants']) }}</div>
                <div class="stat-mini-lbl">Nhà hàng</div>
            </div>
            <div class="stat-mini">
                <div class="stat-mini-val" style="color:#f7b731;">{{ number_format($stats['pending']) }}</div>
                <div class="stat-mini-lbl">Đơn chờ</div>
            </div>
            <div class="stat-mini">
                <div class="stat-mini-val" style="color:#eb3b5a;">{{ number_format($stats['reviews']) }}</div>
                <div class="stat-mini-lbl">Đánh giá chờ</div>
            </div>
        </div>
    </div>
</div>

{{-- ── ROW 3: Recent orders + Top restaurants ── --}}
<div class="bottom-row">
    {{-- Đơn hàng gần đây --}}
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-receipt" style="color:#FF6B35;margin-right:6px;"></i>Đơn hàng gần đây</div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm">Xem tất cả</a>
        </div>
        <div style="overflow-x:auto;">
            <table class="recent-table">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Nhà hàng</th>
                        <th>Trạng thái</th>
                        <th>Tổng tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr>
                        <td style="font-weight:700;font-size:0.75rem;color:#FF6B35;">{{ $order->order_number }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td style="color:#6c757d;">{{ $order->restaurant->name }}</td>
                        <td>{!! $order->status_badge !!}</td>
                        <td style="font-weight:700;color:#212529;">{{ number_format($order->total) }}đ</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Top nhà hàng --}}
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-trophy" style="color:#f7b731;margin-right:6px;"></i>Top nhà hàng doanh thu</div>
            <a href="{{ route('admin.restaurants.index') }}" class="btn btn-light btn-sm">Xem tất cả</a>
        </div>
        @foreach($topRestaurants as $i => $r)
        @php
            $ranks = ['gold','silver','bronze','other','other'];
            $rankClass = $ranks[$i] ?? 'other';
            $rankIcons = ['🥇','🥈','🥉','4','5'];
        @endphp
        <div class="top-rest-item">
            <span class="top-rank {{ $rankClass }}">{{ $rankIcons[$i] ?? $i+1 }}</span>
            <img src="{{ $r->image_url }}" alt="" style="width:38px;height:38px;border-radius:8px;object-fit:cover;"
                 onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=80&h=80&fit=crop'">
            <div class="top-rest-info">
                <div class="top-rest-name">{{ $r->name }}</div>
                <div class="top-rest-meta">{{ $r->orders_count }} đơn · ⭐ {{ number_format($r->rating,1) }}</div>
            </div>
            <div class="top-rest-rev">{{ number_format(($r->orders_sum_total ?? 0)/1000) }}K</div>
        </div>
        @endforeach

        {{-- Quick actions --}}
        <div style="padding:1rem 1.25rem;border-top:1px solid #f0f0f0;display:grid;grid-template-columns:1fr 1fr;gap:0.6rem;">
            <a href="{{ route('admin.restaurants.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Thêm nhà hàng</a>
            <a href="{{ route('admin.orders.index') }}?status=pending" class="btn btn-warning btn-sm"><i class="fas fa-clock"></i> Đơn chờ ({{ $stats['pending'] }})</a>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.color = '#6c757d';

// ── 1. Revenue Line Chart ──
@php
    $months = ['T1','T2','T3','T4','T5','T6','T7','T8','T9','T10','T11','T12'];
    $revenueLabels = [];
    $revenueValues = [];
    $orderCountValues = [];
    foreach ($monthlySales as $row) {
        $revenueLabels[]    = $months[$row->month - 1] . '/' . substr($row->year, 2);
        $revenueValues[]    = round($row->total / 1000000, 2);
        $orderCountValues[] = $row->count;
    }
@endphp
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($revenueLabels) !!},
        datasets: [{
            label: 'Doanh thu (triệu đ)',
            data: {!! json_encode($revenueValues) !!},
            borderColor: '#FF6B35',
            backgroundColor: 'rgba(255,107,53,0.1)',
            borderWidth: 2.5,
            pointBackgroundColor: '#FF6B35',
            pointRadius: 4,
            tension: 0.4,
            fill: true,
            yAxisID: 'y',
        },{
            label: 'Số đơn',
            data: {!! json_encode($orderCountValues) !!},
            borderColor: '#2d98da',
            backgroundColor: 'rgba(45,152,218,0.08)',
            borderWidth: 2,
            pointBackgroundColor: '#2d98da',
            pointRadius: 3,
            tension: 0.4,
            fill: false,
            yAxisID: 'y1',
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: true,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } } },
        scales: {
            y:  { position: 'left',  grid: { color: '#f0f0f0' }, ticks: { callback: v => v + 'M' } },
            y1: { position: 'right', grid: { drawOnChartArea: false }, ticks: { stepSize: 1 } },
            x:  { grid: { display: false } }
        }
    }
});

// ── 2. Order Status Donut ──
@php
    $statusLabels = ['Chờ xác nhận','Đã xác nhận','Đang chuẩn bị','Đang giao','Đã giao','Đã hủy'];
    $statusKeys   = ['pending','confirmed','preparing','delivering','delivered','cancelled'];
    $statusValues = array_map(fn($k) => $orderStatusData[$k] ?? 0, $statusKeys);
    $statusColors = ['#f7b731','#2d98da','#a55eea','#45aaf2','#20bf6b','#eb3b5a'];
@endphp
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($statusLabels) !!},
        datasets: [{ data: {!! json_encode($statusValues) !!}, backgroundColor: {!! json_encode($statusColors) !!}, borderWidth: 2, borderColor: '#fff' }]
    },
    options: {
        responsive: true, maintainAspectRatio: true, cutout: '65%',
        plugins: { legend: { display: false } }
    }
});
// Custom legend
const statusLegendEl = document.getElementById('statusLegend');
const sLabels = {!! json_encode($statusLabels) !!};
const sValues = {!! json_encode($statusValues) !!};
const sColors = {!! json_encode($statusColors) !!};
const sTotal  = sValues.reduce((a,b) => a+b, 0);
statusLegendEl.innerHTML = sLabels.map((l,i) =>
    `<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;font-size:0.72rem;">
        <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:${sColors[i]};margin-right:5px;"></span>${l}</span>
        <span style="font-weight:700;">${sValues[i]} <span style="color:#aaa;font-weight:400;">(${sTotal ? Math.round(sValues[i]/sTotal*100) : 0}%)</span></span>
    </div>`
).join('');

// ── 3. Daily Orders Bar Chart ──
@php
    $dailyLabels = [];
    $dailyCounts = [];
    foreach ($dailyOrders as $d) {
        $dailyLabels[] = \Carbon\Carbon::parse($d->date)->format('d/m');
        $dailyCounts[] = $d->count;
    }
@endphp
new Chart(document.getElementById('dailyChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($dailyLabels) !!},
        datasets: [{
            label: 'Số đơn',
            data: {!! json_encode($dailyCounts) !!},
            backgroundColor: 'rgba(32,191,107,0.75)',
            borderRadius: 4,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { grid: { color: '#f0f0f0' }, ticks: { stepSize: 1 } },
            x: { grid: { display: false }, ticks: { maxTicksLimit: 10, font: { size: 10 } } }
        }
    }
});

// ── 4. Payment Method Donut ──
@php
    $payLabels = ['Tiền mặt','Thẻ','MoMo'];
    $payKeys   = ['cash','card','momo'];
    $payValues = array_map(fn($k) => $paymentData[$k] ?? 0, $payKeys);
    $payColors = ['#f7b731','#2d98da','#e91e8c'];
@endphp
new Chart(document.getElementById('paymentChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($payLabels) !!},
        datasets: [{ data: {!! json_encode($payValues) !!}, backgroundColor: {!! json_encode($payColors) !!}, borderWidth: 2, borderColor: '#fff' }]
    },
    options: {
        responsive: true, maintainAspectRatio: true, cutout: '65%',
        plugins: { legend: { display: false } }
    }
});
const payLegendEl = document.getElementById('paymentLegend');
const pLabels = {!! json_encode($payLabels) !!};
const pValues = {!! json_encode($payValues) !!};
const pColors = {!! json_encode($payColors) !!};
const pTotal  = pValues.reduce((a,b) => a+b, 0);
payLegendEl.innerHTML = pLabels.map((l,i) =>
    `<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;font-size:0.72rem;">
        <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:${pColors[i]};margin-right:5px;"></span>${l}</span>
        <span style="font-weight:700;">${pValues[i]} <span style="color:#aaa;font-weight:400;">(${pTotal ? Math.round(pValues[i]/pTotal*100) : 0}%)</span></span>
    </div>`
).join('');
</script>
@endsection
