<?php

namespace App\Models;

use App\User;
use Eloquent;

class ParentNotification extends Eloquent
{
    protected $fillable = ['parent_id', 'sender_id', 'type', 'title', 'message', 'is_read'];

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
