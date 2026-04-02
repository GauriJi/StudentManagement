<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\MyClass;
use App\Models\SchoolTimetable;
use App\Models\Section;
use App\Models\Subject;
use App\User;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    protected $days    = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    protected $periods = [1, 2, 3, 4, 5, 6, 7, 8];

    /* ── Index: show grid for selected class + section ── */
    public function index(Request $req)
    {
        $session = Qs::getSetting('current_session');

        $data['classes']  = $this->sortedClasses();
        $data['teachers'] = User::where('user_type', 'teacher')->orderBy('name')->get();
        $data['session']  = $session;
        $data['days']     = $this->days;
        $data['periods']  = $this->periods;

        $classId   = $req->get('class_id');
        $sectionId = $req->get('section_id');

        if ($classId && $sectionId) {
            $data['selected_class']   = MyClass::find($classId);
            $data['selected_section'] = Section::find($sectionId);
            $data['sections']         = Section::where('my_class_id', $classId)->get();
            $data['subjects']         = Subject::where('my_class_id', $classId)->get();

            // Build grid: day → period → entry
            $entries = SchoolTimetable::with(['subject', 'teacher'])
                ->where('my_class_id', $classId)
                ->where('section_id',  $sectionId)
                ->where('session',     $session)
                ->get();

            $grid = [];
            foreach ($this->days as $day) {
                foreach ($this->periods as $p) {
                    $grid[$day][$p] = null;
                }
            }
            foreach ($entries as $e) {
                $grid[$e->day][$e->period_no] = $e;
            }
            $data['grid'] = $grid;
        } else {
            $data['selected_class']   = null;
            $data['selected_section'] = null;
            $data['sections']         = collect();
            $data['subjects']         = collect();
            $data['grid']             = [];
        }

        return view('pages.admin.timetable.index', $data);
    }

    /* ── Store / Update a single cell ── */
    public function store(Request $req)
    {
        $req->validate([
            'my_class_id' => 'required|integer',
            'section_id'  => 'required|integer',
            'day'         => 'required|string',
            'period_no'   => 'required|integer|min:1|max:8',
            'subject_id'  => 'nullable|integer',
            'teacher_id'  => 'nullable|integer',
            'time_from'   => 'nullable|string',
            'time_to'     => 'nullable|string',
        ]);

        $session = Qs::getSetting('current_session');

        // Conflict check: same teacher at same day+period+session in any class/section
        if ($req->teacher_id) {
            $conflict = SchoolTimetable::where('teacher_id', $req->teacher_id)
                ->where('day',       $req->day)
                ->where('period_no', $req->period_no)
                ->where('session',   $session)
                ->where(function ($q) use ($req) {
                    $q->where('my_class_id', '!=', $req->my_class_id)
                      ->orWhere('section_id', '!=', $req->section_id);
                })
                ->first();

            if ($conflict) {
                $teacher = User::find($req->teacher_id);
                return response()->json([
                    'error' => "⚠ {$teacher->name} is already assigned to {$conflict->my_class->name} {$conflict->section->name} on {$req->day}, Period {$req->period_no}."
                ], 422);
            }
        }

        $entry = SchoolTimetable::updateOrCreate(
            [
                'my_class_id' => $req->my_class_id,
                'section_id'  => $req->section_id,
                'day'         => $req->day,
                'period_no'   => $req->period_no,
                'session'     => $session,
            ],
            [
                'subject_id'  => $req->subject_id  ?: null,
                'teacher_id'  => $req->teacher_id  ?: null,
                'time_from'   => $req->time_from   ?: null,
                'time_to'     => $req->time_to     ?: null,
            ]
        );

        $entry->load('subject', 'teacher');

        return response()->json([
            'success' => true,
            'subject' => $entry->subject->name ?? '—',
            'teacher' => $entry->teacher->name ?? '—',
            'time'    => ($entry->time_from && $entry->time_to)
                            ? "{$entry->time_from}–{$entry->time_to}"
                            : '',
        ]);
    }

    /* ── Delete a single cell ── */
    public function destroy($id)
    {
        SchoolTimetable::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    /* ── AJAX: get sections for a class ── */
    public function getSections($classId)
    {
        $sections = Section::where('my_class_id', $classId)->get(['id','name']);
        return response()->json($sections);
    }

    /* ── Helper: sort classes NUR→LKG→UKG→1–12 ── */
    private function sortedClasses()
    {
        return MyClass::all()->sortBy(function ($c) {
            $name = strtolower(trim($c->name));
            if (str_contains($name, 'nur') || str_contains($name, 'nursery')) return 0;
            if (str_contains($name, 'lkg')) return 1;
            if (str_contains($name, 'ukg')) return 2;
            preg_match('/(\d+)/', $c->name, $m);
            return isset($m[1]) ? 3 + (int)$m[1] : 99;
        })->values();
    }
}
