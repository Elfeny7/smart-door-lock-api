<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'pin',
        'phone',
        'email',
    ];

    public function doors()
    {
        return $this->belongsToMany(Door::class, 'user_door')->using(UserDoor::class);
    }
}
