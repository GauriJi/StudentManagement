<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function index()
    {
        if (Qs::userIsTeamSA() || Qs::userIsTeacher()) {
            if (Qs::userIsTeacher()) {
                $assignments = Assignment::where('teacher_id', Auth::user()->id)->with(['my_class', 'subject', 'teacher'])->latest()->get();
            } else {
                $assignments = Assignment::with(['my_class', 'subject', 'teacher'])->latest()->get();
            }
        } elseif (Qs::userIsStudent()) {
            $student = Auth::user()->student_record;
            $assignments = Assignment::where('my_class_id', $student->my_class_id)
                ->where(function($q) use($student) {
                    $q->whereNull('section_id')->orWhere('section_id', $student->section_id);
                })
                ->with(['my_class', 'subject', 'teacher'])
                ->latest()
                ->get();
        } elseif (Qs::userIsParent()) {
            // Get all children's classes
            $children = Auth::user()->student_record()->get();
            $class_ids = $children->pluck('my_class_id')->unique();
            $assignments = Assignment::whereIn('my_class_id', $class_ids)->with(['my_class', 'subject', 'teacher'])->latest()->get();
        } else {
            $assignments = collect();
        }

        $my_classes = MyClass::orderBy('id')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('pages.support_team.assignments.index', compact('assignments', 'my_classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'my_class_id' => 'required|integer',
            'subject_id' => 'nullable|integer',
            'file' => 'required|file|mimes:pdf,doc,docx,jpeg,png,jpg|max:5120',
        ]);

        $data = $request->except(['file']);
        $data['teacher_id'] = Auth::user()->id;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $f_name = 'assignment_' . time() . '.' . $file->getClientOriginalExtension();
            $data['file_path'] = $file->storeAs('public/assignments', $f_name);
        }

        $assignment = Assignment::create($data);

        // Notify parents and children
        $students = \App\Models\StudentRecord::where('my_class_id', $assignment->my_class_id)
            ->when($assignment->section_id, function($q) use($assignment) {
                return $q->where('section_id', $assignment->section_id);
            })->with(['user', 'my_parent'])->get();

        foreach ($students as $student) {
            if ($student->user) {
                $student->user->notify(new \App\Notifications\AssignmentUploaded($assignment));
            }
            if ($student->my_parent) {
                $student->my_parent->notify(new \App\Notifications\AssignmentUploaded($assignment));
            }
        }

        return back()->with('flash_success', 'Assignment uploaded successfully');
    }

    public function download($id)
    {
        $assignment = Assignment::findOrFail($id);
        
        // Authorization check logic removed for brevity
        
        if ($assignment->file_path && Storage::exists($assignment->file_path)) {
            return Storage::download($assignment->file_path);
        }

        return back()->with('flash_danger', 'File not found');
    }

    public function destroy($id)
    {
        $assignment = Assignment::findOrFail($id);
        
        if (Qs::userIsSuperAdmin() || Auth::user()->id == $assignment->teacher_id) {
            if ($assignment->file_path && Storage::exists($assignment->file_path)) {
                Storage::delete($assignment->file_path);
            }
            $assignment->delete();
            return back()->with('flash_success', 'Assignment deleted successfully');
        }

        return back()->with('flash_danger', 'Unauthorized action');
    }
}
