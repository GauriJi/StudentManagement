<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PaymentRecord;
use App\Models\StaffRecord;
use App\Models\StudentRecord;
use App\User;

class DashboardController extends Controller
{
    public function index()
    {
        $d['total_users']    = User::count();
        $d['total_students'] = StudentRecord::count();
        $d['total_staff']    = StaffRecord::count();
        $d['total_teachers'] = User::where('user_type', 'teacher')->count();
        $d['total_admins']   = User::where('user_type', 'admin')->count();

        // Fee stats - amt_paid is the actual column name
        $d['total_fees_collected'] = PaymentRecord::sum('amt_paid') ?? 0;
        $d['recent_payments']      = PaymentRecord::with('payment', 'student')->latest()->take(8)->get();

        // Recent users
        $d['recent_users'] = User::latest()->take(8)->get();

        // Users by type
        $d['users_by_type'] = User::selectRaw('user_type, count(*) as count')
            ->groupBy('user_type')
            ->pluck('count', 'user_type');

        return view('pages.super_admin.dashboard', $d);
    }
}
