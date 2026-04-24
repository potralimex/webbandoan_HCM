<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'image',
        'is_available',
        'is_featured',
        'prep_time',
        'calories',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'float',
        'sale_price' => 'float',
    ];

    // Many-To-One: belongs to restaurant
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    // Many-To-One: belongs to category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Many-To-Many: has many tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'menu_item_tag');
    }

    // One-To-Many (inverse): has many order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // If stored as absolute URL (seed/demo data), return as-is
            if (preg_match('/^https?:\\/\\//i', $this->image)) {
                return $this->image;
            }
            return asset('storage/' . ltrim($this->image, '/'));
        }
        return 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300&h=200&fit=crop';
    }

    public function getEffectivePriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
