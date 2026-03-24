<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicCalendar;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\MyClass;
use App\Models\Payment;
use App\Models\PaymentRecord;
use App\Models\Section;
use App\Models\StudentRecord;
use App\Models\Subject;
use App\Models\TimeTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = date('Y-m-d');
        $year  = \App\Helpers\Qs::getSetting('current_session');

        // Key stats
        $d['total_students']  = StudentRecord::count();
        $d['total_teachers']  = \App\User::where('user_type', 'teacher')->count();
        $d['total_classes']   = MyClass::count();
        $d['total_subjects']  = Subject::count();

        // Attendance today
        $d['today_present']   = Attendance::where('date', $today)->where('status', 'present')->count();
        $d['today_total']     = Attendance::where('date', $today)->count();
        $d['att_pct']         = $d['today_total'] > 0
            ? round(($d['today_present'] / $d['today_total']) * 100)
            : 0;

        // Fees
        $d['total_fees_defined'] = Payment::sum('amount') ?? 0;
        $d['total_fees_paid']    = PaymentRecord::sum('amt_paid') ?? 0;
        $d['fees_pending']       = max(0, $d['total_fees_defined'] - $d['total_fees_paid']);

        // Recent admissions
        $d['recent_students'] = StudentRecord::with(['user', 'my_class'])
            ->latest()->take(8)->get();

        // Upcoming exams (using the year field — show current session's exams)
        $d['upcoming_exams'] = Exam::where('year', $year)
            ->latest()->take(5)->get();

        // Upcoming calendar events
        $hasCalendar = \Illuminate\Support\Facades\Schema::hasTable('academic_calendars');
        $d['upcoming_events'] = $hasCalendar
            ? AcademicCalendar::where('start_date', '>=', $today)
                ->orderBy('start_date')->take(5)->get()
            : collect();

        // Monthly attendance chart data (last 6 months)
        $d['att_chart'] = collect(range(5, 0))->map(function ($i) {
            $month = date('Y-m', strtotime("-$i months"));
            $total   = Attendance::whereRaw("DATE_FORMAT(date,'%Y-%m') = ?", [$month])->count();
            $present = Attendance::whereRaw("DATE_FORMAT(date,'%Y-%m') = ?", [$month])
                ->where('status', 'present')->count();
            return [
                'month' => date('M Y', strtotime("-$i months")),
                'pct'   => $total > 0 ? round(($present / $total) * 100) : 0,
            ];
        });

        // Student class distribution
        $d['class_dist'] = MyClass::withCount('student_record')->orderBy('name')->get();

        return view('pages.admin.dashboard', $d);
    }
}
