<?php
namespace App\Models;

use App\User;
use Eloquent;

class Doubt extends Eloquent
{
    protected $fillable = ['title', 'student_id', 'teacher_id', 'subject_id', 'is_resolved'];
    protected $casts = ['is_resolved' => 'boolean'];

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

    public function messages()
    {
        return $this->hasMany(DoubtMessage::class);
    }
}
