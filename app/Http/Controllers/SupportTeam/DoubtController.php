<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\Doubt;
use App\Models\DoubtMessage;
use App\Models\Subject;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoubtController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        
        if (Qs::userIsTeacher()) {
            $doubts = Doubt::where('teacher_id', $user_id)->with(['student', 'subject', 'messages'])->latest()->get();
        } elseif (Qs::userIsStudent()) {
            $doubts = Doubt::where('student_id', $user_id)->with(['teacher', 'subject', 'messages'])->latest()->get();
        } elseif (Qs::userIsParent()) {
            $children = Auth::user()->student_record()->get();
            $student_ids = $children->pluck('user_id');
            $doubts = Doubt::whereIn('student_id', $student_ids)->with(['teacher', 'student', 'subject', 'messages'])->latest()->get();
        } elseif (Qs::userIsTeamSA()) {
            $doubts = Doubt::with(['teacher', 'student', 'subject', 'messages'])->latest()->get();
        } else {
            $doubts = collect();
        }

        // To create a doubt, a student needs to select a subject and its teacher
        $subjects = collect();
        if (Qs::userIsStudent()) {
            $my_class_id = Auth::user()->student_record->my_class_id;
            $subjects = Subject::where('my_class_id', $my_class_id)->with('teacher')->get();
        } elseif (Qs::userIsParent()) {
            $children = Auth::user()->student_record()->get();
            $class_ids = $children->pluck('my_class_id')->unique();
            $subjects = Subject::whereIn('my_class_id', $class_ids)->with('teacher')->get();
        }

        return view('pages.support_team.doubts.index', compact('doubts', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'subject_id' => 'required|exists:subjects,id',
            'message' => 'required|string',
        ]);

        $subject = Subject::findOrFail($request->subject_id);

        if (!$subject->teacher_id) {
            return back()->with('flash_danger', 'No teacher assigned to this subject.');
        }

        // Parent or Student logic
        $student_id = Auth::user()->id;
        if (Qs::userIsParent()) {
            $student_id = Auth::user()->student_record()->first()->user_id; // Simplicity: Just pick first kid
        }

        $doubt = Doubt::create([
            'title' => $request->title,
            'student_id' => $student_id,
            'teacher_id' => $subject->teacher_id,
            'subject_id' => $subject->id,
            'is_resolved' => false,
        ]);

        DoubtMessage::create([
            'doubt_id' => $doubt->id,
            'user_id' => Auth::user()->id,
            'message' => $request->message
        ]);

        return back()->with('flash_success', 'Doubt sent successfully');
    }

    public function show($id)
    {
        $doubt = Doubt::with(['messages.user', 'student', 'teacher', 'subject'])->findOrFail($id);

        // Authorization simplistic check
        if (!Qs::userIsTeamSA() && Auth::user()->id !== $doubt->teacher_id && Auth::user()->id !== $doubt->student_id && !Qs::userIsMyChild($doubt->student_id, Auth::user()->id)) {
            abort(403);
        }

        return view('pages.support_team.doubts.show', compact('doubt'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);

        $doubt = Doubt::findOrFail($id);
        
        // Authorization simplistic check
        if (!Qs::userIsTeamSA() && Auth::user()->id !== $doubt->teacher_id && Auth::user()->id !== $doubt->student_id && !Qs::userIsMyChild($doubt->student_id, Auth::user()->id)) {
            abort(403);
        }

        if ($doubt->is_resolved) {
            return back()->with('flash_danger', 'Doubt is already resolved. Cannot reply.');
        }

        DoubtMessage::create([
            'doubt_id' => $doubt->id,
            'user_id' => Auth::user()->id,
            'message' => $request->message
        ]);

        return back()->with('flash_success', 'Message sent.');
    }

    public function resolve($id)
    {
        $doubt = Doubt::findOrFail($id);
        if (Qs::userIsTeamSA() || Auth::user()->id == $doubt->teacher_id) {
            $doubt->update(['is_resolved' => true]);
            return back()->with('flash_success', 'Doubt marked as resolved.');
        }
        return back()->with('flash_danger', 'Unauthorized');
    }
}
