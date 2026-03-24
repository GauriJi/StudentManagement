<?php

namespace App\Models;

use App\User;
use Eloquent;

class TeacherNotification extends Eloquent
{
    protected $fillable = ['teacher_id', 'title', 'message', 'type', 'is_read', 'sender_id'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
