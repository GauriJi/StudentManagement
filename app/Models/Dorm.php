<?php

namespace App\Models;

use Eloquent;

class Dorm extends Eloquent
{
    protected $fillable = ['name', 'description'];

    public function student_records()
    {
        return $this->hasMany(StudentRecord::class);
    }
}
