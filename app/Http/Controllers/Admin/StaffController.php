<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\StaffRecord;
use App\Models\StaffAttendance;
use App\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $staffTypes = ['super_admin', 'admin', 'teacher', 'accountant', 'librarian'];
        $d['staff'] = User::whereIn('user_type', $staffTypes)
            ->with('staff')
            ->latest()
            ->get();
        return view('pages.admin.staff.index', $d);
    }

    public function attendance()
    {
        $staffTypes = ['super_admin', 'admin', 'teacher', 'accountant', 'librarian'];
        $d['staff'] = User::whereIn('user_type', $staffTypes)->get();
        $d['date'] = date('Y-m-d');
        
        // Load attendance from DB
        $d['attendances'] = StaffAttendance::where('date', $d['date'])
            ->get()
            ->keyBy('user_id');

        $d['all_attendances'] = StaffAttendance::get()->groupBy('user_id');

        return view('pages.admin.staff.attendance', $d);
    }

    public function markAttendance(Request $request)
    {
        $request->validate([
            'date'   => 'required|date',
            'status' => 'required|array',
        ]);

        $date = $request->date;

        foreach ($request->status as $user_id => $status) {
            StaffAttendance::updateOrCreate(
                ['user_id' => $user_id, 'date' => $date],
                ['status' => $status]
            );
        }

        return back()->with('flash_success', 'Staff attendance for ' . date('d M Y', strtotime($date)) . ' has been saved.');
    }
}
