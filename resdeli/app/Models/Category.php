<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // One-To-Many: category has many menu items
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
