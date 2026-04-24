@extends('layouts.admin')

@section('admin-content')

<div class="container-fluid">

    <h3 class="mb-4">📊 Dashboard Management</h3>

    <!-- ===== FILTER ===== -->
    <div class="mb-3 d-flex gap-2 flex-wrap">
        <input type="date" id="fromDate" class="form-control w-auto">
        <input type="date" id="toDate" class="form-control w-auto">

        <select id="statusFilter" class="form-control w-auto">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
        </select>

        <input type="text" id="searchInput" placeholder="🔍 Search order..." class="form-control w-auto">

        <button onclick="fetchDashboard()" class="btn btn-primary">Apply</button>
    </div>

    <!-- ===== STATS ===== -->
    <div class="row mb-4 text-center">
        <div class="col-md-3">
            <div class="card p-3 shadow">
                <h6>Total Orders</h6>
                <h4 id="stat-orders">0</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow">
                <h6>Total Revenue</h6>
                <h4 id="stat-revenue">0</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow">
                <h6>Pending</h6>
                <h4 id="stat-pending">0</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow">
                <h6>Delivered</h6>
                <h4 id="stat-delivered">0</h4>
            </div>
        </div>
    </div>

    <!-- ===== TABLE ===== -->
    <div class="card shadow p-3">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th onclick="sort('order_number')" style="cursor:pointer">Mã ⬍</th>
                    <th>Khách</th>
                    <th>Nhà hàng</th>
                    <th onclick="sort('status')" style="cursor:pointer">Status ⬍</th>
                    <th onclick="sort('total')" style="cursor:pointer">Total ⬍</th>
                    <th onclick="sort('created_at')" style="cursor:pointer">Date ⬍</th>
                </tr>
            </thead>
            <tbody id="orders"></tbody>
        </table>
    </div>

    <!-- ===== PAGINATION ===== -->
    <div id="pagination" class="mt-3"></div>

</div>

<!-- ===== STYLE ===== -->
<style>
.card {
    border-radius: 10px;
}
th:hover {
    background: #f1f1f1;
}
</style>

<!-- ===== SCRIPT ===== -->
<script>
let currentPage = 1;
let currentSort = 'created_at';
let currentDirection = 'desc';

function buildUrl() {
    let from = document.getElementById("fromDate").value;
    let to = document.getElementById("toDate").value;
    let status = document.getElementById("statusFilter").value;
    let search = document.getElementById("searchInput").value;

    return `/api/v1/dashboard?page=${currentPage}&sort=${currentSort}&direction=${currentDirection}&from=${from}&to=${to}&status=${status}&search=${search}`;
}

function fetchDashboard() {
    fetch(buildUrl())
        .then(res => res.json())
        .then(data => {
            renderStats(data.stats);
            renderOrders(data.orders);
            renderPagination(data.pagination);
        });
}

function renderStats(stats) {
    document.getElementById("stat-orders").innerText = stats.total_orders;
    document.getElementById("stat-revenue").innerText = stats.total_revenue;
    document.getElementById("stat-pending").innerText = stats.pending;
    document.getElementById("stat-delivered").innerText = stats.delivered;
}

function renderOrders(orders) {
    let html = orders.map(o => `
        <tr>
            <td>${o.order_number}</td>
            <td>${o.customer_name}</td>
            <td>${o.restaurant_name}</td>
            <td>${o.status_badge}</td>
            <td>${o.total}</td>
            <td>${o.created_at}</td>
        </tr>
    `).join('');

    document.getElementById("orders").innerHTML = html;
}

function renderPagination(p) {
    let html = '';

    for (let i = 1; i <= p.last_page; i++) {
        html += `<button onclick="goPage(${i})" class="btn btn-sm ${i==p.current_page?'btn-primary':'btn-light'}">${i}</button> `;
    }

    document.getElementById("pagination").innerHTML = html;
}

function goPage(page) {
    currentPage = page;
    fetchDashboard();
}

function sort(field) {
    if (currentSort === field) {
        currentDirection = currentDirection === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort = field;
        currentDirection = 'asc';
    }
    fetchDashboard();
}

// SEARCH AUTO
document.getElementById("searchInput").addEventListener("keyup", function () {
    currentPage = 1;
    fetchDashboard();
});

// LOAD
fetchDashboard();
</script>

@endsection