<div align="center">

# 🍜 ResDeli — Hệ Thống Đặt Món Ăn Trực Tuyến

[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)

**ResDeli** là ứng dụng web đặt món ăn trực tuyến xây dựng trên **Laravel 12 + MySQL**, áp dụng kiến trúc MVC, hỗ trợ đầy đủ luồng từ khám phá nhà hàng → đặt món → theo dõi đơn hàng → quản trị hệ thống.

</div>

---

## Mục Lục

1. [Phân tích hệ thống & yêu cầu](#1-phân-tích-hệ-thống--yêu-cầu)
2. [Bài làm đã đạt được](#2-bài-làm-đã-đạt-được)
3. [Thiếu sót & hướng phát triển](#3-thiếu-sót--hướng-phát-triển)
4. [Hướng dẫn cài đặt](#4-hướng-dẫn-cài-đặt)
5. [Cấu trúc thư mục](#5-cấu-trúc-thư-mục)
6. [API Reference](#6-api-reference)

---

## 1. Phân Tích Hệ Thống & Yêu Cầu

### 1.1 Bối cảnh bài toán

Nhu cầu đặt món ăn trực tuyến ngày càng tăng, nhưng nhiều nhà hàng nhỏ chưa có nền tảng quản lý đơn hàng hiệu quả. ResDeli giải quyết bài toán kết nối khách hàng với nhà hàng, tự động hóa quy trình đặt món và cung cấp công cụ quản trị toàn diện.

### 1.2 Các tác nhân (Actor)

| Actor | Mô tả |
|-------|-------|
| **Guest** | Xem nhà hàng, thực đơn, tìm kiếm — không cần đăng nhập |
| **Customer** | Đặt món, theo dõi đơn hàng, đánh giá, yêu thích nhà hàng |
| **Admin** | Toàn quyền quản trị: users, nhà hàng, món ăn, đơn hàng, đánh giá |

> Hệ thống hiện tích hợp vai trò **Restaurant Owner** vào Admin (chưa tách riêng).

### 1.3 Yêu cầu chức năng

| Mã | Chức năng | Mức độ |
|----|-----------|--------|
| FR01 | Đăng ký / Đăng nhập / Đăng xuất | Bắt buộc |
| FR02 | Quản lý hồ sơ cá nhân, đổi mật khẩu, avatar | Bắt buộc |
| FR03 | Xem danh sách nhà hàng, lọc, sắp xếp | Bắt buộc |
| FR04 | Xem thực đơn phân nhóm theo danh mục | Bắt buộc |
| FR05 | Giỏ hàng: thêm/xóa/sửa số lượng, tính tổng | Bắt buộc |
| FR06 | Đặt hàng: địa chỉ, SĐT, ghi chú, thanh toán | Bắt buộc |
| FR07 | Theo dõi trạng thái đơn hàng theo pipeline | Bắt buộc |
| FR08 | Hủy đơn hàng (chỉ khi pending) | Bắt buộc |
| FR09 | Đánh giá nhà hàng sau khi nhận hàng | Quan trọng |
| FR10 | Yêu thích nhà hàng (AJAX, lưu DB) | Quan trọng |
| FR11 | Admin CRUD: danh mục, nhà hàng, món ăn, đơn hàng, đánh giá | Bắt buộc |
| FR12 | Dashboard thống kê: doanh thu, biểu đồ, top nhà hàng | Quan trọng |
| FR13 | REST API: tìm kiếm, thực đơn, danh mục | Quan trọng |
| FR14 | Tìm kiếm thời gian thực (debounce + dropdown) | Quan trọng |

### 1.4 Yêu cầu phi chức năng

| Loại | Yêu cầu |
|------|---------|
| **Bảo mật** | CSRF trên mọi form, kiểm tra ownership đơn hàng, phân quyền theo role |
| **Hiệu năng** | Eager loading tránh N+1, pagination danh sách dài |
| **Giao diện** | Responsive, nhất quán CSS variables, không dùng framework CSS ngoài |
| **Bảo trì** | Chuẩn MVC Laravel, route naming rõ ràng, validation server-side |

### 1.5 Thiết kế cơ sở dữ liệu

11 bảng, quan hệ đầy đủ:

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

### 1.6 Kiến trúc MVC

```
Browser Request
    │
    ▼
routes/web.php          ← Định tuyến, phân nhóm middleware
    │
    ▼
Middleware (auth)       ← Kiểm tra đăng nhập, phân quyền
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

---

## 2. Bài Làm Đã Đạt Được

### ✅ Xác thực & Phân quyền

- Đăng ký, đăng nhập, đăng xuất hoàn chỉnh
- Phân quyền 2 role: `admin` / `customer` qua `User::isAdmin()`
- Middleware `auth` bảo vệ toàn bộ route cần đăng nhập
- CSRF protection trên 100% form
- Kiểm tra ownership: `order->user_id === Auth::id()` trước khi xem đơn

### ✅ Quản lý hồ sơ

- Xem/sửa thông tin cá nhân, bio, thành phố
- Đổi mật khẩu với xác nhận mật khẩu cũ
- Upload avatar (validate mime + size)
- Hiển thị đơn hàng gần đây và nhà hàng yêu thích trên trang hồ sơ

### ✅ Nhà hàng & Thực đơn

- Trang chủ hiển thị nhà hàng nổi bật qua REST API + JS
- Lọc theo thành phố, sắp xếp theo rating / phí giao / thời gian giao
- Trang chi tiết nhà hàng: thực đơn phân nhóm theo danh mục
- Hiển thị giá khuyến mãi (`sale_price`), tags màu sắc, calories, thời gian chuẩn bị
- Trạng thái mở/đóng cửa (`is_open`)

### ✅ Giỏ hàng (Session-based)

- Lưu giỏ hàng trong **PHP Session** (server-side, bền vững qua reload)
- Thêm món từ trang nhà hàng qua AJAX → cập nhật badge navbar ngay lập tức
- Trang `/cart`: xem danh sách, tăng/giảm số lượng, xóa từng món, xóa tất cả — toàn bộ AJAX không reload
- Tính tổng tiền tự động theo thời gian thực

### ✅ Đặt hàng & Checkout

- Trang `/checkout`: form địa chỉ, SĐT, ghi chú
- 4 phương thức thanh toán: COD, Chuyển khoản, MoMo, ZaloPay (mock)
- Validation server-side đầy đủ với thông báo tiếng Việt
- Tạo `Order` + `OrderItems` trong DB, sinh mã đơn `RD-XXXXXX`
- Xóa giỏ hàng sau khi đặt thành công

### ✅ Theo dõi đơn hàng

- Danh sách đơn hàng với badge trạng thái màu sắc
- Chi tiết đơn: progress bar 5 bước (pending → confirmed → preparing → delivering → delivered)
- Hủy đơn khi còn `pending`
- Đánh giá nhà hàng sau khi nhận hàng (rating sao + comment)

### ✅ Admin Dashboard

- Thống kê tổng quan: tổng users, nhà hàng, đơn hàng, doanh thu tháng
- So sánh với tháng trước (% tăng/giảm)
- Biểu đồ doanh thu 12 tháng (Chart.js)
- Phân tích trạng thái đơn hàng, phương thức thanh toán
- Top 5 nhà hàng rating cao nhất
- 8 đơn hàng gần nhất

### ✅ Admin CRUD

| Module | Chức năng |
|--------|-----------|
| Users | Xem, tìm kiếm, lọc theo role, khóa/mở tài khoản |
| Categories | Tạo, sửa, xóa danh mục (có icon emoji) |
| Restaurants | CRUD đầy đủ + upload ảnh + quản lý giờ mở cửa |
| Menu Items | CRUD + upload ảnh + gắn tag + giá khuyến mãi |
| Orders | Xem tất cả, lọc theo trạng thái, cập nhật trạng thái |
| Reviews | Duyệt, xóa — tự động cập nhật rating nhà hàng |

### ✅ REST API

```
GET /api/v1/restaurants          → Danh sách + filter + sort + paginate
GET /api/v1/restaurants/{slug}/menu → Thực đơn nhóm theo danh mục
GET /api/v1/categories           → Danh sách danh mục
GET /api/v1/search?q=...         → Tìm đồng thời nhà hàng + món ăn
```

### ✅ Tính năng UX

- Tìm kiếm thời gian thực: debounce 350ms → dropdown kết quả
- Toggle yêu thích nhà hàng bằng AJAX
- Toast notification khi thêm vào giỏ hàng
- Responsive layout trên mobile/tablet/desktop
- CSS variables nhất quán (`--primary`, `--border`, `--radius`...)

---

## 3. Thiếu Sót & Hướng Phát Triển

### ⚠️ Thiếu sót cần lưu ý

#### Bảo mật
| Vấn đề | Mô tả | Mức độ |
|--------|-------|--------|
| Chưa có rate limiting | Route `/login`, `/register` có thể bị brute-force | Cao |
| Chưa xác thực email | User đăng ký xong dùng được ngay, không cần verify email | Trung bình |
| Chưa có middleware role riêng | Admin check dùng `$this->authorize('admin-action')` nhưng chưa có Middleware class độc lập | Trung bình |
| API không có authentication | `/api/v1/*` public hoàn toàn, không có API token/Sanctum | Trung bình |

#### Nghiệp vụ
| Vấn đề | Mô tả | Mức độ |
|--------|-------|--------|
| Giỏ hàng không kiểm tra nhà hàng | Có thể thêm món từ nhiều nhà hàng khác nhau vào cùng 1 giỏ | Cao |
| Thanh toán là mock | MoMo, ZaloPay, Bank Transfer chưa tích hợp thực | Cao |
| Không kiểm tra `is_available` khi đặt hàng | Món đã tắt vẫn có thể đặt được nếu còn trong session | Trung bình |
| Không có thông báo real-time | Admin không biết có đơn mới nếu không reload trang | Trung bình |
| Delivery fee cố định 0 khi checkout | `CheckoutController` hardcode `delivery_fee = 0` thay vì lấy từ nhà hàng | Trung bình |
| Chưa có vai trò Restaurant Owner thực sự | Owner không thể tự quản lý nhà hàng của mình, phải qua Admin | Thấp |

#### Kỹ thuật
| Vấn đề | Mô tả | Mức độ |
|--------|-------|--------|
| Không có Form Request classes | Validation viết trực tiếp trong Controller, khó tái sử dụng | Thấp |
| Không có Service layer | Logic nghiệp vụ phức tạp (tính giá, tạo đơn) nằm trong Controller | Thấp |
| Không có test | Không có Unit Test hay Feature Test nào | Thấp |
| Ảnh upload chưa có resize | Upload ảnh gốc kích thước lớn, tốn dung lượng | Thấp |

### 🚀 Hướng phát triển

#### Ngắn hạn (ưu tiên cao)
- [ ] Thêm validation: giỏ hàng chỉ chứa món từ 1 nhà hàng, cảnh báo khi thêm từ nhà hàng khác
- [ ] Tính `delivery_fee` đúng từ nhà hàng trong `CheckoutController`
- [ ] Kiểm tra `is_available` của món khi `placeOrder()`
- [ ] Thêm middleware `AdminMiddleware` độc lập thay vì dùng Policy
- [ ] Rate limiting cho route đăng nhập: `throttle:5,1`

#### Trung hạn
- [ ] Tích hợp thanh toán thực: VNPay hoặc MoMo API
- [ ] Email notification: xác nhận đơn hàng, thay đổi trạng thái (Laravel Mail + Queue)
- [ ] Xác thực email khi đăng ký (`MustVerifyEmail`)
- [ ] Real-time notification cho Admin khi có đơn mới (Laravel Echo + Pusher)
- [ ] Tách vai trò Restaurant Owner: dashboard riêng, chỉ quản lý nhà hàng của mình

#### Dài hạn
- [ ] Hệ thống voucher / mã giảm giá
- [ ] Tracking giao hàng trên bản đồ (Google Maps API)
- [ ] Mobile app (Flutter / React Native) dùng API có Sanctum auth
- [ ] Tối ưu hiệu năng: Redis cache cho danh sách nhà hàng, Queue cho email
- [ ] CI/CD pipeline, deploy lên VPS/cloud (Nginx + PHP-FPM)

---

## 4. Hướng Dẫn Cài Đặt

**Yêu cầu:** PHP ≥ 8.2, Composer, MySQL 8.0+, Node.js ≥ 18

```bash
# 1. Cài dependencies
composer install && npm install

# 2. Cấu hình môi trường
cp .env.example .env
php artisan key:generate
# Sửa DB_DATABASE, DB_USERNAME, DB_PASSWORD trong .env

# 3. Tạo database & migrate
mysql -u root -e "CREATE DATABASE resdeli CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate

# 4. (Tùy chọn) Import dữ liệu mẫu
mysql -u root resdeli < database/resdeli_export.sql

# 5. Storage link & chạy server
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
│   ├── Http/Controllers/
│   │   ├── Api/MenuApiController.php     # REST API (search, menu, categories)
│   │   ├── AdminController.php           # Dashboard + Users + Categories
│   │   ├── AuthController.php            # Login, Register, Logout
│   │   ├── CartController.php            # Giỏ hàng session-based (AJAX)
│   │   ├── CheckoutController.php        # Trang checkout + placeOrder
│   │   ├── HomeController.php            # Trang chủ
│   │   ├── MenuItemController.php        # CRUD món ăn
│   │   ├── OrderController.php           # Đặt hàng + quản lý đơn
│   │   ├── ProfileController.php         # Hồ sơ cá nhân
│   │   ├── RestaurantController.php      # Nhà hàng + favorite toggle
│   │   └── ReviewController.php          # Đánh giá
│   └── Models/
│       ├── User.php, Profile.php
│       ├── Restaurant.php, Category.php, Tag.php
│       ├── MenuItem.php
│       ├── Order.php, OrderItem.php
│       ├── Review.php, Favorite.php
├── database/
│   ├── migrations/                       # 14 migration files
│   └── resdeli_export.sql                # SQL backup dữ liệu mẫu
├── resources/views/
│   ├── layouts/app.blade.php             # Layout chính (navbar, footer, toast)
│   ├── auth/                             # login, register
│   ├── cart/index.blade.php              # Trang giỏ hàng
│   ├── checkout/index.blade.php          # Trang thanh toán
│   ├── orders/                           # index, show
│   ├── profile/                          # show, edit
│   ├── restaurants/show.blade.php        # Chi tiết nhà hàng + sidebar cart
│   └── admin/                            # dashboard, users, categories, restaurants, menu-items, orders, reviews
└── routes/web.php                        # Public / Auth / Admin / API routes
```

---

## 6. API Reference

Base URL: `http://localhost:8000/api/v1`

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| GET | `/restaurants` | Danh sách nhà hàng (filter: search, city; sort: rating/delivery_fee/delivery_time) |
| GET | `/restaurants/{slug}/menu` | Thực đơn nhóm theo danh mục |
| GET | `/categories` | Danh sách danh mục |
| GET | `/search?q=keyword` | Tìm đồng thời nhà hàng + món ăn |

---

<div align="center">

**ResDeli** — Đặt món dễ dàng, giao hàng nhanh chóng 🍜

*Laravel 12 · MySQL · MVC · REST API*

</div>
