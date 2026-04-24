<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Restaurant;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // === USERS ===
        $admin = User::create([
            'name' => 'Admin ResDeli',
            'email' => 'admin@resdeli.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '0900000001',
        ]);
        Profile::create([
            'user_id' => $admin->id,
            'address' => '123 Nguyễn Huệ',
            'city' => 'Hồ Chí Minh',
            'bio' => 'Quản trị viên hệ thống ResDeli',
        ]);

        $owner1 = User::create([
            'name' => 'Nguyễn Văn An',
            'email' => 'owner1@resdeli.com',
            'password' => Hash::make('password'),
            'role' => 'restaurant_owner',
            'phone' => '0911111111',
        ]);
        Profile::create([
            'user_id' => $owner1->id,
            'address' => '45 Lê Lợi',
            'city' => 'Hồ Chí Minh',
            'bio' => 'Chủ nhà hàng Phở Hà Nội',
        ]);

        $owner2 = User::create([
            'name' => 'Trần Thị Bích',
            'email' => 'owner2@resdeli.com',
            'password' => Hash::make('password'),
            'role' => 'restaurant_owner',
            'phone' => '0922222222',
        ]);
        Profile::create([
            'user_id' => $owner2->id,
            'address' => '78 Đinh Tiên Hoàng',
            'city' => 'Hà Nội',
            'bio' => 'Chủ nhà hàng Sushi Tokyo',
        ]);

        $customer1 = User::create([
            'name' => 'Lê Văn Cường',
            'email' => 'customer@resdeli.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '0933333333',
        ]);
        Profile::create([
            'user_id' => $customer1->id,
            'address' => '99 Trần Hưng Đạo',
            'city' => 'Hồ Chí Minh',
            'bio' => 'Yêu thích ẩm thực Việt Nam',
        ]);

        $customer2 = User::create([
            'name' => 'Phạm Thị Dung',
            'email' => 'customer2@resdeli.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '0944444444',
        ]);
        Profile::create([
            'user_id' => $customer2->id,
            'address' => '12 Bà Triệu',
            'city' => 'Hà Nội',
        ]);

        // === CATEGORIES ===
        $catVietnamese = Category::create(['name' => 'Món Việt', 'slug' => 'mon-viet', 'icon' => '🍜', 'description' => 'Các món ăn truyền thống Việt Nam']);
        $catJapanese   = Category::create(['name' => 'Đồ Nhật', 'slug' => 'do-nhat', 'icon' => '🍣', 'description' => 'Sushi, Ramen, Tempura']);
        $catPizza      = Category::create(['name' => 'Pizza', 'slug' => 'pizza', 'icon' => '🍕', 'description' => 'Pizza các loại']);
        $catBurger     = Category::create(['name' => 'Burger', 'slug' => 'burger', 'icon' => '🍔', 'description' => 'Burger và fast food']);
        $catDessert    = Category::create(['name' => 'Tráng Miệng', 'slug' => 'trang-mieng', 'icon' => '🍰', 'description' => 'Bánh ngọt, kem, chè']);
        $catDrink      = Category::create(['name' => 'Đồ Uống', 'slug' => 'do-uong', 'icon' => '🧋', 'description' => 'Nước uống, trà sữa, cà phê']);

        // === TAGS ===
        $tagSpicy    = Tag::create(['name' => 'Cay', 'slug' => 'cay', 'color' => '#dc3545']);
        $tagVegan    = Tag::create(['name' => 'Chay', 'slug' => 'chay', 'color' => '#28a745']);
        $tagBestSell = Tag::create(['name' => 'Bán Chạy', 'slug' => 'ban-chay', 'color' => '#fd7e14']);
        $tagNew      = Tag::create(['name' => 'Mới', 'slug' => 'moi', 'color' => '#6f42c1']);
        $tagSale     = Tag::create(['name' => 'Khuyến Mãi', 'slug' => 'khuyen-mai', 'color' => '#e83e8c']);

        // === RESTAURANTS ===
        $r1 = Restaurant::create([
            'owner_id' => $owner1->id, 'name' => 'Phở Hà Nội 1988',
            'slug' => 'pho-ha-noi-1988', 'description' => 'Phở truyền thống Hà Nội với nước dùng hầm từ xương bò trong 8 tiếng, hương vị đậm đà không đâu bằng.',
            'address' => '45 Lê Lợi, Q1', 'city' => 'Hồ Chí Minh',
            'phone' => '0281234567', 'email' => 'pho1988@gmail.com',
            'rating' => 4.7, 'delivery_time' => 25, 'delivery_fee' => 15000,
            'min_order' => 50000, 'is_open' => true, 'is_active' => true,
        ]);

        $r2 = Restaurant::create([
            'owner_id' => $owner2->id, 'name' => 'Sushi Tokyo Premium',
            'slug' => 'sushi-tokyo-premium', 'description' => 'Nhà hàng Nhật Bản chính thống với nguyên liệu nhập khẩu trực tiếp từ Nhật, đầu bếp 10 năm kinh nghiệm.',
            'address' => '78 Đinh Tiên Hoàng, Q3', 'city' => 'Hồ Chí Minh',
            'phone' => '0289876543', 'email' => 'sushitokyo@gmail.com',
            'rating' => 4.5, 'delivery_time' => 35, 'delivery_fee' => 25000,
            'min_order' => 100000, 'is_open' => true, 'is_active' => true,
        ]);

        $r3 = Restaurant::create([
            'owner_id' => $owner1->id, 'name' => 'Pizza Napoli',
            'slug' => 'pizza-napoli', 'description' => 'Pizza phong cách Ý chính thống với lò nướng củi, bột pizza mỏng giòn đặc trưng.',
            'address' => '12 Nguyễn Trãi, Q5', 'city' => 'Hồ Chí Minh',
            'phone' => '0285551234', 'email' => 'pizzanapoli@gmail.com',
            'rating' => 4.3, 'delivery_time' => 40, 'delivery_fee' => 20000,
            'min_order' => 80000, 'is_open' => true, 'is_active' => true,
        ]);

        // === MENU ITEMS ===
        $item1 = MenuItem::create([
            'restaurant_id' => $r1->id, 'category_id' => $catVietnamese->id,
            'name' => 'Phở Bò Tái Chín', 'slug' => 'pho-bo-tai-chin',
            'description' => 'Phở bò với thịt tái và chín, nước dùng hầm xương bò 8 tiếng',
            'price' => 65000, 'is_available' => true, 'is_featured' => true, 'prep_time' => 10, 'calories' => 450,
        ]);
        $item1->tags()->attach([$tagBestSell->id]);

        $item2 = MenuItem::create([
            'restaurant_id' => $r1->id, 'category_id' => $catVietnamese->id,
            'name' => 'Bún Bò Huế', 'slug' => 'bun-bo-hue',
            'description' => 'Bún bò Huế cay nồng đặc trưng với chả cua, giò heo',
            'price' => 70000, 'sale_price' => 60000, 'is_available' => true, 'is_featured' => false, 'prep_time' => 12, 'calories' => 480,
        ]);
        $item2->tags()->attach([$tagSpicy->id, $tagSale->id]);

        $item3 = MenuItem::create([
            'restaurant_id' => $r1->id, 'category_id' => $catDrink->id,
            'name' => 'Trà Đá Thái', 'slug' => 'tra-da-thai',
            'description' => 'Trà thái đỏ với sữa đặc pha chế đặc biệt',
            'price' => 25000, 'is_available' => true, 'prep_time' => 5, 'calories' => 120,
        ]);
        $item3->tags()->attach([$tagNew->id]);

        $item4 = MenuItem::create([
            'restaurant_id' => $r2->id, 'category_id' => $catJapanese->id,
            'name' => 'Set Sushi Salmon 8 Miếng', 'slug' => 'set-sushi-salmon-8-mieng',
            'description' => 'Sushi cá hồi tươi nhập khẩu từ Na Uy, 8 miếng kèm gừng và wasabi',
            'price' => 185000, 'is_available' => true, 'is_featured' => true, 'prep_time' => 15, 'calories' => 320,
        ]);
        $item4->tags()->attach([$tagBestSell->id, $tagNew->id]);

        $item5 = MenuItem::create([
            'restaurant_id' => $r2->id, 'category_id' => $catJapanese->id,
            'name' => 'Ramen Tonkotsu', 'slug' => 'ramen-tonkotsu',
            'description' => 'Mì ramen nước lèo xương heo hầm 12 tiếng, topping trứng onsen, thịt char siu',
            'price' => 145000, 'is_available' => true, 'is_featured' => true, 'prep_time' => 20, 'calories' => 680,
        ]);
        $item5->tags()->attach([$tagBestSell->id]);

        $item6 = MenuItem::create([
            'restaurant_id' => $r2->id, 'category_id' => $catJapanese->id,
            'name' => 'Tempura Tôm (6 Chiếc)', 'slug' => 'tempura-tom-6-chiec',
            'description' => 'Tôm tempura chiên giòn với bột đặc biệt, ăn kèm sốt tsuyu',
            'price' => 120000, 'sale_price' => 99000, 'is_available' => true, 'prep_time' => 18, 'calories' => 380,
        ]);
        $item6->tags()->attach([$tagSale->id]);

        $item7 = MenuItem::create([
            'restaurant_id' => $r3->id, 'category_id' => $catPizza->id,
            'name' => 'Pizza Margherita', 'slug' => 'pizza-margherita',
            'description' => 'Pizza truyền thống với sốt cà chua, mozzarella tươi, lá basil',
            'price' => 179000, 'is_available' => true, 'is_featured' => true, 'prep_time' => 25, 'calories' => 800,
        ]);
        $item7->tags()->attach([$tagVegan->id, $tagBestSell->id]);

        $item8 = MenuItem::create([
            'restaurant_id' => $r3->id, 'category_id' => $catPizza->id,
            'name' => 'Pizza Pepperoni', 'slug' => 'pizza-pepperoni',
            'description' => 'Pizza với pepperoni Mỹ và phô mai mozzarella kéo dài',
            'price' => 209000, 'is_available' => true, 'prep_time' => 25, 'calories' => 950,
        ]);
        $item8->tags()->attach([$tagBestSell->id, $tagSpicy->id]);

        $item9 = MenuItem::create([
            'restaurant_id' => $r3->id, 'category_id' => $catDessert->id,
            'name' => 'Tiramisu', 'slug' => 'tiramisu',
            'description' => 'Bánh tiramisu Ý với mascarpone và cà phê espresso',
            'price' => 75000, 'is_available' => true, 'prep_time' => 5, 'calories' => 380,
        ]);
        $item9->tags()->attach([$tagNew->id]);

        // === ORDERS ===
        $order1 = Order::create([
            'user_id' => $customer1->id, 'restaurant_id' => $r1->id,
            'order_number' => 'RD-' . strtoupper(uniqid()),
            'status' => 'delivered', 'subtotal' => 155000,
            'delivery_fee' => 15000, 'total' => 170000,
            'delivery_address' => '99 Trần Hưng Đạo, Q1, TP.HCM',
            'phone' => '0933333333', 'payment_method' => 'cash', 'payment_status' => 'paid',
        ]);
        OrderItem::create(['order_id' => $order1->id, 'menu_item_id' => $item1->id, 'item_name' => $item1->name, 'item_price' => $item1->price, 'quantity' => 2, 'subtotal' => 130000]);
        OrderItem::create(['order_id' => $order1->id, 'menu_item_id' => $item3->id, 'item_name' => $item3->name, 'item_price' => $item3->price, 'quantity' => 1, 'subtotal' => 25000]);

        $order2 = Order::create([
            'user_id' => $customer1->id, 'restaurant_id' => $r2->id,
            'order_number' => 'RD-' . strtoupper(uniqid()),
            'status' => 'pending', 'subtotal' => 330000,
            'delivery_fee' => 25000, 'total' => 355000,
            'delivery_address' => '99 Trần Hưng Đạo, Q1, TP.HCM',
            'phone' => '0933333333', 'notes' => 'Ít wasabi', 'payment_method' => 'momo', 'payment_status' => 'paid',
        ]);
        OrderItem::create(['order_id' => $order2->id, 'menu_item_id' => $item4->id, 'item_name' => $item4->name, 'item_price' => $item4->price, 'quantity' => 1, 'subtotal' => 185000]);
        OrderItem::create(['order_id' => $order2->id, 'menu_item_id' => $item5->id, 'item_name' => $item5->name, 'item_price' => $item5->price, 'quantity' => 1, 'subtotal' => 145000]);

        // === REVIEWS ===
        Review::create([
            'user_id' => $customer1->id, 'restaurant_id' => $r1->id,
            'order_id' => $order1->id, 'rating' => 5,
            'comment' => 'Phở rất ngon, nước dùng đậm đà, giao hàng nhanh. Sẽ đặt lại!',
            'is_approved' => true,
        ]);
        Review::create([
            'user_id' => $customer2->id, 'restaurant_id' => $r1->id,
            'rating' => 4, 'comment' => 'Phở ngon nhưng hơi ít thịt. Nước dùng rất chuẩn vị Hà Nội.',
            'is_approved' => true,
        ]);
        Review::create([
            'user_id' => $customer2->id, 'restaurant_id' => $r2->id,
            'rating' => 5, 'comment' => 'Sushi tuyệt vời! Cá hồi rất tươi, không có mùi, tan trong miệng.',
            'is_approved' => true,
        ]);

        // Update ratings
        $r1->updateRating();
        $r2->updateRating();

        // === FAVORITES ===
        $customer1->favorites()->attach([$r1->id, $r2->id]);
        $customer2->favorites()->attach([$r1->id, $r3->id]);

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('Admin: admin@resdeli.com / password');
        $this->command->info('Owner: owner1@resdeli.com / password');
        $this->command->info('Customer: customer@resdeli.com / password');
    }
}
