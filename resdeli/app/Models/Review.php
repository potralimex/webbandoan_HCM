<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'restaurant_id',
        'order_id',
        'rating',
        'comment',
        'image',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'rating' => 'integer',
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

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getStarsAttribute()
    {
        $filled = str_repeat('★', $this->rating);
        $empty = str_repeat('☆', 5 - $this->rating);
        return $filled . $empty;
    }
}
