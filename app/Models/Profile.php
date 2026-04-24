<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'city',
        'date_of_birth',
        'bio',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    // One-To-One (inverse)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
