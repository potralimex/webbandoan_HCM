<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\MenuItem;
use App\Models\Restaurant;

echo "Total menu items: " . MenuItem::count() . "\n";
$restaurants = Restaurant::where('is_active', 1)->get();
foreach($restaurants as $r) {
    $total = $r->menuItems()->count();
    $available = $r->menuItems()->where('is_available', 1)->count();
    echo "Restaurant: " . $r->name . " - Items: " . $total . " (available: " . $available . ")\n";
}
