<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicCalendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AcademicCalendarController extends Controller
{
    public function index(Request $request)
    {
        $hasTable = Schema::hasTable('academic_calendars');

        $month = $request->month ?? date('Y-m');
        $d['month']      = $month;
        $d['month_name'] = date('F Y', strtotime($month . '-01'));
        $d['types']      = ['holiday', 'event', 'exam', 'notice'];

        if ($hasTable) {
            $d['events'] = AcademicCalendar::orderBy('start_date')->get();
            $d['month_events'] = AcademicCalendar::whereRaw("DATE_FORMAT(start_date,'%Y-%m') = ?", [$month])
                ->orWhereRaw("DATE_FORMAT(end_date,'%Y-%m') = ?", [$month])
                ->orderBy('start_date')->get();
        } else {
            $d['events'] = collect();
            $d['month_events'] = collect();
        }

        // Build calendar grid for current month
        $firstDay   = date('N', strtotime($month . '-01')); // 1=Mon, 7=Sun
        $daysInMonth = date('t', strtotime($month . '-01'));
        $d['calendar_grid'] = ['first_day' => $firstDay, 'days' => $daysInMonth];

        return view('pages.admin.calendar.index', $d);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:200',
            'type'       => 'required|in:holiday,event,exam,notice',
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        AcademicCalendar::create([
            'title'       => $request->title,
            'type'        => $request->type,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'description' => $request->description,
            'created_by'  => Auth::id(),
        ]);

        return back()->with('flash_success', 'Event added to calendar.');
    }

    public function destroy($id)
    {
        AcademicCalendar::findOrFail($id)->delete();
        return back()->with('flash_success', 'Event removed.');
    }
}
