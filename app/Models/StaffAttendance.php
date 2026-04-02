<?php

namespace App\Models;

use App\User;
use Eloquent;

class StaffAttendance extends Eloquent
{
    protected $fillable = ['user_id', 'date', 'status', 'notes'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
