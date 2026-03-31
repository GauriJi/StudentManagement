<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\MyClass;
use App\Models\PaymentRecord;
use App\Models\StaffRecord;
use App\Models\StudentRecord;
use App\User;

class DashboardController extends Controller
{
    /**
     * Returns a numeric sort key so classes order as:
     * NUR(0) → LKG(1) → UKG(2) → Class 1(3) → … → Class 12(14)
     */
    private function classOrder(string $name): int
    {
        $map = ['nur' => 0, 'nursery' => 0, 'lkg' => 1, 'ukg' => 2];
        $lower = strtolower(trim($name));

        foreach ($map as $key => $order) {
            if (str_contains($lower, $key)) return $order;
        }

        // Extract trailing number, e.g. "Class 10" → 10
        if (preg_match('/(\d+)/', $name, $m)) {
            return 3 + (int)$m[1]; // 1→3, 2→4, … 12→14
        }

        return 99; // unknown goes to end
    }

    public function index()
    {
        $d['total_users']    = User::count();
        $d['total_students'] = StudentRecord::count();
        $d['total_staff']    = StaffRecord::count();
        $d['total_teachers'] = User::where('user_type', 'teacher')->count();
        $d['total_admins']   = User::where('user_type', 'admin')->count();

        // Fee stats
        $d['total_fees_collected'] = PaymentRecord::sum('amt_paid') ?? 0;
        $d['recent_payments']      = PaymentRecord::with('payment', 'student')->latest()->take(8)->get();

        // Recent users
        $d['recent_users'] = User::latest()->take(8)->get();

        // Users by type
        $d['users_by_type'] = User::selectRaw('user_type, count(*) as count')
            ->groupBy('user_type')
            ->pluck('count', 'user_type');

        // Classes sorted: NUR → LKG → UKG → Class 1 → … → Class 12
        $d['classes_enrollment'] = MyClass::withCount('student_record')
            ->get()
            ->sortBy(fn($c) => $this->classOrder($c->name))
            ->values();

        return view('pages.super_admin.dashboard', $d);
    }
}
