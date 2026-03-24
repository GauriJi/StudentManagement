<?php

namespace App\Models;

use App\User;
use Eloquent;

class AcademicCalendar extends Eloquent
{
    protected $fillable = ['title', 'type', 'start_date', 'end_date', 'description', 'color', 'created_by'];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getBadgeColorAttribute()
    {
        return [
            'holiday' => '#e74c3c',
            'event'   => '#3498db',
            'exam'    => '#f39c12',
            'notice'  => '#27ae60',
        ][$this->type] ?? '#6c757d';
    }
}
