<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Door extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'class_name',
        'description',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_door')->using(UserDoor::class);
    }
}
