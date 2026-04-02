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

    public function attendanceReport(Request $request)
    {
        $query = StaffAttendance::with('user')->orderBy('date', 'desc');

        $filter_month = $request->get('month'); // YYYY-MM
        $filter_date  = $request->get('date');  // YYYY-MM-DD

        $period = "All Time";

        if ($filter_date) {
            $query->where('date', $filter_date);
            $period = date('d F Y', strtotime($filter_date));
        } elseif ($filter_month) {
            $query->where('date', 'like', $filter_month . '%');
            $period = date('F Y', strtotime($filter_month . '-01'));
        }

        $d['attendances'] = $query->get();
        $d['filter_date'] = $filter_date;
        $d['filter_month'] = $filter_month;
        $d['period'] = $period;

        return view('pages.admin.staff.attendance_report', $d);
    }
}
