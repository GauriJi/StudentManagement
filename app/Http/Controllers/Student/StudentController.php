<?php

namespace App\Http\Controllers\Student;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ExamRecord;
use App\Models\Mark;
use App\Models\StudentRecord;
use App\Models\StudentNote;
use App\Models\StudentAssignment;
use App\Models\StudentDoubt;
use App\Models\TeacherChat;
use App\Models\TimeSlot;
use App\Models\TimeTableRecord;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSAT', ['only' => ['index', 'show', 'edit', 'update']]);
        $this->middleware('super_admin', ['only' => ['destroy']]);
    }

    public function dashboard()
    {
        $data['my_class_id'] = Qs::findStudentRecord(Auth::user()->id)->my_class_id;
        $data['session'] = Qs::getCurrentSession();
        $data['sr'] = Qs::findStudentRecord(Auth::user()->id);

        // Attendance stats
        $attendances = Attendance::where('student_id', Auth::user()->id)->get();
        $data['att_total'] = $attendances->count();
        $data['att_present'] = $attendances->where('status', 'present')->count();
        $data['att_pct'] = $data['att_total'] > 0 ? round(($data['att_present'] / $data['att_total']) * 100) : 0;

        // Exam summary
        $data['marks'] = Mark::where('student_id', Auth::user()->id)->get();
        $data['avg_score'] = $data['marks']->count() > 0 ? round($data['marks']->avg('t_mark')) : 0;

        // Assignments
        $data['pending_tasks'] = StudentAssignment::where('student_id', Auth::user()->id)->where('status', 'pending')->count();
        
        // Doubts
        $data['open_doubts'] = StudentDoubt::where('student_id', Auth::user()->id)->where('status', 'open')->count();

        return view('pages.student.dashboard', $data);
    }

    public function attendance()
    {
        $data['session'] = Qs::getCurrentSession();
        $data['attendances'] = Attendance::where('student_id', Auth::user()->id)
            ->orderBy('date')
            ->get();

        return view('pages.student.attendance', $data);
    }

    public function exams()
    {
        $data['marks'] = Mark::where('student_id', Auth::user()->id)
            ->with(['exam', 'subject'])
            ->get()
            ->groupBy('exam_id');

        return view('pages.student.exams', $data);
    }

    public function notes()
    {
        $data['session'] = Qs::getCurrentSession();
        $data['notes'] = StudentNote::where('student_id', Auth::user()->id)
            ->where('session', $data['session'])
            ->with(['teacher', 'subject'])
            ->orderByDesc('created_at')
            ->get();

        return view('pages.student.notes', $data);
    }

    public function assignments()
    {
        $data['session'] = Qs::getCurrentSession();
        $data['assignments'] = StudentAssignment::where('student_id', Auth::user()->id)
            ->where('session', $data['session'])
            ->with(['teacher', 'subject'])
            ->orderBy('due_date')
            ->get();

        return view('pages.student.assignments', $data);
    }

    public function chatIndex()
    {
        $sr = Qs::findStudentRecord(Auth::user()->id);
        $data['sr'] = $sr;
        $data['teachers'] = User::where('user_type', 'teacher')->get(); // Simplification: list all teachers
        
        $data['lastMessages'] = TeacherChat::where('student_id', Auth::user()->id)
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('teacher_id');

        return view('pages.student.chat_index', $data);
    }

    public function chat($teacher_id_hashed)
    {
        $teacher_id = Qs::decodeHash($teacher_id_hashed);
        $data['teacher'] = User::find($teacher_id);
        $data['teacher_id_hashed'] = $teacher_id_hashed;
        $data['sr'] = Qs::findStudentRecord(Auth::user()->id);
        
        $data['messages'] = TeacherChat::where('student_id', Auth::user()->id)
            ->where('teacher_id', $teacher_id)
            ->orderBy('created_at')
            ->get();
            
        // Mark as read
        TeacherChat::where('student_id', Auth::user()->id)
            ->where('teacher_id', $teacher_id)
            ->where('sender_type', 'teacher')
            ->update(['is_read' => true]);

        return view('pages.student.chat', $data);
    }

    public function sendMessage(Request $request, $teacher_id_hashed)
    {
        $teacher_id = Qs::decodeHash($teacher_id_hashed);
        
        TeacherChat::create([
            'student_id' => Auth::user()->id,
            'teacher_id' => $teacher_id,
            'message' => $request->message,
            'sender_type' => 'student'
        ]);

        return back()->with('flash_success', 'Message sent.');
    }

    public function doubts()
    {
        $data['sr'] = Qs::findStudentRecord(Auth::user()->id);
        $data['doubts'] = StudentDoubt::where('student_id', Auth::user()->id)
            ->with(['teacher', 'subject'])
            ->orderByDesc('created_at')
            ->get();
            
        $data['teachers'] = User::where('user_type', 'teacher')->get();
        $data['subjects'] = \App\Models\Subject::all();

        return view('pages.student.doubts', $data);
    }

    public function storeDoubt(Request $request)
    {
        StudentDoubt::create([
            'student_id' => Auth::user()->id,
            'teacher_id' => $request->teacher_id,
            'subject_id' => $request->subject_id,
            'question' => $request->question,
            'status' => 'open'
        ]);

        return back()->with('flash_success', 'Question submitted to teacher.');
    }

    public function progress()
    {
        $uid = Auth::id();
        $session = Qs::getCurrentSession();
        $sr = Qs::findStudentRecord($uid);
        
        $data['session'] = $session;
        $data['sr'] = $sr;
        
        // Attendance
        $att = Attendance::where('student_id', $uid)->get();
        $data['attPct'] = $att->count() > 0 ? round(($att->where('status', 'present')->count() / $att->count()) * 100) : 0;
        
        // Marks
        $marks = Mark::where('student_id', $uid)->get();
        $data['avgScore'] = $marks->count() > 0 ? round($marks->avg('t_mark')) : 0;
        
        // Subject breakdown
        $data['subjectMarks'] = $marks->groupBy('subject_id')->map(function($m) {
            return [
                'subject' => optional($m->first()->subject)->name,
                'avg' => round($m->avg('t_mark'))
            ];
        })->values();
        
        // Assignments
        $assign = StudentAssignment::where('student_id', $uid)->where('session', $session)->get();
        $data['assignPct'] = $assign->count() > 0 ? round(($assign->where('status', 'graded')->count() / $assign->count()) * 100) : 0;
        
        // Doubts
        $doubts = StudentDoubt::where('student_id', $uid)->get();
        $data['doubtsTotal'] = $doubts->count();
        $data['doubtsAns'] = $doubts->where('status', 'answered')->count();
        
        $data['examRecords'] = ExamRecord::where('student_id', $uid)->with('exam')->get();

        return view('pages.student.progress', $data);
    }

    public function timetable()
    {
        $uid = Auth::id();
        $sr = Qs::findStudentRecord($uid);
        $data['sr'] = $sr;
        
        $data['ttr'] = TimeTableRecord::where('my_class_id', $sr->my_class_id)
            ->first();
            
        $data['timeSlots'] = $data['ttr'] ? TimeSlot::where('ttr_id', $data['ttr']->id)->orderBy('timestamp_from')->get() : collect();

        return view('pages.student.timetable', $data);
    }

    public function notifications()
    {
        $data['notifications'] = \App\Models\StudentNotification::where('student_id', Auth::user()->id)
            ->with('sender')
            ->orderByDesc('created_at')
            ->get();
            
        // Mark all as read
        \App\Models\StudentNotification::where('student_id', Auth::user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('pages.student.notifications', $data);
    }

    public function markNotificationsRead()
    {
        \App\Models\StudentNotification::where('student_id', Auth::user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return back()->with('flash_success', 'All notifications marked as read.');
    }
}
