<div align="center">

# 🍜 ResDeli — Hệ Thống Đặt Món Ăn Trực Tuyến

[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![GitHub](https://img.shields.io/badge/GitHub-potralimex-181717?style=for-the-badge&logo=github)](https://github.com/potralimex/webbandoan_HCM)

**ResDeli** là ứng dụng web đặt món ăn trực tuyến xây dựng trên **Laravel 12 + MySQL**, áp dụng kiến trúc MVC, hỗ trợ đầy đủ luồng từ khám phá nhà hàng → đặt món → thanh toán QR → theo dõi đơn hàng → quản trị hệ thống.

</div>

---

## Mục Lục

1. [Phân tích hệ thống & yêu cầu](#1-phân-tích-hệ-thống--yêu-cầu)
2. [Bài làm đã đạt được](#2-bài-làm-đã-đạt-được)
3. [Thiếu sót & hướng phát triển](#3-thiếu-sót--hướng-phát-triển)
4. [Hướng dẫn cài đặt](#4-hướng-dẫn-cài-đặt)
5. [Cấu trúc thư mục](#5-cấu-trúc-thư-mục)
6. [Cấu hình thanh toán QR](#6-cấu-hình-thanh-toán-qr)
7. [Cấu hình email](#7-cấu-hình-email)
8. [API Reference](#8-api-reference)

---

## 1. Phân Tích Hệ Thống & Yêu Cầu

### 1.1 Bối cảnh bài toán

Nhu cầu đặt món ăn trực tuyến ngày càng tăng, nhưng nhiều nhà hàng nhỏ chưa có nền tảng quản lý đơn hàng hiệu quả. ResDeli giải quyết bài toán kết nối khách hàng với nhà hàng, tự động hóa quy trình đặt món và cung cấp công cụ quản trị toàn diện.

### 1.2 Các tác nhân (Actor)

| Actor | Mô tả |
|-------|-------|
| **Guest** | Xem nhà hàng, thực đơn, tìm kiếm — không cần đăng nhập |
| **Customer** | Đặt món, thanh toán QR, theo dõi đơn hàng, đánh giá, yêu thích |
| **Admin** | Toàn quyền quản trị: users, nhà hàng, món ăn, đơn hàng, xuất Excel |

### 1.3 Yêu cầu chức năng

| Mã | Chức năng | Mức độ |
|----|-----------|--------|
| FR01 | Đăng ký / Đăng nhập / Đăng xuất | Bắt buộc |
| FR02 | Quản lý hồ sơ cá nhân, đổi mật khẩu, avatar | Bắt buộc |
| FR03 | Xem danh sách nhà hàng, lọc, sắp xếp | Bắt buộc |
| FR04 | Xem thực đơn phân nhóm theo danh mục | Bắt buộc |
| FR05 | Giỏ hàng session: thêm/xóa/sửa số lượng AJAX | Bắt buộc |
| FR06 | Đặt hàng: địa chỉ, SĐT, ghi chú, thanh toán | Bắt buộc |
| FR07 | Thanh toán QR: VietQR, MoMo, ZaloPay | Bắt buộc |
| FR08 | Theo dõi trạng thái đơn hàng theo pipeline | Bắt buộc |
| FR09 | Hủy đơn hàng (chỉ khi pending) | Bắt buộc |
| FR10 | Email thông báo admin khi có đơn mới | Quan trọng |
| FR11 | Đánh giá nhà hàng sau khi nhận hàng | Quan trọng |
| FR12 | Yêu thích nhà hàng (AJAX, lưu DB) | Quan trọng |
| FR13 | Admin CRUD: danh mục, nhà hàng, món ăn, đơn hàng | Bắt buộc |
| FR14 | Xuất báo cáo đơn hàng ra file Excel (.xlsx) | Quan trọng |
| FR15 | Dashboard thống kê: doanh thu, biểu đồ Chart.js | Quan trọng |
| FR16 | REST API: tìm kiếm, thực đơn, danh mục | Quan trọng |

### 1.4 Kiến trúc MVC

```
Browser Request
    │
    ▼
routes/web.php          ← Định tuyến, phân nhóm middleware
    │
    ▼
Middleware (auth/admin) ← Kiểm tra đăng nhập, phân quyền
    │
    ▼
Controller              ← Nhận request, gọi Model, trả View/JSON
    │
    ├── Model (Eloquent) ← Query DB, quan hệ, accessor, mutator
    │       │
    │       ▼
    │   MySQL Database
    │
    └── View (Blade)    ← Render HTML, nhận $data từ Controller
            │
            ▼
        Response → Browser
```

### 1.5 Thiết kế cơ sở dữ liệu (11 bảng)

```
users ──(1:1)── profiles
users ──(1:N)── orders ──(1:N)── order_items ──(N:1)── menu_items
users ──(1:N)── reviews
users ──(N:N)── restaurants  [via favorites]
restaurants ──(1:N)── menu_items ──(N:N)── tags  [via menu_item_tag]
restaurants ──(1:N)── orders
restaurants ──(1:N)── reviews
menu_items ──(N:1)── categories
orders ──(1:1)── reviews
```

---

## 2. Bài Làm Đã Đạt Được

### ✅ Xác thực & Phân quyền
- Đăng ký, đăng nhập, đăng xuất
- Phân quyền 2 role: `admin` / `customer`
- Middleware `auth` + `AdminMiddleware` bảo vệ routes
- CSRF protection trên 100% form
- Kiểm tra ownership đơn hàng trước khi xem

### ✅ Giỏ hàng (Session-based)
- Lưu trong **PHP Session** — bền vững qua reload trang
- Thêm món từ trang nhà hàng qua **AJAX** → badge navbar cập nhật ngay
- Sidebar cart tự động seed từ session khi load trang nhà hàng
- Trang `/cart`: tăng/giảm số lượng, xóa từng món, xóa tất cả — toàn bộ AJAX
- Tính tổng tiền real-time

### ✅ Thanh toán QR (3 phương thức)

| Phương thức | Công nghệ | Mô tả |
|-------------|-----------|-------|
| 🏦 Chuyển khoản | **VietQR API** | QR thực — quét được bằng mọi app ngân hàng VN |
| 📱 MoMo | **QR Server API** | QR deeplink mở thẳng app MoMo |
| 💙 ZaloPay | **QR Server API** | QR deeplink mở thẳng app ZaloPay |
| 💵 COD | — | Tiền mặt khi nhận hàng |

QR hiện ngay khi chọn phương thức, tự động điền số tiền và tên khách hàng.

### ✅ Email thông báo Admin
- Khi user đặt hàng thành công → **tự động gửi email** đến admin
- Template HTML đẹp: bảng món ăn, tổng tiền, thông tin giao hàng, nút CTA
- Dùng **Gmail SMTP** (App Password)
- Lỗi mail không ảnh hưởng luồng đặt hàng (try/catch + Log)

### ✅ Xuất Excel báo cáo
- Package **maatwebsite/excel 3.1**
- Xuất danh sách đơn hàng với 10 cột đầy đủ
- Lọc theo khoảng ngày (from_date / to_date)
- Header màu cam #FF6B35, auto-width cột
- Chỉ Admin mới có quyền export

### ✅ Admin Dashboard
- 4 thẻ KPI với % so sánh tháng trước
- Biểu đồ doanh thu 12 tháng (Chart.js line)
- Biểu đồ trạng thái đơn hàng (doughnut)
- Biểu đồ phương thức thanh toán (bar)
- Top 5 nhà hàng doanh thu cao nhất
- 8 đơn hàng mới nhất

### ✅ Admin CRUD đầy đủ

| Module | Chức năng |
|--------|-----------|
| Users | Xem, tìm kiếm, lọc role, khóa/mở tài khoản |
| Categories | Tạo, sửa, xóa danh mục (icon emoji) |
| Restaurants | CRUD + upload ảnh + giờ mở cửa + phí ship |
| Menu Items | CRUD + upload ảnh + tags + giá khuyến mãi |
| Orders | Xem tất cả, lọc trạng thái, cập nhật, xuất Excel |
| Reviews | Duyệt, xóa — tự động cập nhật rating nhà hàng |

### ✅ REST API

```
GET /api/v1/restaurants          → Danh sách + filter + sort + paginate
GET /api/v1/restaurants/{slug}/menu → Thực đơn nhóm theo danh mục
GET /api/v1/categories           → Danh sách danh mục
GET /api/v1/search?q=...         → Tìm đồng thời nhà hàng + món ăn
```

### ✅ UX & Tính năng khác
- Tìm kiếm real-time: debounce 350ms → dropdown kết quả
- Toggle yêu thích nhà hàng bằng AJAX
- Toast notification khi thêm vào giỏ hàng
- Progress bar 5 bước theo dõi đơn hàng
- Ảnh món ăn đúng với từng loại (Unsplash)
- Responsive layout mobile/tablet/desktop

---

## 3. Thiếu Sót & Hướng Phát Triển

### ⚠️ Thiếu sót cần lưu ý

| Vấn đề | Mức độ |
|--------|--------|
| Giỏ hàng chưa kiểm tra nhà hàng — có thể thêm món từ nhiều nhà hàng | Cao |
| Thanh toán MoMo/ZaloPay là mock (deeplink), chưa tích hợp API thực | Cao |
| Chưa có rate limiting cho `/login`, `/register` | Trung bình |
| Chưa xác thực email khi đăng ký | Trung bình |
| API `/api/v1/*` public, chưa có Sanctum auth | Trung bình |
| `delivery_fee` hardcode 0 trong CheckoutController | Trung bình |
| Không có Unit Test / Feature Test | Thấp |

### 🚀 Hướng phát triển

**Ngắn hạn:**
- Validate giỏ hàng chỉ chứa món từ 1 nhà hàng
- Tính `delivery_fee` đúng từ nhà hàng
- Rate limiting: `throttle:5,1` cho route đăng nhập

**Trung hạn:**
- Tích hợp MoMo API / VNPay thực
- Email xác nhận đơn hàng cho khách hàng
- Real-time notification (Laravel Echo + Pusher)
- Xác thực email khi đăng ký

**Dài hạn:**
- Hệ thống voucher / mã giảm giá
- Tracking giao hàng trên bản đồ
- Mobile app dùng API + Sanctum
- Redis cache, Queue cho email

---

## 4. Hướng Dẫn Cài Đặt

**Yêu cầu:** PHP ≥ 8.2 (với ext-gd, ext-zip), Composer, MySQL 8.0+, Node.js ≥ 18

```bash
# 1. Cài dependencies
composer install && npm install

# 2. Cấu hình môi trường
cp .env.example .env
php artisan key:generate

# 3. Sửa .env: DB_DATABASE, DB_USERNAME, DB_PASSWORD
# Sửa .env: MAIL_USERNAME, MAIL_PASSWORD, ADMIN_EMAIL
# Sửa .env: PAYMENT_BANK_ID, PAYMENT_ACCOUNT_NUMBER...

# 4. Tạo database & migrate
mysql -u root -e "CREATE DATABASE resdeli CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate

# 5. Import dữ liệu mẫu (chọn 1 trong 2)
php artisan db:seed
# hoặc
mysql -u root resdeli < database/resdeli_export.sql

# 6. Chạy server
php artisan storage:link
npm run build
php artisan serve
```

Truy cập: **http://localhost:8000**

| Vai trò | Email | Mật khẩu |
|---------|-------|----------|
| Admin | admin@resdeli.com | password |
| Customer | customer@resdeli.com | password |

---

## 5. Cấu Trúc Thư Mục

```
resdeli/
├── app/
│   ├── Exports/
│   │   └── OrdersExport.php          # Xuất Excel đơn hàng (maatwebsite/excel)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/MenuApiController.php   # REST API 4 endpoints
│   │   │   ├── AdminController.php         # Dashboard + CRUD + Export Excel
│   │   │   ├── AuthController.php          # Login, Register, Logout
│   │   │   ├── CartController.php          # Giỏ hàng session AJAX
│   │   │   ├── CheckoutController.php      # Checkout + placeOrder + gửi mail
│   │   │   ├── HomeController.php          # Trang chủ
│   │   │   ├── MenuItemController.php      # CRUD món ăn
│   │   │   ├── OrderController.php         # Đặt hàng + quản lý
│   │   │   ├── ProfileController.php       # Hồ sơ cá nhân
│   │   │   ├── RestaurantController.php    # Nhà hàng + favorite
│   │   │   └── ReviewController.php        # Đánh giá
│   │   └── Middleware/
│   │       └── AdminMiddleware.php         # Kiểm tra quyền admin
│   ├── Mail/
│   │   └── OrderCreatedMail.php            # Email thông báo đơn hàng mới
│   └── Models/
│       ├── User.php, Profile.php
│       ├── Restaurant.php, Category.php, Tag.php
│       ├── MenuItem.php
│       ├── Order.php, OrderItem.php
│       ├── Review.php, Favorite.php
├── config/
│   ├── mail.php                            # Cấu hình mail + admin_email
│   └── payment.php                         # Cấu hình QR thanh toán
├── database/
│   ├── migrations/                         # 14 migration files
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   └── UpdateMenuItemImagesSeeder.php  # Cập nhật ảnh món ăn
│   └── resdeli_export.sql                  # SQL backup dữ liệu mẫu
├── resources/views/
│   ├── layouts/app.blade.php               # Layout chính (navbar + cart badge)
│   ├── layouts/admin.blade.php             # Layout admin (sidebar)
│   ├── emails/order-created.blade.php      # Template email HTML
│   ├── auth/                               # login, register
│   ├── cart/index.blade.php                # Trang giỏ hàng AJAX
│   ├── checkout/index.blade.php            # Checkout + QR thanh toán
│   ├── orders/                             # index, show (progress bar)
│   ├── profile/                            # show, edit
│   ├── restaurants/show.blade.php          # Thực đơn + sidebar cart
│   └── admin/                             # dashboard, users, categories,
│                                          # restaurants, menu-items, orders, reviews
└── routes/web.php                          # Public / Auth / Admin / API routes
```

---

## 6. Cấu Hình Thanh Toán QR

Sửa các giá trị trong `.env`:

```env
# Chuyển khoản ngân hàng (VietQR)
# Xem mã ngân hàng tại: https://api.vietqr.io/v2/banks
PAYMENT_BANK_ID=MB
PAYMENT_BANK_NAME="MB Bank"
PAYMENT_ACCOUNT_NUMBER=số_tài_khoản
PAYMENT_ACCOUNT_NAME=TEN_CHU_TK

# MoMo
PAYMENT_MOMO_PHONE=số_điện_thoại_momo
PAYMENT_MOMO_NAME=TEN_TAI_KHOAN

# ZaloPay
PAYMENT_ZALOPAY_PHONE=số_điện_thoại_zalopay
PAYMENT_ZALOPAY_NAME=TEN_TAI_KHOAN
```

---

## 7. Cấu Hình Email

Dùng Gmail SMTP với App Password:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_gmail@gmail.com
MAIL_PASSWORD=xxxx_xxxx_xxxx_xxxx   # App Password 16 ký tự
ADMIN_EMAIL=your_gmail@gmail.com    # Email nhận thông báo đơn hàng mới
```

Tạo App Password tại: **https://myaccount.google.com/apppasswords**

Test gửi mail:
```bash
php artisan mail:test
```

---

## 8. API Reference

Base URL: `http://localhost:8000/api/v1`

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| GET | `/restaurants` | Danh sách nhà hàng (filter: search, city; sort: rating/delivery_fee/delivery_time; page) |
| GET | `/restaurants/{slug}/menu` | Thực đơn nhóm theo danh mục |
| GET | `/categories` | Danh sách danh mục |
| GET | `/search?q=keyword` | Tìm đồng thời nhà hàng + món ăn |

**Ví dụ response `/search`:**
```json
{
  "restaurants": [
    { "id": 1, "name": "Phở Hà Nội 1988", "slug": "pho-ha-noi-1988", "rating": 4.5 }
  ],
  "items": [
    { "id": 1, "name": "Phở Bò Tái Chín", "restaurant_name": "Phở Hà Nội 1988", "effective_price": 85000 }
  ]
}
```

---

<div align="center">

**ResDeli** — Đặt món dễ dàng, giao hàng nhanh chóng 🍜

*Laravel 12 · MySQL · VietQR · Gmail SMTP · Excel Export*

⭐ [GitHub Repository](https://github.com/potralimex/webbandoan_HCM)

</div>
