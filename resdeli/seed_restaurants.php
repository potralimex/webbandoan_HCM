<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Profile;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Hash;

// Tạo owner mới
$owners = [];
for ($i = 3; $i <= 5; $i++) {
    $owner = User::firstOrCreate(
        ['email' => 'owner' . $i . '@resdeli.com'],
        [
            'name' => 'Chủ Nhà Hàng ' . $i,
            'password' => Hash::make('password'),
            'role' => 'restaurant_owner',
            'phone' => '091111111' . $i,
        ]
    );
    Profile::firstOrCreate(
        ['user_id' => $owner->id],
        [
            'address' => str_pad($i, 3, '0', STR_PAD_LEFT) . ' Đường ' . $i,
            'city' => 'Hồ Chí Minh',
        ]
    );
    $owners[] = $owner->id;
}

// Categories
$categories = [
    'Pizza' => '#FF6B35',
    'Burger' => '#F4A261',
    'Bánh ngọt' => '#E76F51',
    'Đồ uống' => '#2A9D8F',
];

$cats = [];
foreach ($categories as $name => $color) {
    $cat = Category::firstOrCreate(
        ['name' => $name],
        ['slug' => \Illuminate\Support\Str::slug($name), 'color' => $color]
    );
    $cats[$name] = $cat->id;
}

// Nhà hàng 1: Pizza Place
$r1 = Restaurant::create([
    'name' => 'Pizza Italia Express',
    'slug' => 'pizza-italia-express-' . time(),
    'description' => 'Pizza Italy nước ngoài chất lượng cao',
    'address' => '123 Nguyễn Hữu Cảnh',
    'city' => 'Hồ Chí Minh',
    'phone' => '0288765432',
    'owner_id' => $owners[0],
    'image' => 'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=400&h=300&fit=crop',
    'delivery_time' => 30,
    'delivery_fee' => 25000,
    'min_order' => 100000,
    'rating' => 4.7,
    'is_active' => 1,
    'is_open' => 1,
    'open_time' => '10:00',
    'close_time' => '22:00',
]);

MenuItem::create(['restaurant_id' => $r1->id, 'category_id' => $cats['Pizza'], 'name' => 'Pizza Margherita', 'slug' => 'r' . $r1->id . '-pizza-margherita', 'description' => 'Phô mai mozzarella, cà chua tươi', 'price' => 120000, 'image' => 'https://images.unsplash.com/photo-1604068549290-dea0e4a305ca?w=200&h=200&fit=crop', 'is_available' => 1]);
MenuItem::create(['restaurant_id' => $r1->id, 'category_id' => $cats['Pizza'], 'name' => 'Pizza Pepperoni', 'slug' => 'r' . $r1->id . '-pizza-pepperoni', 'description' => 'Pepperoni, phô mai, cà chua', 'price' => 140000, 'image' => 'https://images.unsplash.com/photo-1628840042765-356cda07f4ee?w=200&h=200&fit=crop', 'is_available' => 1]);
MenuItem::create(['restaurant_id' => $r1->id, 'category_id' => $cats['Pizza'], 'name' => 'Pizza Quattro Formaggi', 'slug' => 'r' . $r1->id . '-pizza-quattro-formaggi', 'description' => '4 loại phô mai', 'price' => 150000, 'image' => 'https://images.unsplash.com/photo-1614049162082-403d27bf5aa5?w=200&h=200&fit=crop', 'is_available' => 1]);
MenuItem::create(['restaurant_id' => $r1->id, 'category_id' => $cats['Đồ uống'], 'name' => 'Coca Cola', 'slug' => 'r' . $r1->id . '-coca-cola', 'description' => 'Nước ngọt lạnh', 'price' => 15000, 'image' => 'https://images.unsplash.com/photo-1554866585-ae5d3b1d2f70?w=200&h=200&fit=crop', 'is_available' => 1]);

// Nhà hàng 2: Burger House
$r2 = Restaurant::create([
    'name' => 'Burger House Premium',
    'slug' => 'burger-house-premium-' . time(),
    'description' => 'Burger bò Mỹ đơn thuần thiccc',
    'address' => '456 Đại Lộ Võ Văn Kiệt',
    'city' => 'Hồ Chí Minh',
    'phone' => '0288765433',
    'owner_id' => $owners[1],
    'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&h=300&fit=crop',
    'delivery_time' => 25,
    'delivery_fee' => 20000,
    'min_order' => 80000,
    'rating' => 4.6,
    'is_active' => 1,
    'is_open' => 1,
    'open_time' => '10:30',
    'close_time' => '23:00',
]);

