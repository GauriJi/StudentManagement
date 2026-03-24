<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MyClass;
use App\Models\Subject;
use App\Models\TimeTable;
use App\Models\TimeTableRecord;
use App\Models\TimeSlot;
use App\User;
use Illuminate\Http\Request;

class SubstitutionController extends Controller
{
    public function index(Request $request)
    {
        $year = \App\Helpers\Qs::getSetting('current_session');

        // All teachers
        $d['teachers'] = User::where('user_type', 'teacher')->orderBy('name')->get();

        // Get all active timetables (one per class)
        $d['timetables'] = TimeTableRecord::with(['my_class', 'exam'])
            ->where('year', $year)
            ->get();

        // Time slots for today's date context
        $d['time_slots'] = TimeSlot::with('tt_record')->get();

        // All subjects with class info (for substitute assignment context)
        $d['subjects'] = Subject::with(['my_class', 'teacher'])->get();

        // Teachers already assigned to subjects
        $teacher_ids = Subject::pluck('teacher_id')->unique()->filter()->toArray();

        // "Free" teachers = those not assigned to any subject
        $d['free_teachers'] = User::where('user_type', 'teacher')
            ->whereNotIn('id', $teacher_ids)
            ->get();

        // All classes
        $d['classes'] = MyClass::orderBy('name')->get();

        $d['today'] = date('l, d M Y');

        return view('pages.admin.substitution.index', $d);
    }
}
