<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_item_id',
        'item_name',
        'item_price',
        'quantity',
        'subtotal',
        'notes',
    ];

    protected $casts = [
        'item_price' => 'float',
        'subtotal' => 'float',
    ];

    // Many-To-One: belongs to order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Many-To-One: belongs to menu item
    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