MenuItem::create(['restaurant_id' => $r2->id, 'category_id' => $cats['Burger'], 'name' => 'Classic Burger', 'slug' => 'r' . $r2->id . '-classic-burger', 'description' => 'Thịt bò, pho mai, hành', 'price' => 95000, 'image' => 'https://images.unsplash.com/photo-1553979459-d2229ba7433b?w=200&h=200&fit=crop', 'is_available' => 1]);
MenuItem::create(['restaurant_id' => $r2->id, 'category_id' => $cats['Burger'], 'name' => 'Double Cheese Burger', 'slug' => 'r' . $r2->id . '-double-cheese-burger', 'description' => 'Thịt bò x2, pho mai x2', 'price' => 125000, 'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=200&h=200&fit=crop', 'is_available' => 1]);
MenuItem::create(['restaurant_id' => $r2->id, 'category_id' => $cats['Burger'], 'name' => 'Deluxe Burger', 'slug' => 'r' . $r2->id . '-deluxe-burger', 'description' => 'Thịt bò, pho mai, dưa chuột, cà chua, hành', 'price' => 145000, 'image' => 'https://images.unsplash.com/photo-1550547660-d9450f859349?w=200&h=200&fit=crop', 'is_available' => 1]);
MenuItem::create(['restaurant_id' => $r2->id, 'category_id' => $cats['Đồ uống'], 'name' => 'Pepsi', 'slug' => 'r' . $r2->id . '-pepsi', 'description' => 'Soda lạnh', 'price' => 15000, 'image' => 'https://images.unsplash.com/photo-1601614467723-8d996450e126?w=200&h=200&fit=crop', 'is_available' => 1]);

// Nhà hàng 3: Dessert Palace
$r3 = Restaurant::create([
    'name' => 'Dessert Palace',
    'slug' => 'dessert-palace-' . time(),
    'description' => 'Bánh ngọt, ice cream bá đạo',
    'address' => '789 Lê Văn Sỹ',
    'city' => 'Hồ Chí Minh',
    'phone' => '0288765434',
    'owner_id' => $owners[2],
    'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=400&h=300&fit=crop',
    'delivery_time' => 20,
    'delivery_fee' => 15000,
    'min_order' => 60000,
    'rating' => 4.8,
    'is_active' => 1,
    'is_open' => 1,
    'open_time' => '11:00',
    'close_time' => '21:00',
]);

MenuItem::create(['restaurant_id' => $r3->id, 'category_id' => $cats['Bánh ngọt'], 'name' => 'Bánh Chocolate Fondant', 'slug' => 'r' . $r3->id . '-banh-chocolate-fondant', 'description' => 'Bánh sô cô la nóng chảy', 'price' => 65000, 'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=200&h=200&fit=crop', 'is_available' => 1]);
MenuItem::create(['restaurant_id' => $r3->id, 'category_id' => $cats['Bánh ngọt'], 'name' => 'Bánh Tiramisu', 'slug' => 'r' . $r3->id . '-banh-tiramisu', 'description' => 'Bánh ý truyền thống', 'price' => 75000, 'image' => 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?w=200&h=200&fit=crop', 'is_available' => 1]);
MenuItem::create(['restaurant_id' => $r3->id, 'category_id' => $cats['Bánh ngọt'], 'name' => 'Bánh Cheesecake', 'slug' => 'r' . $r3->id . '-banh-cheesecake', 'description' => 'Bánh pho mai mềm', 'price' => 70000, 'image' => 'https://images.unsplash.com/photo-1553134760-cd4628902175?w=200&h=200&fit=crop', 'is_available' => 1]);
MenuItem::create(['restaurant_id' => $r3->id, 'category_id' => $cats['Đồ uống'], 'name' => 'Iced Coffee', 'slug' => 'r' . $r3->id . '-iced-coffee', 'description' => 'Cà phê đá thơm ngon', 'price' => 25000, 'image' => 'https://images.unsplash.com/photo-1517668808822-9ebb02ae2a0e?w=200&h=200&fit=crop', 'is_available' => 1]);

echo "✅ Đã seed xong! Thêm 3 nhà hàng mới với tổng 12 đơn vị menu items.\n";
echo "Pizza Italia Express: 4 items\n";
echo "Burger House Premium: 4 items\n";
echo "Dessert Palace: 4 items\n";
