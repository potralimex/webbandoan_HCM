<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateMenuItemImagesSeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            // Phở Bò Tái Chín
            1  => 'https://images.unsplash.com/photo-1582878826629-29b7ad1cdc43?w=400&h=300&fit=crop',
            // Bún Bò Huế
            2  => 'https://images.unsplash.com/photo-1569050467447-ce54b3bbc37d?w=400&h=300&fit=crop',
            // Trà Đá Thái
            3  => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400&h=300&fit=crop',
            // Set Sushi Salmon 8 Miếng
            4  => 'https://images.unsplash.com/photo-1617196034183-421b4040ed20?w=400&h=300&fit=crop',
            // Ramen Tonkotsu
            5  => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=400&h=300&fit=crop',
            // Tempura Tôm
            6  => 'https://images.unsplash.com/photo-1615361200141-f45040f367be?w=400&h=300&fit=crop',
            // Pizza Margherita (id 7)
            7  => 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=400&h=300&fit=crop',
            // Pizza Pepperoni (id 8)
            8  => 'https://images.unsplash.com/photo-1628840042765-356cda07504e?w=400&h=300&fit=crop',
            // Tiramisu
            9  => 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?w=400&h=300&fit=crop',
            // Pizza Margherita (id 11)
            11 => 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=400&h=300&fit=crop',
            // Pizza Pepperoni (id 12)
            12 => 'https://images.unsplash.com/photo-1628840042765-356cda07504e?w=400&h=300&fit=crop',
            // Pizza Quattro Formaggi
            13 => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400&h=300&fit=crop',
            // Coca Cola
            14 => 'https://images.unsplash.com/photo-1554866585-cd94860890b7?w=400&h=300&fit=crop',
            // Classic Burger
            15 => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&h=300&fit=crop',
            // Double Cheese Burger
            16 => 'https://images.unsplash.com/photo-1553979459-d2229ba7433b?w=400&h=300&fit=crop',
            // Deluxe Burger
            17 => 'https://images.unsplash.com/photo-1550547660-d9450f859349?w=400&h=300&fit=crop',
            // Pepsi
            18 => 'https://images.unsplash.com/photo-1629203851122-3726ecdf080e?w=400&h=300&fit=crop',
            // Iced Coffee
            22 => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=400&h=300&fit=crop',
            // Cà phê đen đá
            23 => 'https://images.unsplash.com/photo-1497935586351-b67a49e012bf?w=400&h=300&fit=crop',
            // Trà sữa trân châu
            24 => 'https://images.unsplash.com/photo-1558857563-b371033873b8?w=400&h=300&fit=crop',
            // Sinh tố bơ
            25 => 'https://images.unsplash.com/photo-1623065422902-30a2d299bbe4?w=400&h=300&fit=crop',
            // Nước ép cam
            26 => 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=400&h=300&fit=crop',
            // Pancake
            32 => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=400&h=300&fit=crop',
        ];

        foreach ($images as $id => $url) {
            DB::table('menu_items')->where('id', $id)->update(['image' => $url]);
        }

        $this->command->info('✅ Đã cập nhật ảnh cho ' . count($images) . ' món ăn!');
    }
}
