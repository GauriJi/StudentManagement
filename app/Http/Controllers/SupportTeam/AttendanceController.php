<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\MyClass;
use App\Models\StudentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        // View to select class and date
        if (Qs::userIsTeacher()) {
            $my_classes = MyClass::where('teacher_id', Auth::user()->id)->get();
        } else if (Qs::userIsTeamSA()) {
            $my_classes = MyClass::all();
        } else {
            return back()->with('flash_danger', 'Unauthorized');
        }

        return view('pages.support_team.attendance.index', compact('my_classes'));
    }

    public function manage(Request $request)
    {
        $request->validate([
            'my_class_id' => 'required|integer',
            'date' => 'required|date'
        ]);

        $class_id = $request->my_class_id;
        $date = $request->date;

        $my_class = MyClass::findOrFail($class_id);

        // Security Check
        if (Qs::userIsTeacher() && $my_class->teacher_id !== Auth::user()->id) {
            abort(403, 'You are not the assigned teacher for this class.');
        }

        $students = StudentRecord::where('my_class_id', $class_id)->with('user')->get();

        // Fetch existing attendance if any
        $attendances = Attendance::where('my_class_id', $class_id)->where('date', $date)->get()->keyBy('student_id');
        $all_attendances = Attendance::where('my_class_id', $class_id)->get()->groupBy('student_id');

        return view('pages.support_team.attendance.manage', compact('my_class', 'students', 'date', 'attendances', 'all_attendances'));
    }

    public function store(Request $request)
    {
        $class_id = $request->my_class_id;
        $date = $request->date;
        $status = $request->status; // array of student_id => status

        if (!Qs::userIsTeacher()) {
            abort(403, 'Unauthorized action.');
        }

        $my_class = MyClass::findOrFail($class_id);

        if ($my_class->teacher_id !== Auth::user()->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($status) {
            foreach ($status as $student_id => $st) {
                Attendance::updateOrCreate(
                    ['student_id' => $student_id, 'my_class_id' => $class_id, 'date' => $date],
                    ['status' => $st]
                );
            }
        }

        return redirect()->route('attendance.index')->with('flash_success', 'Attendance recorded successfully for '.$date);
    }

    public function report(Request $request)
    {
        $class_id = $request->my_class_id;
        $filter_month = $request->get('month'); // YYYY-MM
        $filter_date  = $request->get('date');  // YYYY-MM-DD

        if (!$class_id) {
            return redirect()->route('attendance.index')->with('flash_danger', 'Please select a class first.');
        }

        $my_class = MyClass::findOrFail($class_id);

        $query = Attendance::with('student.user')->where('my_class_id', $class_id)->orderBy('date', 'desc');

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
        $d['my_class'] = $my_class;

        return view('pages.support_team.attendance.report', $d);
    }
}
