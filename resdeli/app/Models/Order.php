<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'restaurant_id',
        'order_number',
        'status',
        'subtotal',
        'delivery_fee',
        'total',
        'delivery_address',
        'phone',
        'notes',
        'payment_method',
        'payment_status',
    ];

    protected $casts = [
        'subtotal' => 'float',
        'delivery_fee' => 'float',
        'total' => 'float',
    ];

    // Many-To-One: belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Many-To-One: belongs to restaurant
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    // One-To-Many: has many order items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending'    => '<span class="badge bg-warning">Chờ xác nhận</span>',
            'confirmed'  => '<span class="badge bg-info">Đã xác nhận</span>',
            'preparing'  => '<span class="badge bg-primary">Đang chuẩn bị</span>',
            'delivering' => '<span class="badge bg-purple">Đang giao</span>',
            'delivered'  => '<span class="badge bg-success">Đã giao</span>',
            'cancelled'  => '<span class="badge bg-danger">Đã hủy</span>',
            default      => '<span class="badge bg-secondary">Không xác định</span>',
        };
    }

    public static function generateOrderNumber()
    {
        return 'RD-' . strtoupper(uniqid());
    }
}
