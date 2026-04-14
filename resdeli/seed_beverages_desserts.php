<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use App\Models\User;
use App\Models\Profile;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\MenuItem;

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

echo "🚀 Bắt đầu seed nhà hàng Đồ uống & Tráng miệng...\n";

try {
    // Tạo user cho nhà hàng
    $user = User::firstOrCreate(
        ['email' => 'beverages@example.com'],
        [
            'name' => 'Beverage Corner',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]
    );

    // Tạo profile
    $profile = Profile::firstOrCreate(
        ['user_id' => $user->id],
        [
            'phone' => '0123456789',
            'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&h=200&fit=crop',
        ]
    );

    // Tạo nhà hàng
    $restaurant = Restaurant::firstOrCreate(
        ['slug' => 'beverage-dessert-corner'],
        [
            'name' => 'Beverage & Dessert Corner',
            'description' => 'Nhà hàng chuyên về đồ uống và tráng miệng ngon miệng',
            'address' => '123 Đường ABC, Quận 1',
            'city' => 'TP.HCM',
            'phone' => '0123456789',
            'email' => 'beverages@example.com',
            'image_url' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&h=400&fit=crop',
            'rating' => 4.8,
            'delivery_time' => 15,
            'delivery_fee' => 15000,
            'min_order' => 50000,
            'is_open' => true,
            'owner_id' => $user->id,
        ]
    );

    echo "✅ Đã tạo nhà hàng: {$restaurant->name}\n";

    // Lấy categories
    $drinkCategory = Category::where('name', 'Đồ uống')->first();
    $dessertCategory = Category::where('name', 'Bánh ngọt')->first();

    if (!$drinkCategory) {
        $drinkCategory = Category::create(['name' => 'Đồ uống']);
    }
    if (!$dessertCategory) {
        $dessertCategory = Category::create(['name' => 'Bánh ngọt']);
    }

    // Menu items cho Đồ uống
    $drinks = [
        [
            'name' => 'Cà phê đen đá',
            'description' => 'Cà phê đen pha đá, đậm đà hương vị',
            'price' => 25000,
            'image_url' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=200&h=200&fit=crop',
            'calories' => 5,
            'prep_time' => 5,
        ],
        [
            'name' => 'Trà sữa trân châu',
            'description' => 'Trà sữa với trân châu dai ngon',
            'price' => 35000,
            'image_url' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=200&h=200&fit=crop',
            'calories' => 250,
            'prep_time' => 10,
        ],
        [
            'name' => 'Sinh tố bơ',
            'description' => 'Sinh tố bơ tươi ngon, bổ dưỡng',
            'price' => 40000,
            'image_url' => 'https://images.unsplash.com/photo-1571771019784-3ff35f4f4277?w=200&h=200&fit=crop',
            'calories' => 180,
            'prep_time' => 8,
        ],
        [
            'name' => 'Nước ép cam',
            'description' => 'Nước ép cam tươi 100%',
            'price' => 30000,
            'image_url' => 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=200&h=200&fit=crop',
            'calories' => 120,
            'prep_time' => 5,
        ],
    ];

    foreach ($drinks as $drink) {
        MenuItem::firstOrCreate(
            [
                'restaurant_id' => $restaurant->id,
                'name' => $drink['name'],
            ],
            array_merge($drink, [
                'slug' => $restaurant->id . '-' . \Illuminate\Support\Str::slug($drink['name']),
                'category_id' => $drinkCategory->id,
                'is_available' => true,
            ])
        );
    }

    echo "✅ Đã thêm 4 món đồ uống\n";

    // Menu items cho Bánh ngọt
    $desserts = [
        [
            'name' => 'Tiramisu',
            'description' => 'Bánh tiramisu Ý truyền thống',
            'price' => 45000,
            'image_url' => 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?w=200&h=200&fit=crop',
            'calories' => 320,
            'prep_time' => 0,
        ],
        [
            'name' => 'Bánh cheesecake',
            'description' => 'Cheesecake phô mai mềm mịn',
            'price' => 50000,
            'image_url' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=200&h=200&fit=crop',
            'calories' => 280,
            'prep_time' => 0,
        ],
        [
            'name' => 'Bánh mousse chocolate',
            'description' => 'Bánh mousse sô-cô-la tan chảy',
            'price' => 55000,
            'image_url' => 'https://images.unsplash.com/photo-1606312619070-d48b4c652a52?w=200&h=200&fit=crop',
            'calories' => 350,
            'prep_time' => 0,
        ],
        [
            'name' => 'Bánh tart trái cây',
            'description' => 'Bánh tart với nhiều loại trái cây tươi',
            'price' => 48000,
            'image_url' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=200&h=200&fit=crop',
            'calories' => 290,
            'prep_time' => 0,
        ],
    ];

    foreach ($desserts as $dessert) {
        MenuItem::firstOrCreate(
            [
                'restaurant_id' => $restaurant->id,
                'name' => $dessert['name'],
            ],
            array_merge($dessert, [
                'slug' => $restaurant->id . '-' . \Illuminate\Support\Str::slug($dessert['name']),
                'category_id' => $dessertCategory->id,
                'is_available' => true,
            ])
        );
    }

    echo "✅ Đã thêm 4 món tráng miệng\n";

    echo "🎉 Hoàn thành! Đã tạo nhà hàng '{$restaurant->name}' với 8 món ăn (4 đồ uống + 4 tráng miệng).\n";

} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}