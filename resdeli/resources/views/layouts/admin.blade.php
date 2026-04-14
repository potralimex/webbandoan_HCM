<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - @yield('title', 'Dashboard') | ResDeli</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #FF6B35; --primary-dark: #e55a26; --primary-light: #fff3ef;
            --sidebar-bg: #1a1a2e; --sidebar-text: #a0aec0; --sidebar-active: #FF6B35;
            --bg: #f8f9fa; --card: #fff; --border: #e9ecef;
            --text: #212529; --text-muted: #6c757d;
            --success: #20bf6b; --danger: #eb3b5a; --warning: #f7b731; --info: #2d98da;
            --radius: 12px; --radius-sm: 8px; --shadow: 0 2px 20px rgba(0,0,0,0.08);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:var(--bg); color:var(--text); display:flex; min-height:100vh; }

        /* SIDEBAR */
        .admin-sidebar {
            width: 240px;
            background: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            text-decoration: none;
        }
        .sidebar-brand-title { font-size: 1.4rem; font-weight: 800; color: var(--primary); }
        .sidebar-brand-sub { font-size: 0.7rem; color: var(--sidebar-text); letter-spacing: 0.5px; text-transform: uppercase; }
        .sidebar-nav { flex: 1; padding: 1rem 0; }
        .sidebar-section { padding: 0.5rem 1.25rem 0.25rem; font-size: 0.68rem; font-weight: 700; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 1px; }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 1.25rem;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            margin: 0.1rem 0;
        }
        .sidebar-link:hover { color: #fff; background: rgba(255,255,255,0.06); }
        .sidebar-link.active { color: var(--primary); background: rgba(255,107,53,0.1); border-left-color: var(--primary); }
        .sidebar-link i { width: 20px; text-align: center; font-size: 0.95rem; }
        .sidebar-user {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .sidebar-user img { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary); }
        .sidebar-user span { font-size: 0.8rem; color: var(--sidebar-text); }
        .sidebar-user strong { display: block; color: #fff; font-size: 0.85rem; }

        /* MAIN CONTENT */
        .admin-main { margin-left: 240px; flex: 1; min-width: 0; }
        .admin-header {
            background: #fff;
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 900;
        }
        .admin-title { font-size: 1.2rem; font-weight: 800; }
        .admin-content { padding: 2rem; }

        /* STATS CARDS */
        .stat-card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .stat-icon {
            width: 56px; height: 56px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }
        .stat-label { font-size: 0.78rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem; }
        .stat-value { font-size: 1.75rem; font-weight: 800; line-height: 1; }

        /* COMMON */
        .card { background: var(--card); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
        .card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .card-header h3 { font-size: 0.95rem; font-weight: 800; margin: 0; }
        .card-body { padding: 1.5rem; }
        .table { width: 100%; border-collapse: collapse; }
        .table th { background: var(--bg); padding: 0.75rem 1rem; font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); text-align: left; border-bottom: 2px solid var(--border); }
        .table td { padding: 0.9rem 1rem; border-bottom: 1px solid var(--border); font-size: 0.875rem; vertical-align: middle; }
        .table tr:hover td { background: var(--bg); }
        .table-responsive { overflow-x: auto; }
        .btn { display:inline-flex; align-items:center; justify-content:center; gap:0.4rem; padding:0.5rem 1rem; border-radius:var(--radius-sm); border:none; font-size:0.8rem; font-weight:600; cursor:pointer; text-decoration:none; transition:all 0.2s; }
        .btn-primary { background:var(--primary); color:#fff; } .btn-primary:hover { background:var(--primary-dark); }
        .btn-danger { background:var(--danger); color:#fff; } .btn-danger:hover { background:#d63350; }
        .btn-success { background:var(--success); color:#fff; }
        .btn-warning { background:var(--warning); color:#fff; }
        .btn-info { background:var(--info); color:#fff; }
        .btn-light { background:var(--bg); color:var(--text); border:1px solid var(--border); }
        .btn-sm { padding:0.35rem 0.75rem; font-size:0.78rem; }
        .btn-block { width:100%; }
        .badge { display:inline-flex; align-items:center; gap:0.25rem; padding:0.25rem 0.6rem; border-radius:50px; font-size:0.72rem; font-weight:700; }
        .badge-primary { background:var(--primary-light); color:var(--primary); }
        .badge-success { background:#d4efde; color:#1a7a42; }
        .badge-warning { background:#fef5d9; color:#b78000; }
        .badge-danger { background:#fde8ec; color:#c0392b; }
        .badge-info { background:#daeefa; color:#1a6b8a; }
        .badge-secondary { background:#e9ecef; color:#495057; }
        .badge-purple { background:#f3eeff; color:#6f42c1; }
        .bg-purple { background: #6f42c1; }
        .alert { padding:0.9rem 1.25rem; border-radius:var(--radius-sm); margin-bottom:1.25rem; display:flex; align-items:flex-start; gap:0.75rem; font-size:0.875rem; }
        .alert-success { background:#d4efde; color:#1a7a42; border-left:4px solid #20bf6b; }
        .alert-danger { background:#fde8ec; color:#c0392b; border-left:4px solid #eb3b5a; }
        .form-group { margin-bottom:1.25rem; }
        .form-label { display:block; font-size:0.8rem; font-weight:700; margin-bottom:0.4rem; text-transform:uppercase; letter-spacing:0.4px; }
        .form-control { width:100%; padding:0.65rem 0.9rem; border:1.5px solid var(--border); border-radius:var(--radius-sm); font-size:0.875rem; font-family:inherit; outline:none; transition:border-color 0.2s; background:#fff; color:var(--text); }
        .form-control:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(255,107,53,0.12); }
        .form-control.is-invalid { border-color:var(--danger); }
        .invalid-feedback { color:var(--danger); font-size:0.78rem; margin-top:0.3rem; }
        .form-check { display:flex; align-items:center; gap:0.5rem; cursor:pointer; }
        .form-check-input { width:18px; height:18px; accent-color:var(--primary); cursor:pointer; }
        textarea.form-control { resize:vertical; min-height:100px; }
        select.form-control { cursor:pointer; }
        .d-flex { display:flex; }
        .align-items-center { align-items:center; }
        .justify-content-between { justify-content:space-between; }
        .gap-1 { gap:0.5rem; } .gap-2 { gap:1rem; }
        .mt-1 { margin-top:0.5rem; } .mt-2 { margin-top:1rem; } .mt-3 { margin-top:1.5rem; }
        .mb-1 { margin-bottom:0.5rem; } .mb-2 { margin-bottom:1rem; } .mb-3 { margin-bottom:1.5rem; } .mb-4 { margin-bottom:2rem; }
        .text-muted { color:var(--text-muted); }
        .text-primary { color:var(--primary); }
        .text-center { text-align:center; }
        .fw-bold { font-weight:700; }
        hr { border:none; border-top:1px solid var(--border); margin:1.5rem 0; }
        .grid { display:grid; gap:1.5rem; }
        .grid-4 { grid-template-columns:repeat(4,1fr); }
        .grid-2 { grid-template-columns:repeat(2,1fr); }
        @media(max-width:1200px) { .grid-4 { grid-template-columns:repeat(2,1fr); } }
        .toast-container { position:fixed; top:80px; right:1.5rem; z-index:9999; display:flex; flex-direction:column; gap:0.75rem; }
        .toast { background:#fff; border-radius:var(--radius-sm); box-shadow:0 4px 20px rgba(0,0,0,0.15); padding:1rem 1.25rem; display:flex; align-items:center; gap:0.75rem; min-width:280px; font-size:0.875rem; border-left:4px solid var(--success); }
        .toast.toast-error { border-left-color:var(--danger); }
        .toast-close { margin-left:auto; cursor:pointer; color:var(--text-muted); background:none; border:none; font-size:1rem; padding:0; }
        @keyframes fadeIn { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }
        .pagination { display:flex; align-items:center; justify-content:center; gap:0.4rem; padding:1.5rem 0; list-style:none; }
        .pagination .page-link { padding:0.45rem 0.8rem; border:1.5px solid var(--border); border-radius:var(--radius-sm); text-decoration:none; color:var(--text); font-size:0.8rem; transition:all 0.2s; background:#fff; }
        .pagination .page-link:hover { border-color:var(--primary); color:var(--primary); }
        .pagination .active .page-link { background:var(--primary); border-color:var(--primary); color:#fff; }
        .pagination .disabled .page-link { opacity:0.5; pointer-events:none; }
        .img-thumbnail { width:50px; height:50px; object-fit:cover; border-radius:var(--radius-sm); }
    </style>
    @yield('styles')
</head>
<body>
<!-- SIDEBAR -->
<aside class="admin-sidebar">
    <a href="{{ route('home') }}" class="sidebar-brand">
        <div class="sidebar-brand-title">🍜 ResDeli</div>
        <div class="sidebar-brand-sub">Admin Panel</div>
    </a>
    <nav class="sidebar-nav">
        <div class="sidebar-section">Tổng quan</div>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> Dashboard
        </a>

        <div class="sidebar-section">Quản lý</div>
        <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Người dùng
        </a>
        <a href="{{ route('admin.restaurants.index') }}" class="sidebar-link {{ request()->routeIs('admin.restaurants*') ? 'active' : '' }}">
            <i class="fas fa-store"></i> Nhà hàng
        </a>
        <a href="{{ route('admin.menu-items.index') }}" class="sidebar-link {{ request()->routeIs('admin.menu-items*') ? 'active' : '' }}">
            <i class="fas fa-utensils"></i> Thực đơn
        </a>
        <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
            <i class="fas fa-receipt"></i> Đơn hàng
        </a>
        <a href="{{ route('admin.reviews.index') }}" class="sidebar-link {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
            <i class="fas fa-star"></i> Đánh giá
        </a>
        <a href="{{ route('admin.categories') }}" class="sidebar-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i> Danh mục
        </a>
    </nav>
    <div class="sidebar-user">
        <img src="{{ Auth::user()->avatar_url }}" alt="">
        <div>
            <strong>{{ Auth::user()->name }}</strong>
            <span>Admin</span>
        </div>
    </div>
</aside>

<!-- MAIN -->
<main class="admin-main">
    <header class="admin-header">
        <div>
            <h1 class="admin-title">@yield('title', 'Dashboard')</h1>
            <nav style="font-size:0.8rem; color:var(--text-muted);">
                <a href="{{ route('admin.dashboard') }}" style="color:var(--text-muted); text-decoration:none;">Admin</a>
                @hasSection('breadcrumb')
                <span> / </span> @yield('breadcrumb')
                @endif
            </nav>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('home') }}" class="btn btn-light btn-sm" target="_blank"><i class="fas fa-external-link-alt"></i> Xem trang web</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-light btn-sm"><i class="fas fa-sign-out-alt"></i> Đăng xuất</button>
            </form>
        </div>
    </header>

    <!-- Toast -->
    <div class="toast-container">
        @if(session('success'))
        <div class="toast">
            <i class="fas fa-check-circle" style="color:var(--success);"></i>
            <span>{{ session('success') }}</span>
            <button class="toast-close" onclick="this.parentElement.remove()">✕</button>
        </div>
        @endif
        @if(session('error'))
        <div class="toast toast-error">
            <i class="fas fa-exclamation-circle" style="color:var(--danger);"></i>
            <span>{{ session('error') }}</span>
            <button class="toast-close" onclick="this.parentElement.remove()">✕</button>
        </div>
        @endif
    </div>

    <div class="admin-content">
        @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
        </div>
        @endif

        @yield('content')
    </div>
</main>

<script>
setTimeout(() => document.querySelectorAll('.toast').forEach(t => t.remove()), 5000);

// Confirm delete
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => {
        if (!confirm(el.dataset.confirm)) e.preventDefault();
    });
});
</script>

@yield('scripts')
</body>
</html>
