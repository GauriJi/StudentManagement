<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolTimetable extends Model
{
    protected $table = 'school_timetable';

    protected $fillable = [
        'my_class_id', 'section_id', 'subject_id', 'teacher_id',
        'day', 'period_no', 'time_from', 'time_to', 'session',
    ];

    public function my_class()  { return $this->belongsTo(MyClass::class, 'my_class_id'); }
    public function section()   { return $this->belongsTo(Section::class, 'section_id'); }
    public function subject()   { return $this->belongsTo(Subject::class, 'subject_id'); }
    public function teacher()   { return $this->belongsTo(\App\User::class, 'teacher_id'); }
}
