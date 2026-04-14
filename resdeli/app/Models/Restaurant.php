<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'description',
        'address',
        'city',
        'phone',
        'email',
        'image',
        'rating',
        'delivery_time',
        'delivery_fee',
        'min_order',
        'is_open',
        'is_active',
        'open_time',
        'close_time',
    ];

    protected $casts = [
        'is_open' => 'boolean',
        'is_active' => 'boolean',
        'rating' => 'float',
        'delivery_fee' => 'float',
        'min_order' => 'float',
    ];

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Many-To-Many: users who favorited this restaurant
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=600&h=400&fit=crop';
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function updateRating()
    {
        $avg = $this->reviews()->where('is_approved', true)->avg('rating');
        $this->update(['rating' => $avg ?? 0]);
    }
}
