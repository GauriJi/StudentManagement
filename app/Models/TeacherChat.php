<?php

namespace App\Models;

use App\User;
use Eloquent;

class TeacherChat extends Eloquent
{
    protected $fillable = ['student_id', 'teacher_id', 'message', 'sender_type', 'is_read'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
