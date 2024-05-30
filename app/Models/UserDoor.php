<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserDoor extends Pivot
{
    protected $table = 'user_door';
    protected $fillable = [
        'user_id',
        'door_id',
    ];
}
