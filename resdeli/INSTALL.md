# Hướng dẫn cài đặt ResDeli

## Yêu cầu hệ thống
- PHP >= 8.2
- MySQL / MariaDB (XAMPP)
- Composer

---

## Các bước cài đặt

### Bước 1: Import Database
1. Mở `http://localhost/phpmyadmin`
2. Tạo database mới tên **`resdeli`**
3. Chọn database `resdeli` → tab **Import**
4. Chọn file `database/resdeli_export.sql` → **Go**

### Bước 2: Cấu hình môi trường
Mở file `.env`, kiểm tra thông tin database:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=resdeli
DB_USERNAME=root
DB_PASSWORD=
```
> Nếu chưa có file `.env`, copy `.env.example` → `.env`

### Bước 3: Cài đặt dependencies
```bash
composer install
```

### Bước 4: Tạo application key
```bash
php artisan key:generate
```

### Bước 5: Tạo link thư mục storage (để ảnh hiển thị)
```bash
php artisan storage:link
```

### Bước 6: Chạy server
```bash
php artisan serve
```

Truy cập: **http://localhost:8000**

---

## Tài khoản demo

| Vai trò | Email | Mật khẩu |
|---------|-------|----------|
| 👑 Admin | admin@resdeli.com | password |
| 🏪 Chủ nhà hàng | owner1@resdeli.com | password |
| 🛒 Khách hàng | customer@resdeli.com | password |

---

## Lưu ý
- Đảm bảo **XAMPP đã bật MySQL** trước khi chạy
- Nếu MySQL dùng mật khẩu, điền vào `DB_PASSWORD` trong `.env`
- Ảnh upload được lưu trong `storage/app/public/`
