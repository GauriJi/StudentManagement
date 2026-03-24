<?php

namespace App\Models;

use App\User;
use Eloquent;

class Assignment extends Eloquent
{
    protected $fillable = [
        'title', 
        'description', 
        'file_path', 
        'my_class_id', 
        'section_id', 
        'subject_id', 
        'teacher_id', 
        'due_date'
    ];

    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
