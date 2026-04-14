@extends('layouts.admin')
@section('title', 'Dashboard')
@section('styles')
<style>
.chart-container {
    position: relative;
    height: 300px;
    width: 100%;
}
.chart-container canvas {
    width: 100% !important;
    height: 100% !important;
}
.chart-empty {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 220px;
    color: var(--text-muted);
    font-size: 0.95rem;
    border: 1px dashed var(--border);
    border-radius: var(--radius-sm);
    background: rgba(0, 0, 0, 0.02);
    text-align: center;
    padding: 1rem;
}
.card-body {
    padding: 1rem;
}
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}
.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.2s;
}
.stat-card:hover {
    transform: translateY(-2px);
}
.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary);
}
.stat-label {
    color: var(--text-muted);
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}
</style>
@endsection
@section('content')
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff3ef;">
            <i class="fas fa-users" style="color:var(--primary);"></i>
        </div>
        <div>
            <div class="stat-label">Người dùng</div>
            <div id="statUsers" class="stat-value">{{ number_format($stats['users']) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#e8f5e9;">
            <i class="fas fa-store" style="color:var(--success);"></i>
        </div>
        <div>
            <div class="stat-label">Nhà hàng</div>
            <div id="statRestaurants" class="stat-value">{{ number_format($stats['restaurants']) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#e3f2fd;">
            <i class="fas fa-receipt" style="color:var(--info);"></i>
        </div>
        <div>
            <div class="stat-label">Đơn hàng</div>
            <div id="statOrders" class="stat-value">{{ number_format($stats['orders']) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef5d9;">
            <i class="fas fa-coins" style="color:var(--warning);"></i>
        </div>
        <div>
            <div class="stat-label">Doanh thu</div>
            <div id="statRevenue" class="stat-value" style="font-size:1.3rem;">{{ number_format($stats['revenue']/1000000, 1) }}M</div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="grid grid-2 mb-4">
    <!-- Revenue Chart -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-chart-line" style="color:var(--primary);"></i> Doanh thu theo tháng</h3>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Orders Status Chart -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-chart-pie" style="color:var(--success);"></i> Trạng thái đơn hàng</h3>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="ordersStatusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Restaurant Performance Chart -->
<div class="card mb-4">
    <div class="card-header">
        <h3><i class="fas fa-chart-bar" style="color:var(--warning);"></i> Top nhà hàng theo số đơn hàng</h3>
    </div>
    <div class="card-body">
        <div class="chart-container" style="height: 250px;">
            <canvas id="restaurantsChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-2">
    <!-- Recent Orders -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-receipt" style="color:var(--primary);"></i> Đơn hàng gần đây</h3>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm">Xem tất cả</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Trạng thái</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.index') }}?search={{ $order->order_number }}" style="color:var(--primary); text-decoration:none; font-weight:600; font-size:0.78rem;">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td>
                            <div style="font-weight:600; font-size:0.85rem;">{{ $order->user->name }}</div>
                            <div style="font-size:0.75rem; color:var(--text-muted);">{{ $order->restaurant->name }}</div>
                        </td>
                        <td>{!! $order->status_badge !!}</td>
                        <td style="font-weight:700; color:var(--primary);">{{ number_format($order->total) }}đ</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Restaurants + Quick Actions -->
    <div>
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fas fa-trophy" style="color:var(--warning);"></i> Top nhà hàng</h3>
            </div>
            <div style="padding:0.25rem 0;">
                @foreach($topRestaurants as $i => $r)
                <div style="display:flex; align-items:center; gap:1rem; padding:0.75rem 1.25rem; border-bottom:1px solid var(--border);">
                    <span style="font-size:1rem; font-weight:800; color:{{ $i < 3 ? 'var(--warning)' : 'var(--text-muted)' }}; min-width:20px;">{{ $i+1 }}</span>
                    <img src="{{ $r->image_url }}" alt="" style="width:40px;height:40px;border-radius:var(--radius-sm);object-fit:cover;" onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=80&h=80&fit=crop'">
                    <div style="flex:1;">
                        <div style="font-weight:600; font-size:0.875rem;">{{ $r->name }}</div>
                        <div style="font-size:0.75rem; color:var(--text-muted);">{{ $r->city }}</div>
                    </div>
                    <span class="badge badge-warning">⭐ {{ number_format($r->rating, 1) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3>⚡ Hành động nhanh</h3></div>
            <div style="padding:1rem; display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                <a href="{{ route('admin.restaurants.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm nhà hàng</a>
                <a href="{{ route('admin.menu-items.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Thêm món ăn</a>
                <a href="{{ route('admin.reviews.index') }}?status=pending" class="btn btn-warning"><i class="fas fa-star"></i> Duyệt đánh giá ({{ $stats['reviews'] }})</a>
                <a href="{{ route('admin.orders.index') }}?status=pending" class="btn btn-info"><i class="fas fa-receipt"></i> Đơn chờ ({{ $stats['pending'] }})</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const charts = {};

    function buildConfig(type, data, options = {}) {
        return { type, data, options };
    }

    function renderChart(canvasId, config, emptyMessage) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;
        const container = canvas.closest('.chart-container');
        const hasData = config.data.datasets.some(dataset => Array.isArray(dataset.data) && dataset.data.length > 0 && dataset.data.some(value => value !== null && value !== undefined));
        if (!hasData) {
            if (container) {
                container.innerHTML = `<div class="chart-empty">${emptyMessage}</div>`;
            }
            if (charts[canvasId]) {
                charts[canvasId].destroy();
                delete charts[canvasId];
            }
            return;
        }
        if (container && container.querySelector('.chart-empty')) {
            container.innerHTML = `<canvas id="${canvasId}"></canvas>`;
        }
        const updatedCanvas = document.getElementById(canvasId);
        if (!updatedCanvas) return;

        if (charts[canvasId]) {
            charts[canvasId].data = config.data;
            charts[canvasId].options = config.options;
            charts[canvasId].update();
            return;
        }

        charts[canvasId] = new Chart(updatedCanvas.getContext('2d'), config);
    }

    function updateStats(stats) {
        document.getElementById('statUsers').textContent = new Intl.NumberFormat('vi-VN').format(stats.users);
        document.getElementById('statRestaurants').textContent = new Intl.NumberFormat('vi-VN').format(stats.restaurants);
        document.getElementById('statOrders').textContent = new Intl.NumberFormat('vi-VN').format(stats.orders);
        document.getElementById('statRevenue').textContent = new Intl.NumberFormat('vi-VN', { maximumFractionDigits: 1 }).format(stats.revenue / 1000000) + 'M';
    }

    function loadDashboardData(initialData) {
        const monthlySales = initialData.monthly_sales;
        const orderStatuses = initialData.order_statuses;
        const topRestaurantsByOrders = initialData.top_restaurants_by_orders;

        const revenueData = buildConfig('line', {
            labels: monthlySales.map(item => {
                const monthNames = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
                return monthNames[item.month - 1] || `Tháng ${item.month}`;
            }),
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: monthlySales.map(item => item.total),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.4,
                fill: true
            }]
        }, {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: value => new Intl.NumberFormat('vi-VN').format(value) + 'đ' }
                }
            }
        });

        const statusData = buildConfig('doughnut', {
            labels: orderStatuses.map(item => {
                const statusLabels = {
                    pending: 'Chờ xác nhận',
                    confirmed: 'Đã xác nhận',
                    preparing: 'Đang chuẩn bị',
                    delivering: 'Đang giao',
                    delivered: 'Đã giao',
                    cancelled: 'Đã hủy'
                };
                return statusLabels[item.status] || item.status;
            }),
            datasets: [{
                data: orderStatuses.map(item => item.count),
                backgroundColor: ['#ffc107', '#17a2b8', '#fd7e14', '#007bff', '#28a745', '#dc3545'],
                borderWidth: 1
            }]
        }, {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 20, usePointStyle: true }
                }
            }
        });

        const restaurantsData = buildConfig('bar', {
            labels: topRestaurantsByOrders.map(r => r.name.length > 20 ? r.name.substring(0, 20) + '...' : r.name),
            datasets: [{
                label: 'Số đơn hàng',
                data: topRestaurantsByOrders.map(r => r.orders_count),
                backgroundColor: 'rgba(255, 193, 7, 0.8)',
                borderColor: 'rgba(255, 193, 7, 1)',
                borderWidth: 1
            }]
        }, {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        });

        renderChart('revenueChart', revenueData, 'Chưa có dữ liệu doanh thu');
        renderChart('ordersStatusChart', statusData, 'Chưa có đơn hàng để hiển thị trạng thái');
        renderChart('restaurantsChart', restaurantsData, 'Chưa có dữ liệu nhà hàng');
        updateStats(initialData.stats);
    }

    function fetchDashboardData() {
        fetch('{{ route('admin.dashboard.data') }}', { headers: { 'Accept': 'application/json' } })
            .then(response => response.json())
            .then(data => {
                loadDashboardData(data);
            })
            .catch(() => {
                console.warn('Không thể tải dữ liệu dashboard.');
            });
    }

    const initialData = {
        stats: @json($stats),
        monthly_sales: @json($monthlySales),
        order_statuses: @json($orderStatuses),
        top_restaurants_by_orders: @json($topRestaurantsByOrders)
    };

    loadDashboardData(initialData);
    setInterval(fetchDashboardData, 30000);
});
</script>
@endsection
