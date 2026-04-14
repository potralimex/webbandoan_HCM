<div align="center">

# 🍜 ResDeli — Hệ Thống Đặt Món Ăn Trực Tuyến

[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

**ResDeli** là ứng dụng web đặt món ăn trực tuyến được xây dựng trên nền tảng **Laravel 12** và **MySQL**, hỗ trợ đầy đủ luồng hoạt động từ khám phá nhà hàng, đặt món, theo dõi đơn hàng đến quản trị toàn bộ hệ thống.

</div>

---

## 📋 Mục Lục

1. [Giới thiệu bài toán](#1-giới-thiệu-bài-toán)
2. [Phân tích yêu cầu người dùng](#2-phân-tích-yêu-cầu-người-dùng)
3. [Thiết kế hệ thống](#3-thiết-kế-hệ-thống)
4. [Triển khai hệ thống](#4-triển-khai-hệ-thống)
5. [Hướng dẫn cài đặt](#5-hướng-dẫn-cài-đặt)
6. [Cấu trúc thư mục](#6-cấu-trúc-thư-mục)
7. [API Reference](#7-api-reference)
8. [Tài khoản mặc định](#8-tài-khoản-mặc-định)

---

## 1. Giới Thiệu Bài Toán

### 1.1 Bối cảnh

Trong thời đại số hóa, nhu cầu đặt món ăn trực tuyến ngày càng tăng cao. Người dùng mong muốn tìm kiếm nhà hàng, xem thực đơn và đặt món chỉ trong vài bước đơn giản trên trình duyệt. Tuy nhiên, nhiều nhà hàng nhỏ và vừa chưa có nền tảng quản lý đơn hàng hiệu quả, dẫn đến mất khách hàng và thiếu dữ liệu vận hành.

### 1.2 Vấn đề cần giải quyết

| Vấn đề | Mô tả |
|---|---|
| **Người dùng** | Khó tìm nhà hàng phù hợp, không biết thực đơn, không theo dõi được đơn hàng |
| **Nhà hàng** | Không có hệ thống nhận đơn tự động, quản lý thủ công gây sai sót |
| **Quản trị** | Thiếu công cụ giám sát tổng thể: doanh thu, đánh giá, người dùng |

### 1.3 Mục tiêu dự án

- ✅ Xây dựng nền tảng đặt món **đa nhà hàng** trên web
- ✅ Hỗ trợ **3 vai trò**: Khách hàng, Chủ nhà hàng, Admin
- ✅ Quản trị toàn diện: nhà hàng, thực đơn, đơn hàng, đánh giá, người dùng
- ✅ Tích hợp **REST API** để frontend JS tương tác động
- ✅ Giao diện hiện đại, responsive, trải nghiệm người dùng mượt mà

### 1.4 Phạm vi hệ thống

```
┌─────────────────────────────────────────────────────┐
│                      ResDeli                        │
│                                                     │
│  ┌──────────┐   ┌───────────────┐   ┌───────────┐  │
│  │ Khách    │   │  Chủ nhà hàng │   │  Admin    │  │
│  │ hàng     │   │  (Owner)      │   │           │  │
│  └────┬─────┘   └──────┬────────┘   └─────┬─────┘  │
│       │                │                  │         │
│  Đặt món          Thực đơn         Quản trị        │
│  Theo dõi         Đơn hàng         toàn hệ thống   │
└─────────────────────────────────────────────────────┘
```

---

## 2. Phân Tích Yêu Cầu Người Dùng

### 2.1 Các Actor (Tác nhân)

#### 👤 Khách vãng lai (Guest)
- Xem danh sách nhà hàng nổi bật trên trang chủ
- Tìm kiếm nhà hàng/món ăn qua thanh tìm kiếm
- Xem thực đơn chi tiết của từng nhà hàngg
- Phải đăng ký / đăng nhập để đặt mónn

#### 🛒 Khách hàng đã đăng nhập (Customer)
- Đăng ký, đăng nhập, đổi mật khẩu
- Quản lý thông tin hồ sơ cá nhân & avatar
- Xem thực đơn, lọc theo danh mục, tìm kiếm món ăn
- Thêm vào giỏ hàng (giỏ hàng phía client, lưu localStorage)
- Đặt hàng: nhập địa chỉ giao hàng, SĐT, ghi chú, chọn thanh toán
- Xem lịch sử đơn hàng & chi tiết từng đơn
- Hủy đơn hàng đang ở trạng thái *chờ xác nhận*
- Đánh giá nhà hàng sau khi nhận hàng
- Yêu thích / bỏ yêu thích nhà hàng (AJAX)

#### 🏪 Chủ nhà hàng (Restaurant Owner) *(tích hợp trong Admin)*
- Quản lý thông tin nhà hàng của mình
- Thêm / sửa / xóa món ăn trong thực đơn
- Xem và cập nhật trạng thái đơn hàng
- Xem đánh giá từ khách hàng

#### 🔑 Quản trị viên (Admin)
- Toàn quyền trên hệ thống
- Quản lý người dùng: xem, tìm kiếm, khóa/mở tài khoản
- Quản lý danh mục (Category) CRUD
- Quản lý nhà hàng CRUD + upload ảnh
- Quản lý món ăn CRUD + upload ảnh + gắn tag
- Quản lý đơn hàng: xem tất cả, lọc, thay đổi trạng thái
- Quản lý đánh giá: duyệt, xóa
- Xem dashboard tổng quan: thống kê doanh thu, đơn hàng, đồ thị

### 2.2 Yêu cầu chức năng (Functional Requirements)

| Mã | Chức năng | Mô tả |
|----|-----------|-------|
| FR01 | Đăng ký tài khoản | Email, mật khẩu, tên, SĐT — validation đầy đủ |
| FR02 | Đăng nhập / Đăng xuất | Xác thực session, redirect theo vai trò |
| FR03 | Quản lý hồ sơ | Cập nhật thông tin, đổi mật khẩu, đổi avatar |
| FR04 | Xem nhà hàng | Danh sách nổi bật, lọc theo thành phố, sắp xếp theo rating/phí giao |
| FR05 | Xem thực đơn | Phân nhóm theo danh mục, hiển thị giá khuyến mãi, tags |
| FR06 | Giỏ hàng | Thêm/xóa/sửa số lượng, tính tổng tiền tự động, persist qua JavaScript |
| FR07 | Đặt hàng | Validate dữ liệu, kiểm tra giá trị tối thiểu, tạo mã đơn ngẫu nhiên |
| FR08 | Theo dõi đơn hàng | Xem trạng thái theo pipeline, lịch sử tất cả đơn |
| FR09 | Hủy đơn | Chỉ khi trạng thái còn *pending* |
| FR10 | Đánh giá | Gửi sau khi nhận hàng, admin duyệt trước khi hiển thị |
| FR11 | Yêu thích | Toggle bất đồng bộ (AJAX), lưu vào database |
| FR12 | Quản lý Admin | CRUD đầy đủ cho Categories, Restaurants, MenuItems, Reviews, Users |
| FR13 | REST API | 4 endpoint JSON cho frontend JS tiêu thụ |
| FR14 | Tìm kiếm | Tìm đồng thời nhà hàng + món ăn qua API |

### 2.3 Yêu cầu phi chức năng (Non-Functional Requirements)

| Loại | Yêu cầu |
|------|---------|
| **Bảo mật** | CSRF protection trên mọi form; Authorization theo vai trò (policy `admin-action`) |
| **Hiệu năng** | Eager loading (`with()`) tránh N+1 query; Pagination trên danh sách dài |
| **Khả dụng** | Responsive trên mọi thiết bị (mobile/tablet/desktop) |
| **Bảo trì** | Tuân thủ chuẩn MVC; Route naming có namespace `admin.` / `api.` |
| **Dữ liệu** | Validation server-side trên mọi form; Foreign key constraints |

---

## 3. Thiết Kế Hệ Thống

### 3.1 Kiến Trúc Tổng Thể

ResDeli áp dụng kiến trúc **MVC (Model - View - Controller)** của Laravel kết hợp với một lớp **REST API** cho các tính năng động:

```
┌─────────────────────────────────────────────────────────────┐
│                        Client (Browser)                      │
│         HTML/CSS/JS   ←→   Blade Templates                  │
│                              ↕ AJAX/Fetch                   │
└──────────────────────┬────────────────────────────────────┘
                       ↓
┌─────────────────────────────────────────────────────────────┐
│                   Laravel Application                        │
│                                                             │
│  Routes (web.php)                                          │
│    ├── Public Routes      → HomeController, RestaurantCtrl  │
│    ├── Auth Routes        → AuthController                  │
│    ├── Authenticated      → OrderCtrl, ProfileCtrl,         │
│    │                         ReviewCtrl, RestaurantCtrl     │
│    ├── Admin Routes       → AdminController, all CRUD Ctrl  │
│    └── API Routes         → MenuApiController               │
│                                                             │
│  Controllers → Models → Eloquent ORM → Database (MySQL)    │
└─────────────────────────────────────────────────────────────┘
                       ↓
┌─────────────────────────────────────────────────────────────┐
│                    MySQL Database                            │
│         10 bảng chính + pivot tables                        │
└─────────────────────────────────────────────────────────────┘
```

### 3.2 Thiết Kế Cơ Sở Dữ Liệu (ERD)

#### Danh sách bảng

| # | Bảng | Mô tả |
|---|------|-------|
| 1 | `users` | Tài khoản người dùng (admin / customer) |
| 2 | `profiles` | Thông tin mở rộng của user (1-1 với users) |
| 3 | `categories` | Danh mục món ăn (Pizza, Sushi, Bún bò...) |
| 4 | `restaurants` | Thông tin nhà hàng |
| 5 | `menu_items` | Món ăn trong thực đơn |
| 6 | `tags` | Nhãn dán vào món ăn (Cay, Ngon, Best Seller...) |
| 7 | `menu_item_tag` | Pivot bảng: many-to-many MenuItem ↔ Tag |
| 8 | `orders` | Đơn hàng |
| 9 | `order_items` | Chi tiết từng món trong đơn hàng |
| 10 | `reviews` | Đánh giá của khách hàng |
| 11 | `favorites` | Pivot bảng: many-to-many User ↔ Restaurant |

#### Sơ đồ quan hệ (ERD)

```
users (1) ────────── (1) profiles
  │
  ├── (1)────────(∞) restaurants
  │                       │
  │                       ├── (1)──────(∞) menu_items ──(∞)──(∞)── tags
  │                       │                    │           [via menu_item_tag]
  │                       ├── (1)──────(∞) orders ──────(1)──(∞) order_items
  │                       │                    │                      │
  │                       └── (1)──────(∞) reviews         references menu_items
  │
  ├── (∞)────────(∞) restaurants   [via favorites]
  │
  └── (1)────────(∞) orders
                       └── (1)──── (0..1) reviews
```

#### Schema chi tiết

**Bảng `users`**
```sql
id, name, email, password, role ENUM('admin','customer'), 
phone, avatar, email_verified_at, remember_token, timestamps
```

**Bảng `profiles`**
```sql
id, user_id FK, bio, address, city, date_of_birth, timestamps
```

**Bảng `restaurants`**
```sql
id, owner_id FK(users), name, slug UNIQUE, description,
address, city, phone, email, image, rating DECIMAL(3,2),
delivery_time INT, delivery_fee DECIMAL, min_order DECIMAL,
is_open BOOL, is_active BOOL, open_time TIME, close_time TIME, timestamps
```

**Bảng `menu_items`**
```sql
id, restaurant_id FK, category_id FK, name, slug UNIQUE,
description, price DECIMAL, sale_price DECIMAL nullable,
image, is_available BOOL, is_featured BOOL, prep_time INT, calories INT, timestamps
```

**Bảng `orders`**
```sql
id, user_id FK, restaurant_id FK, order_number VARCHAR UNIQUE,
status ENUM('pending','confirmed','preparing','delivering','delivered','cancelled'),
subtotal, delivery_fee, total DECIMAL, delivery_address, phone, notes,
payment_method ENUM('cash','momo','bank_transfer'),
payment_status ENUM('pending','paid','refunded'), timestamps
```

**Bảng `order_items`**
```sql
id, order_id FK, menu_item_id FK nullable, item_name, item_price, quantity, subtotal, timestamps
```

**Bảng `reviews`**
```sql
id, user_id FK, restaurant_id FK, order_id FK unique, rating INT(1-5),
comment TEXT, is_approved BOOL, timestamps
```

### 3.3 Thiết Kế Controllers

| Controller | Namespace | Chức năng chính |
|-----------|-----------|-----------------|
| `HomeController` | Web | Trang chủ, nhà hàng nổi bật qua API JS |
| `AuthController` | Web | Đăng ký, đăng nhập, đăng xuất |
| `RestaurantController` | Web | Hiển thị nhà hàng (public), CRUD Admin, Toggle Favorite |
| `MenuItemController` | Web | CRUD món ăn (Admin) + upload ảnh + gắn tag |
| `OrderController` | Web | Đặt hàng, xem lịch sử, hủy đơn (Customer); Quản lý + cập nhật trạng thái (Admin) |
| `ReviewController` | Web | Gửi đánh giá (Customer); Duyệt/Xóa (Admin) |
| `ProfileController` | Web | Xem/Sửa hồ sơ, đổi mật khẩu, upload avatar |
| `AdminController` | Web | Dashboard thống kê, quản lý Users, CRUD Categories |
| `MenuApiController` | API | 4 endpoint JSON: danh sách nhà hàng, thực đơn, danh mục, tìm kiếm |

### 3.4 Thiết Kế Routes

Hệ thống phân chia route thành 4 nhóm rõ ràng:

```
/                        → Trang chủ (public)
/login, /register        → Xác thực (public)
/restaurants/{slug}      → Chi tiết nhà hàng (public)

/profile/*               → Hồ sơ cá nhân (auth required)
/orders/*                → Đặt hàng & lịch sử (auth required)
/reviews                 → Gửi đánh giá (auth required)

/admin/dashboard         → Dashboard
/admin/users             → Quản lý người dùng
/admin/categories/*      → CRUD danh mục
/admin/restaurants/*     → CRUD nhà hàng
/admin/menu-items/*      → CRUD món ăn
/admin/orders            → Quản lý đơn hàng
/admin/reviews           → Quản lý đánh giá

/api/v1/restaurants      → API: danh sách nhà hàng (+ filter/sort)
/api/v1/restaurants/{slug}/menu → API: thực đơn nhà hàng
/api/v1/categories       → API: danh sách danh mục
/api/v1/search           → API: tìm kiếm toàn hệ thống
```

### 3.5 Thiết Kế Giao Diện (Views)

#### Cây view

```
resources/views/
├── layouts/
│   └── app.blade.php          # Layout chính: navbar, footer, flash messages
├── welcome.blade.php          # Trang chủ: hero, tìm kiếm, nhà hàng nổi bật
├── auth/
│   ├── login.blade.php        # Form đăng nhập
│   └── register.blade.php     # Form đăng ký
├── restaurants/
│   └── show.blade.php         # Chi tiết nhà hàng + thực đơn + giỏ hàng + đánh giá
├── orders/
│   ├── index.blade.php        # Lịch sử đơn hàng
│   └── show.blade.php         # Chi tiết đơn hàng
├── profile/
│   ├── show.blade.php         # Trang hồ sơ
│   └── edit.blade.php         # Chỉnh sửa hồ sơ
└── admin/
    ├── dashboard.blade.php    # Bảng điều khiển
    ├── users/index.blade.php  # Danh sách người dùng
    ├── categories/            # CRUD danh mục (index, create, edit)
    ├── restaurants/           # CRUD nhà hàng (index, create, edit)
    ├── menu-items/            # CRUD món ăn (index, create, edit)
    ├── orders/index.blade.php # Danh sách đơn hàng
    └── reviews/index.blade.php # Danh sách đánh giá
```

### 3.6 Luồng Hoạt Động Chính

#### Luồng Đặt Hàng
```
Khách hàng xem nhà hàng
    → Xem thực đơn (phân nhóm theo danh mục)
    → Thêm món vào giỏ (JavaScript + localStorage)
    → Nhấn "Đặt hàng" → Form checkout
    → POST /orders (validate server-side)
    → Tạo Order + OrderItems trong DB
    → Redirect sang trang xác nhận đơn hàng
    → Admin thấy đơn mới → Cập nhật trạng thái
    → Khách theo dõi: pending → confirmed → preparing → delivering → delivered
    → Khách đánh giá sau khi nhận hàng
```

#### Luồng Xác Thực
```
Guest → /register → Validate → Insert User → Redirect home
Guest → /login → Validate → Auth::attempt() → Redirect theo role
        ↳ Admin → /admin/dashboard
        ↳ Customer → /
Auth → POST /logout → Auth::logout() → /login
```

---

## 4. Triển Khai Hệ Thống

### 4.1 Công Nghệ Sử Dụng

| Thành phần | Công nghệ | Phiên bản |
|------------|-----------|-----------|
| Backend Framework | Laravel | 12.x |
| Ngôn ngữ server | PHP | ≥ 8.2 |
| Database | MySQL | 8.0+ |
| ORM | Eloquent | (built-in Laravel) |
| Template Engine | Blade | (built-in Laravel) |
| Frontend CSS | Bootstrap | 5.x (CDN) |
| Icons | Bootstrap Icons | (CDN) |
| JavaScript | Vanilla JS + Fetch API | ES6+ |
| Package Manager | Composer + NPM | |
| Storage | Laravel Storage (public disk) | |
| Build Tool | Vite | |

### 4.2 Triển Khai Models

#### Model `User`
- **Quan hệ:** `hasOne Profile`, `hasMany Restaurant(owner_id)`, `hasMany Order`, `hasMany Review`, `belongsToMany Restaurant via favorites`
- **Methods:** `isAdmin()`, `isCustomer()`, `isRestaurantOwner()`, `getAvatarUrlAttribute()`

#### Model `Restaurant`
- **Quan hệ:** `belongsTo User(owner)`, `hasMany MenuItem`, `hasMany Order`, `hasMany Review`, `belongsToMany User via favorites`
- **Methods:** `getImageUrlAttribute()`, `getRouteKeyName()` → slug, `updateRating()`

#### Model `MenuItem`
- **Quan hệ:** `belongsTo Restaurant`, `belongsTo Category`, `belongsToMany Tag via menu_item_tag`, `hasMany OrderItem`
- **Methods:** `getEffectivePriceAttribute()` (trả về `sale_price ?? price`), `getImageUrlAttribute()`

#### Model `Order`
- **Quan hệ:** `belongsTo User`, `belongsTo Restaurant`, `hasMany OrderItem`, `hasOne Review`
- **Methods:** `getStatusBadgeAttribute()` (trả về HTML badge màu theo trạng thái), `generateOrderNumber()` (tạo mã dạng `RD-XXXXXX`)
- **Cast:** `subtotal`, `delivery_fee`, `total` → float

#### Model `Review`
- **Quan hệ:** `belongsTo User`, `belongsTo Restaurant`, `belongsTo Order`
- **Logic:** Sau khi approve/delete, trigger `$restaurant->updateRating()` cập nhật điểm trung bình

### 4.3 Triển Khai Controllers

#### `AuthController` — Xác thực người dùng
```
showLogin()   → GET /login
login()       → POST /login     (validate email+pass → Auth::attempt)
showRegister()→ GET /register
register()    → POST /register  (validate → User::create → login)
logout()      → POST /logout    (Auth::logout → redirect)
```

#### `RestaurantController` — Nhà hàng
```
show()          → GET /restaurants/{slug}       [public]
index()         → GET /admin/restaurants        [admin]
create()/store()→ GET+POST /admin/restaurants/create  [admin + file upload]
edit()/update() → GET+PUT  /admin/restaurants/{id}/edit [admin + file upload]
destroy()       → DELETE   /admin/restaurants/{id}     [admin + delete file]
toggleFavorite()→ POST /restaurants/{slug}/favorite     [auth, returns JSON]
```

#### `OrderController` — Đơn hàng
```
store()      → POST /orders        (validate → tính tổng → Order::create + OrderItems)
index()      → GET  /orders        (orders của user đang đăng nhập)
show()       → GET  /orders/{id}   (kiểm tra ownership hoặc admin)
cancel()     → POST /orders/{id}/cancel  (chỉ khi status=pending)
adminIndex() → GET  /admin/orders  (filter by status/search)
updateStatus()→ PUT /admin/orders/{id}/status
```

#### `AdminController` — Dashboard & Quản trị
```
dashboard()    → Tổng hợp: users/restaurants/orders/revenue count; recent orders; top restaurants; monthly sales chart data
users()        → Paginate users với search (name/email) + filter (role)
toggleUserStatus() → Khóa/Mở user bằng cách null/set email_verified_at
categories()   → Paginate categories + withCount menuItems
create/store/edit/update/destroyCategory() → CRUD danh mục
```

#### `MenuApiController` — REST API
```
GET /api/v1/restaurants
    → Filter: search, city
    → Sort: rating | delivery_fee | delivery_time
    → Paginate 9/page
    → Response: { data: [...], meta: { total, current_page, last_page } }

GET /api/v1/restaurants/{slug}/menu
    → Response: { restaurant: {...}, menu: { "Danh mục": [{...}, ...] } }

GET /api/v1/categories
    → Response: { data: [{id, name, slug, icon}] }

GET /api/v1/search?q=queryString
    → Tìm đồng thời restaurants + menu_items
    → Response: { restaurants: [...], items: [...] }
```

### 4.4 Triển Khai Tính Năng Nổi Bật

#### 🛒 Giỏ Hàng (Phía Client)
Giỏ hàng được quản lý hoàn toàn bằng **JavaScript + localStorage**, không cần session server:
- Thêm/xóa/thay đổi số lượng món ăn
- Tính tổng tiền theo thời gian thực
- Dữ liệu giỏ hàng được serialize thành JSON và POST lên server khi đặt hàng
- Server validate lại toàn bộ giá và tồn kho trước khi tạo đơn

#### ❤️ Yêu Thích (AJAX)
```javascript
// POST /restaurants/{slug}/favorite
fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': token } })
  .then(r => r.json())
  .then(data => {
      // Cập nhật icon tim ngay lập tức, không reload trang
      icon.classList.toggle('text-danger', data.favorited);
  });
```

#### 🔍 Tìm Kiếm Thời Gian Thực
- Gõ từ khóa → debounce 400ms → gọi `GET /api/v1/search?q=...`
- Kết quả nhà hàng + món ăn hiện ngay bên dưới thanh tìm kiếm (dropdown)
- Không cần reload trang

#### 📊 Dashboard Admin
- Thống kê tổng quan: Users, Nhà hàng, Đơn hàng, Doanh thu
- Biểu đồ doanh thu theo tháng (sử dụng `Chart.js`)
- Đơn hàng gần nhất (8 đơn)
- Top 5 nhà hàng có rating cao nhất

### 4.5 Bảo Mật

| Cơ chế | Mô tả |
|--------|-------|
| **CSRF Token** | Tất cả form đều có `@csrf`, middleware `VerifyCsrfToken` |
| **Authorization** | Method `ensureAdmin()` trong AdminController; Policy `admin-action` trên các route nhạy cảm |
| **Ownership Check** | `OrderController::show()` kiểm tra `order->user_id === Auth::id()` |
| **Validation** | Server-side validation trên 100% form với custom messages tiếng Việt |
| **Password Hashing** | Bcrypt với 12 rounds (cấu hình trong `.env`) |
| **File Upload** | Validate `mimes:jpeg,png,jpg,webp` và `max:2048` KB |

### 4.6 Cấu Hình Môi Trường (.env)

```env
APP_NAME=ResDeli
APP_ENV=local
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=resdeli
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=local
```

---

## 5. Hướng Dẫn Cài Đặt

### Yêu cầu hệ thống

- PHP ≥ 8.2
- Composer ≥ 2.x
- MySQL/MariaDB ≥ 8.0
- Node.js ≥ 18 + NPM

### Bước cài đặt

**1. Clone project và cài dependencies**
```bash
cd resdeli
composer install
npm install
```

**2. Cấu hình môi trường**
```bash
cp .env.example .env
php artisan key:generate
```

Chỉnh sửa `.env` với thông tin database:
```env
DB_DATABASE=resdeli
DB_USERNAME=root
DB_PASSWORD=
```

**3. Tạo database và migrate**
```bash
# Tạo database trong MySQL
mysql -u root -e "CREATE DATABASE resdeli CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Migrate bảng
php artisan migrate

# (Tùy chọn) Seed dữ liệu mẫu
php artisan db:seed
```

**4. Tạo symbolic link cho storage**
```bash
php artisan storage:link
```

**5. Build assets và chạy server**
```bash
npm run build
php artisan serve
```

Truy cập: **http://localhost:8000**

### Import từ file SQL (thay thế migrate + seed)

```bash
mysql -u root resdeli < database/resdeli_export.sql
```

---

## 6. Cấu Trúc Thư Mục

```
resdeli/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Api/
│   │       │   └── MenuApiController.php    # REST API endpoints
│   │       ├── AdminController.php          # Dashboard + Categories + Users
│   │       ├── AuthController.php           # Login, Register, Logout
│   │       ├── HomeController.php           # Trang chủ
│   │       ├── MenuItemController.php       # CRUD món ăn (admin)
│   │       ├── OrderController.php          # Đặt hàng + quản lý
│   │       ├── ProfileController.php        # Hồ sơ cá nhân
│   │       ├── RestaurantController.php     # Nhà hàng + favorite
│   │       └── ReviewController.php         # Đánh giá
│   └── Models/
│       ├── Category.php
│       ├── Favorite.php
│       ├── MenuItem.php
│       ├── Order.php
│       ├── OrderItem.php
│       ├── Profile.php
│       ├── Restaurant.php
│       ├── Review.php
│       ├── Tag.php
│       └── User.php
├── database/
│   ├── migrations/              # 13 migration files
│   ├── seeders/                 # Database seeders
│   └── resdeli_export.sql       # SQL backup
├── resources/
│   ├── css/app.css
│   ├── js/app.js
│   └── views/                   # Blade templates (20+ views)
├── routes/
│   └── web.php                  # Toàn bộ routing (97 dòng)
├── storage/
│   └── app/public/              # Ảnh upload (restaurants/, menu-items/)
├── .env                         # Cấu hình môi trường
├── composer.json                # PHP dependencies
└── vite.config.js               # Asset bundler config
```

---

## 7. API Reference

Base URL: `http://localhost:8000/api/v1`

### GET `/restaurants`

Lấy danh sách nhà hàng đang hoạt động.

**Query Parameters:**

| Tham số | Kiểu | Mô tả |
|---------|------|-------|
| `search` | string | Tìm theo tên hoặc thành phố |
| `city` | string | Lọc theo thành phố |
| `sort` | string | Sắp xếp: `rating` (mặc định), `delivery_fee`, `delivery_time` |
| `page` | int | Trang (9 items/page) |

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Phở Hà Nội",
      "slug": "pho-ha-noi",
      "city": "Hà Nội",
      "rating": 4.8,
      "delivery_fee": 15000,
      "delivery_time": 25
    }
  ],
  "meta": {
    "total": 20,
    "current_page": 1,
    "last_page": 3
  }
}
```

### GET `/restaurants/{slug}/menu`

Lấy thực đơn của nhà hàng (nhóm theo danh mục).

**Response:**
```json
{
  "restaurant": { "id": 1, "name": "...", "rating": 4.8, "delivery_fee": 15000 },
  "menu": {
    "Món chính": [
      {
        "id": 5,
        "name": "Phở bò tái",
        "price": 65000,
        "sale_price": null,
        "effective_price": 65000,
        "image_url": "http://...",
        "tags": ["Best Seller", "Ngon"],
        "prep_time": 15,
        "is_featured": true
      }
    ]
  }
}
```

### GET `/categories`

Lấy danh sách danh mục đang hoạt động.

**Response:**
```json
{
  "data": [
    { "id": 1, "name": "Món Việt", "slug": "mon-viet", "icon": "🍜" }
  ]
}
```

### GET `/search?q={keyword}`

Tìm kiếm toàn hệ thống (nhà hàng + món ăn).

**Response:**
```json
{
  "restaurants": [
    { "id": 1, "name": "Phở Hà Nội", "slug": "pho-ha-noi", "city": "Hà Nội", "rating": 4.8 }
  ],
  "items": [
    {
      "id": 5,
      "name": "Phở bò tái",
      "restaurant_name": "Phở Hà Nội",
      "restaurant_slug": "pho-ha-noi",
      "effective_price": 65000
    }
  ]
}
```

---

## 8. Tài Khoản Mặc Định

Sau khi seed dữ liệu:

| Vai trò | Email | Mật khẩu |
|---------|-------|----------|
| Admin | admin@resdeli.com | password |
| Customer | customer@resdeli.com | password |

---

## 📸 Màn Hình Chính

| Trang | Mô tả |
|-------|-------|
| **Trang chủ** | Hero banner, tìm kiếm thời gian thực, nhà hàng nổi bật |
| **Chi tiết nhà hàng** | Thực đơn phân nhóm, giỏ hàng AJAX, đánh giá |
| **Giỏ hàng / Checkout** | Xem lại đơn, nhập địa chỉ, chọn thanh toán |
| **Lịch sử đơn hàng** | Danh sách với trạng thái badge màu |
| **Admin Dashboard** | Thống kê, biểu đồ, đơn hàng mới nhất |
| **Admin Quản lý** | CRUD đầy đủ cho tất cả thực thể |

---

## 🏗️ Phát Triển Thêm (Roadmap)

- [ ] Thanh toán trực tuyến (MoMo, VNPay tích hợp thực)
- [ ] Thông báo real-time (WebSocket / Pusher)
- [ ] App mobile (Flutter / React Native)
- [ ] Hệ thống voucher / khuyến mãi
- [ ] Tracking vị trí giao hàng trên bản đồ

---

<div align="center">

**ResDeli** — Đặt món dễ dàng, giao hàng nhanh chóng 🍜

*Xây dựng với ❤️ sử dụng Laravel 12 & MySQL*

</div>
