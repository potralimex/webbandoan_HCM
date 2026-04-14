<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ResDeli') - Đặt đồ ăn online</title>
    <meta name="description" content="@yield('description', 'ResDeli - Nền tảng đặt đồ ăn trực tuyến hàng đầu Việt Nam. Đặt món từ hàng nghìn nhà hàng, giao nhanh tận nơi.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --primary: #FF6B35;
            --primary-dark: #e55a26;
            --primary-light: #fff3ef;
            --secondary: #1a1a2e;
            --accent: #f7b731;
            --success: #20bf6b;
            --danger: #eb3b5a;
            --info: #2d98da;
            --warning: #f7b731;
            --bg: #f8f9fa;
            --card: #ffffff;
            --border: #e9ecef;
            --text: #212529;
            --text-muted: #6c757d;
            --radius: 12px;
            --radius-sm: 8px;
            --shadow: 0 2px 20px rgba(0,0,0,0.08);
            --shadow-hover: 0 8px 30px rgba(255,107,53,0.2);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }

        /* NAVBAR */
        .navbar {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 0 1rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }
        .navbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            height: 64px;
        }
        .navbar-brand {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .navbar-brand span { color: var(--secondary); }

        .navbar-search {
            flex: 1;
            max-width: 420px;
            position: relative;
        }
        .navbar-search input {
            width: 100%;
            padding: 0.6rem 1rem 0.6rem 2.8rem;
            border: 1.5px solid var(--border);
            border-radius: 50px;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s;
            background: var(--bg);
        }
        .navbar-search input:focus { border-color: var(--primary); background: #fff; }
        .navbar-search .search-icon {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }
        .search-dropdown {
            position: absolute;
            top: 110%;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow);
            z-index: 999;
            display: none;
            max-height: 400px;
            overflow-y: auto;
        }
        .search-dropdown.show { display: block; }
        .search-item {
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text);
            transition: background 0.15s;
        }
        .search-item:hover { background: var(--bg); }
        .search-item img { width: 40px; height: 40px; border-radius: 8px; object-fit: cover; }
        .search-section-title {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-top: 1px solid var(--border);
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            margin-left: auto;
        }
        .nav-link {
            padding: 0.5rem 0.75rem;
            border-radius: var(--radius-sm);
            text-decoration: none;
            color: var(--text);
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .nav-link:hover { background: var(--bg); color: var(--primary); }
        .nav-link.active { color: var(--primary); }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            padding: 0.55rem 1.2rem;
            border-radius: var(--radius-sm);
            border: none;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 15px rgba(255,107,53,0.3); }
        .btn-outline { background: transparent; border: 1.5px solid var(--border); color: var(--text); }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
        .btn-sm { padding: 0.4rem 0.9rem; font-size: 0.8rem; }
        .btn-lg { padding: 0.8rem 2rem; font-size: 1rem; }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #d63350; }
        .btn-success { background: var(--success); color: #fff; }
        .btn-warning { background: var(--warning); color: #fff; }
        .btn-info { background: var(--info); color: #fff; }
        .btn-secondary { background: #6c757d; color: #fff; }
        .btn-light { background: var(--bg); color: var(--text); border: 1px solid var(--border); }
        .btn-block { width: 100%; }

        /* AVATAR DROPDOWN */
        .user-menu { position: relative; }
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid var(--primary);
        }
        .user-dropdown {
            position: absolute;
            right: 0;
            top: 110%;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            min-width: 220px;
            display: none;
            z-index: 999;
            overflow: hidden;
        }
        .user-dropdown.show { display: block; animation: fadeIn 0.15s ease; }
        .user-dropdown-header {
            padding: 1rem;
            background: var(--primary-light);
            border-bottom: 1px solid var(--border);
        }
        .user-dropdown-header strong { display: block; font-weight: 600; }
        .user-dropdown-header span { font-size: 0.8rem; color: var(--text-muted); }
        .user-dropdown a, .user-dropdown button {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            text-decoration: none;
            color: var(--text);
            font-size: 0.875rem;
            transition: background 0.15s;
            background: none;
            border: none;
            width: 100%;
            cursor: pointer;
        }
        .user-dropdown a:hover, .user-dropdown button:hover { background: var(--bg); }
        .user-dropdown a i, .user-dropdown button i { width: 18px; color: var(--text-muted); }
        .user-dropdown .divider { height: 1px; background: var(--border); margin: 0.25rem 0; }

        /* CONTAINER */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
        .section { padding: 3rem 0; }

        /* CARDS */
        .card {
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: all 0.3s;
        }
        .card:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover); }
        .card-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card-body { padding: 1.25rem; }
        .card-title { font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem; }
        .card-text { color: var(--text-muted); font-size: 0.875rem; }

        /* GRID */
        .grid { display: grid; gap: 1.5rem; }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-4 { grid-template-columns: repeat(4, 1fr); }
        .grid-2 { grid-template-columns: repeat(2, 1fr); }
        @media (max-width: 900px) { .grid-3, .grid-4 { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 600px) { .grid-3, .grid-4, .grid-2 { grid-template-columns: 1fr; } }

        /* BADGE */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.25rem 0.65rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-primary { background: var(--primary-light); color: var(--primary); }
        .badge-success { background: #d4efde; color: #1a7a42; }
        .badge-warning { background: #fef5d9; color: #b78000; }
        .badge-danger { background: #fde8ec; color: #c0392b; }
        .badge-info { background: #daeefa; color: #1a6b8a; }
        .badge-secondary { background: #e9ecef; color: #495057; }

        /* ALERTS */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            font-size: 0.9rem;
        }
        .alert-success { background: #d4efde; color: #1a7a42; border-left: 4px solid #20bf6b; }
        .alert-danger  { background: #fde8ec; color: #c0392b; border-left: 4px solid #eb3b5a; }
        .alert-warning { background: #fef5d9; color: #b78000; border-left: 4px solid #f7b731; }
        .alert-info    { background: #daeefa; color: #1a6b8a; border-left: 4px solid #2d98da; }
        .alert i { margin-top: 0.1rem; }

        /* FORM */
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.4rem; color: var(--text); }
        .form-control {
            width: 100%;
            padding: 0.65rem 0.9rem;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fff;
            color: var(--text);
        }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(255,107,53,0.12); }
        .form-control.is-invalid { border-color: var(--danger); }
        .invalid-feedback { color: var(--danger); font-size: 0.8rem; margin-top: 0.3rem; }
        textarea.form-control { resize: vertical; min-height: 100px; }
        select.form-control { cursor: pointer; }
        .form-check { display: flex; align-items: center; gap: 0.5rem; }
        .form-check-input { width: 18px; height: 18px; cursor: pointer; accent-color: var(--primary); }

        /* STAR RATING */
        .stars { color: #f7b731; }
        .stars .empty { color: #dee2e6; }

        /* PAGINATION */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 2rem 0;
            list-style: none;
        }
        .pagination .page-link {
            padding: 0.5rem 0.9rem;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            text-decoration: none;
            color: var(--text);
            font-size: 0.875rem;
            transition: all 0.2s;
            background: #fff;
        }
        .pagination .page-link:hover { border-color: var(--primary); color: var(--primary); }
        .pagination .active .page-link { background: var(--primary); border-color: var(--primary); color: #fff; }
        .pagination .disabled .page-link { opacity: 0.5; pointer-events: none; }

        /* FOOTER */
        .footer {
            background: var(--secondary);
            color: #adb5bd;
            padding: 3rem 0 1.5rem;
            margin-top: 4rem;
        }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 2rem; }
        .footer-brand { font-size: 1.5rem; font-weight: 800; color: var(--primary); margin-bottom: 0.75rem; }
        .footer h5 { color: #fff; font-size: 0.9rem; font-weight: 700; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .footer ul { list-style: none; }
        .footer ul li { margin-bottom: 0.5rem; }
        .footer ul li a { color: #adb5bd; text-decoration: none; font-size: 0.875rem; transition: color 0.2s; }
        .footer ul li a:hover { color: var(--primary); }
        .footer-bottom { border-top: 1px solid #2d3748; margin-top: 2rem; padding-top: 1.5rem; text-align: center; font-size: 0.8rem; }
        @media (max-width: 900px) { .footer-grid { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 600px) { .footer-grid { grid-template-columns: 1fr; } }

        /* SKELETON LOADING */
        .skeleton { background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: skeleton 1.5s infinite; }
        @keyframes skeleton { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

        /* ANIMATIONS */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fadeIn { animation: fadeIn 0.3s ease; }
        .animate-slideUp { animation: slideUp 0.4s ease; }

        /* MISC */
        .text-primary { color: var(--primary); }
        .text-muted { color: var(--text-muted); }
        .text-success { color: var(--success); }
        .text-danger { color: var(--danger); }
        .fw-bold { font-weight: 700; }
        .fw-semibold { font-weight: 600; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .mt-1 { margin-top: 0.5rem; } .mt-2 { margin-top: 1rem; } .mt-3 { margin-top: 1.5rem; } .mt-4 { margin-top: 2rem; }
        .mb-1 { margin-bottom: 0.5rem; } .mb-2 { margin-bottom: 1rem; } .mb-3 { margin-bottom: 1.5rem; } .mb-4 { margin-bottom: 2rem; }
        .d-flex { display: flex; }
        .align-items-center { align-items: center; }
        .justify-content-between { justify-content: space-between; }
        .gap-1 { gap: 0.5rem; } .gap-2 { gap: 1rem; }
        .w-100 { width: 100%; }
        .img-fluid { max-width: 100%; height: auto; }
        .rounded { border-radius: var(--radius-sm); }
        .rounded-circle { border-radius: 50%; }
        hr { border: none; border-top: 1px solid var(--border); margin: 1.5rem 0; }

        /* TABLE */
        .table { width: 100%; border-collapse: collapse; }
        .table th { background: var(--bg); padding: 0.75rem 1rem; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); text-align: left; border-bottom: 2px solid var(--border); }
        .table td { padding: 0.9rem 1rem; border-bottom: 1px solid var(--border); font-size: 0.875rem; vertical-align: middle; }
        .table tr:hover td { background: var(--bg); }
        .table-responsive { overflow-x: auto; }

        /* TOAST */
        .toast-container {
            position: fixed;
            top: 80px;
            right: 1.5rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .toast {
            background: #fff;
            border-radius: var(--radius-sm);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 280px;
            max-width: 380px;
            animation: slideUp 0.3s ease;
            border-left: 4px solid var(--success);
            font-size: 0.875rem;
        }
        .toast.toast-error { border-left-color: var(--danger); }
        .toast-icon { font-size: 1.1rem; flex-shrink: 0; }
        .toast-close { margin-left: auto; cursor: pointer; color: var(--text-muted); background: none; border: none; font-size: 1rem; padding: 0; }
    </style>

    @yield('styles')
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="navbar-inner">
        <a href="{{ route('home') }}" class="navbar-brand">
            🍜 Res<span>Deli</span>
        </a>

        <div class="navbar-search">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="globalSearch" placeholder="Tìm nhà hàng, món ăn..." autocomplete="off">
            <div class="search-dropdown" id="searchDropdown"></div>
        </div>

        <nav class="navbar-nav">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Trang chủ
            </a>

            @auth
                <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i> Đơn hàng
                </a>

                <div class="user-menu">
                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="user-avatar" id="userAvatarBtn">
                    <div class="user-dropdown" id="userDropdown">
                        <div class="user-dropdown-header">
                            <strong>{{ Auth::user()->name }}</strong>
                            <span>{{ Auth::user()->email }}</span>
                        </div>
                        <a href="{{ route('profile.show') }}"><i class="fas fa-user"></i> Hồ sơ của tôi</a>
                        <a href="{{ route('orders.index') }}"><i class="fas fa-receipt"></i> Đơn hàng</a>
                        @if(Auth::user()->isAdmin())
                            <div class="divider"></div>
                            <a href="{{ route('admin.dashboard') }}"><i class="fas fa-cog"></i> Quản trị</a>
                        @endif
                        <div class="divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"><i class="fas fa-sign-out-alt"></i> Đăng xuất</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Đăng nhập</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Đăng ký</a>
            @endauth
        </nav>
    </div>
</nav>

<!-- FLASH MESSAGES -->
<div class="toast-container" id="toastContainer">
    @if(session('success'))
        <div class="toast" id="toast-success">
            <i class="fas fa-check-circle toast-icon text-success"></i>
            <span>{{ session('success') }}</span>
            <button class="toast-close" onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif
    @if(session('error'))
        <div class="toast toast-error" id="toast-error">
            <i class="fas fa-exclamation-circle toast-icon text-danger"></i>
            <span>{{ session('error') }}</span>
            <button class="toast-close" onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif
</div>

@yield('content')

<!-- FOOTER -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="footer-brand">🍜 ResDeli</div>
                <p style="font-size:0.875rem; line-height:1.8;">Nền tảng đặt đồ ăn trực tuyến hàng đầu Việt Nam. Kết nối bạn với hàng trăm nhà hàng chất lượng, giao nhanh tận nơi.</p>
                <div style="margin-top:1rem; display:flex; gap:0.75rem;">
                    <a href="#" style="color:#adb5bd; font-size:1.2rem;"><i class="fab fa-facebook"></i></a>
                    <a href="#" style="color:#adb5bd; font-size:1.2rem;"><i class="fab fa-instagram"></i></a>
                    <a href="#" style="color:#adb5bd; font-size:1.2rem;"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
            <div>
                <h5>Khám phá</h5>
                <ul>
                    <li><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li><a href="{{ route('home') }}?sort=rating">Đánh giá cao</a></li>
                    <li><a href="{{ route('home') }}?sort=delivery_fee">Giao nhanh</a></li>
                    <li><a href="{{ route('home') }}?sort=newest">Mới nhất</a></li>
                </ul>
            </div>
            <div>
                <h5>Tài khoản</h5>
                <ul>
                    @auth
                        <li><a href="{{ route('profile.show') }}">Hồ sơ</a></li>
                        <li><a href="{{ route('orders.index') }}">Đơn hàng</a></li>
                    @else
                        <li><a href="{{ route('login') }}">Đăng nhập</a></li>
                        <li><a href="{{ route('register') }}">Đăng ký</a></li>
                    @endauth
                </ul>
            </div>
            <div>
                <h5>Liên hệ</h5>
                <ul>
                    <li><a href="tel:19001234"><i class="fas fa-phone" style="width:16px;"></i> 1900 1234</a></li>
                    <li><a href="mailto:hello@resdeli.com"><i class="fas fa-envelope" style="width:16px;"></i> hello@resdeli.com</a></li>
                    <li><a href="#"><i class="fas fa-map-marker-alt" style="width:16px;"></i> TP. Hồ Chí Minh</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© {{ date('Y') }} ResDeli. Bảo lưu mọi quyền. Được xây dựng bằng ❤️ và Laravel.</p>
        </div>
    </div>
</footer>

<script>
// User dropdown toggle
const avatarBtn = document.getElementById('userAvatarBtn');
const userDropdown = document.getElementById('userDropdown');
if (avatarBtn) {
    avatarBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        userDropdown.classList.toggle('show');
    });
}
document.addEventListener('click', () => {
    if (userDropdown) userDropdown.classList.remove('show');
});

// Toast auto-dismiss
setTimeout(() => {
    document.querySelectorAll('.toast').forEach(t => t.remove());
}, 5000);

// Global search
const globalSearch = document.getElementById('globalSearch');
const searchDropdown = document.getElementById('searchDropdown');
let searchTimeout;

if (globalSearch) {
    globalSearch.addEventListener('input', function() {
        const q = this.value.trim();
        clearTimeout(searchTimeout);

        if (q.length < 2) {
            searchDropdown.classList.remove('show');
            return;
        }

        searchTimeout = setTimeout(async () => {
            try {
                const res = await fetch(`/api/v1/search?q=${encodeURIComponent(q)}`);
                const data = await res.json();

                let html = '';

                if (data.restaurants && data.restaurants.length > 0) {
                    html += '<div class="search-section-title"><i class="fas fa-store"></i> Nhà hàng</div>';
                    data.restaurants.forEach(r => {
                        html += `<a href="/restaurants/${r.slug}" class="search-item">
                            <img src="${r.image_url}" alt="${r.name}" onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=80&h=80&fit=crop'">
                            <div>
                                <div style="font-weight:600;">${r.name}</div>
                                <div style="font-size:0.78rem;color:#6c757d;">${r.city} · ⭐ ${r.rating}</div>
                            </div>
                        </a>`;
                    });
                }

                if (data.items && data.items.length > 0) {
                    html += '<div class="search-section-title"><i class="fas fa-utensils"></i> Món ăn</div>';
                    data.items.forEach(i => {
                        html += `<a href="/restaurants/${i.restaurant_slug}" class="search-item">
                            <img src="${i.image_url}" alt="${i.name}" onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=80&h=80&fit=crop'">
                            <div>
                                <div style="font-weight:600;">${i.name}</div>
                                <div style="font-size:0.78rem;color:#6c757d;">${i.restaurant_name} · ${Number(i.effective_price).toLocaleString('vi-VN')}đ</div>
                            </div>
                        </a>`;
                    });
                }

                if (!html) {
                    html = '<div class="search-item" style="color:#6c757d;">Không tìm thấy kết quả phù hợp</div>';
                }

                searchDropdown.innerHTML = html;
                searchDropdown.classList.add('show');
            } catch (e) {
                console.error(e);
            }
        }, 350);
    });

    document.addEventListener('click', (e) => {
        if (!globalSearch.contains(e.target) && !searchDropdown.contains(e.target)) {
            searchDropdown.classList.remove('show');
        }
    });
}
</script>

@yield('scripts')
</body>
</html>
