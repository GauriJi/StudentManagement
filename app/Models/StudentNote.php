<?php

namespace App\Models;

use App\User;
use Eloquent;

class StudentNote extends Eloquent
{
    protected $fillable = ['student_id', 'teacher_id', 'subject_id', 'title', 'description', 'file_path', 'session'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
