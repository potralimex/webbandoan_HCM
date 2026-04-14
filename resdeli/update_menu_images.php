<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use App\Models\MenuItem;

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

echo "🔄 Bắt đầu cập nhật ảnh món ăn cho Beverage & Dessert Corner...\n";

echo "🔄 Bắt đầu cập nhật ảnh cho TẤT CẢ món ăn trong hệ thống...\n";

try {
    // Map ảnh cho các món ăn phổ biến
    $imageMap = [
        // Pizza
        'Pizza Margherita' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=200&h=200&fit=crop',
        'Pizza Pepperoni' => 'https://images.unsplash.com/photo-1628840042765-356cda07504e?w=200&h=200&fit=crop',
        'Pizza Hải sản' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=200&h=200&fit=crop',
        'Pizza Rau củ' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=200&h=200&fit=crop',

        // Burger
        'Burger Bò Phô Mai' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=200&h=200&fit=crop',
        'Burger Gà' => 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=200&h=200&fit=crop',
        'Burger Cá' => 'https://images.unsplash.com/photo-1551782450-17144efb5723?w=200&h=200&fit=crop',
        'Burger Chay' => 'https://images.unsplash.com/photo-1550317138-10000687a4d0?w=200&h=200&fit=crop',

        // Đồ uống
        'Cà phê đen đá' => 'https://images.unsplash.com/photo-1497935586351-b67a49e012bf?w=200&h=200&fit=crop',
        'Trà sữa trân châu' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=200&h=200&fit=crop',
        'Sinh tố bơ' => 'https://images.unsplash.com/photo-1571771019784-3ff35f4f4277?w=200&h=200&fit=crop',
        'Nước ép cam' => 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=200&h=200&fit=crop',

        // Tráng miệng
        'Tiramisu' => 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?w=200&h=200&fit=crop',
        'Bánh cheesecake' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=200&h=200&fit=crop',
        'Bánh mousse chocolate' => 'https://images.unsplash.com/photo-1606312619070-d48b4c652a52?w=200&h=200&fit=crop',
        'Bánh tart trái cây' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=200&h=200&fit=crop',
    ];

    $updatedCount = 0;
    $totalItems = MenuItem::count();

    foreach ($imageMap as $name => $imageUrl) {
        $updated = MenuItem::where('name', $name)->update(['image' => $imageUrl]);
        if ($updated > 0) {
            echo "✅ Đã cập nhật ảnh cho: {$name} ({$updated} món)\n";
            $updatedCount += $updated;
        }
    }

    // Cho các món không có trong map, dùng ảnh mặc định theo category
    $categoryImages = [
        1 => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=200&h=200&fit=crop', // Pizza
        2 => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=200&h=200&fit=crop', // Burger
        3 => 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?w=200&h=200&fit=crop', // Bánh ngọt
        4 => 'https://images.unsplash.com/photo-1497935586351-b67a49e012bf?w=200&h=200&fit=crop', // Đồ uống
    ];

    $itemsWithoutImage = MenuItem::whereNull('image')->orWhere('image', '')->get();

    foreach ($itemsWithoutImage as $item) {
        $categoryImage = $categoryImages[$item->category_id] ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=200&h=200&fit=crop';
        $item->update(['image' => $categoryImage]);
        $updatedCount++;
    }

    if ($itemsWithoutImage->count() > 0) {
        echo "✅ Đã cập nhật ảnh mặc định cho {$itemsWithoutImage->count()} món không có ảnh\n";
    }

    echo "🎉 Hoàn thành! Đã cập nhật ảnh cho {$updatedCount}/{$totalItems} món ăn trong hệ thống.\n";

} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}