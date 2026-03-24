<?php
namespace App\Models;

use App\User;
use Eloquent;

class Attendance extends Eloquent
{
    protected $fillable = ['student_id', 'my_class_id', 'section_id', 'date', 'status'];

    public function student()
    {
        return $this->belongsTo(StudentRecord::class, 'student_id', 'user_id');
    }

    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }
}
