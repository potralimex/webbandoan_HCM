<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'color'];

    // Many-To-Many: tag belongs to many menu items
    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'menu_item_tag');
    }
}
