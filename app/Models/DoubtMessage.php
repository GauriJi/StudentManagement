<?php
namespace App\Models;

use App\User;
use Eloquent;

class DoubtMessage extends Eloquent
{
    protected $fillable = ['doubt_id', 'user_id', 'message'];

    public function doubt()
    {
        return $this->belongsTo(Doubt::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
