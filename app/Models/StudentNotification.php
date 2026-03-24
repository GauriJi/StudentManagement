<?php

namespace App\Models;

use App\User;
use Eloquent;

class StudentNotification extends Eloquent
{
    protected $fillable = ['student_id', 'from_id', 'type', 'title', 'message', 'is_read', 'session'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'from_id');
    }
}
