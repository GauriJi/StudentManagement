<?php

namespace App\Models;

use App\User;
use Eloquent;

class Expense extends Eloquent
{
    protected $fillable = ['title', 'category', 'amount', 'expense_date', 'description', 'ref_no', 'recorded_by'];

    protected $casts = ['expense_date' => 'date'];

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
