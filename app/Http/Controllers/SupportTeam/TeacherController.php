<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\MyClassRepo;

class TeacherController extends Controller
{
    protected $my_class;

    public function __construct(MyClassRepo $my_class)
    {
        $this->my_class = $my_class;
    }

    public function dashboard()
    {
        $teacher_id = \Auth::user()->id;
        
        $my_classes = $this->my_class->getTeacherClasses($teacher_id);
        $class_ids = $my_classes->pluck('id')->unique()->toArray();
        
        $subjects = \App\Models\Subject::where('teacher_id', $teacher_id)->with('my_class')->get();
        $total_students = \App\Models\StudentRecord::whereIn('my_class_id', $class_ids)->count();
        
        $pending_doubts = \App\Models\Doubt::where('teacher_id', $teacher_id)->where('is_resolved', false)->count();
        $unread_chats = \App\Models\TeacherChat::where('teacher_id', $teacher_id)->where('sender_type', 'student')->where('is_read', false)->count();

        return view('pages.teacher.dashboard', compact('subjects', 'total_students', 'pending_doubts', 'unread_chats', 'my_classes'));
    }

    public function timetables()
    {
        $teacher_id = \Auth::user()->id;
        $my_classes = $this->my_class->getTeacherClasses($teacher_id);
        $class_ids = $my_classes->pluck('id')->unique()->toArray();
        
        $tt_records = \App\Models\TimeTableRecord::whereIn('my_class_id', $class_ids)->get();

        return view('pages.teacher.timetables.index', compact('my_classes', 'tt_records'));
    }

    public function reminders()
    {
        $notifications = \Auth::user()->notifications()->paginate(15);
        \Auth::user()->unreadNotifications->markAsRead();

        return view('pages.teacher.reminders.index', compact('notifications'));
    }

    public function assignments()
    {
        $teacher_id = \Auth::user()->id;
        $assignments = \App\Models\Assignment::where('teacher_id', $teacher_id)->with(['my_class', 'subject'])->latest()->get();
        
        $my_classes = $this->my_class->getTeacherClasses($teacher_id);
        $subjects = \App\Models\Subject::where('teacher_id', $teacher_id)->get();

        return view('pages.teacher.assignments.index', compact('assignments', 'my_classes', 'subjects'));
    }

    public function attendance()
    {
        $teacher_id = \Auth::user()->id;
        $my_classes = $this->my_class->getTeacherClasses($teacher_id);

        return view('pages.support_team.attendance.index', compact('my_classes'));
    }

    public function studyMaterials()
    {
        $teacher_id = \Auth::user()->id;
        $materials = \App\Models\StudyMaterial::where('teacher_id', $teacher_id)->with(['my_class', 'subject'])->latest()->get();
        
        $my_classes = $this->my_class->getTeacherClasses($teacher_id);
        $subjects = \App\Models\Subject::where('teacher_id', $teacher_id)->get();

        return view('pages.teacher.study_materials.index', compact('materials', 'my_classes', 'subjects'));
    }

    public function doubts()
    {
        $teacher_id = \Auth::user()->id;
        $doubts = \App\Models\Doubt::where('teacher_id', $teacher_id)->with(['student', 'subject', 'messages'])->latest()->get();
        return view('pages.teacher.doubts.index', compact('doubts'));
    }

    public function students()
    {
        $teacher_id = \Auth::user()->id;
        $my_classes = $this->my_class->getTeacherClasses($teacher_id);
        $class_ids = $my_classes->pluck('id')->unique()->toArray();

        $classOrder = [
            'Nursery' => 1, 'Nur' => 1, 'LKG' => 2, 'UKG' => 3,
            '1' => 4, '2' => 5, '3' => 6, '4' => 7, '5' => 8,
            '6' => 9, '7' => 10, '8' => 11, '9' => 12, '10' => 13,
            '11' => 14, '12' => 15
        ];

        $my_classes = $my_classes->sortBy(function($mc) use($classOrder) {
            $name = str_replace('Class ', '', $mc->name);
            foreach ($classOrder as $key => $order) {
                if (strcasecmp($name, (string)$key) === 0 || stripos($mc->name, (string)$key) !== false) {
                    return $order;
                }
            }
            return 999 + $mc->id;
        });

        $students = \App\Models\StudentRecord::whereIn('my_class_id', $class_ids)->with(['user', 'my_class'])->get();
        return view('pages.teacher.students.index', compact('my_classes', 'students'));
    }

    public function exams()
    {
        $year = \App\Helpers\Qs::getSetting('current_session');
        $teacher_id = \Auth::user()->id;

        $exams = \App\Models\Exam::where('year', $year)->get();
        
        $my_classes = $this->my_class->getTeacherClasses($teacher_id);
        $class_ids = $my_classes->pluck('id')->unique()->toArray();
        $subjects = \App\Models\Subject::where('teacher_id', $teacher_id)->get();
        $sections = \App\Models\Section::whereIn('my_class_id', $class_ids)->get();
        $selected = false;

        return view('pages.support_team.marks.index', compact('exams', 'my_classes', 'sections', 'subjects', 'selected'));
    }

    public function chatIndex()
    {
        $teacher_id = \Auth::user()->id;
        // Get all students who have chatted with this teacher or are in their classes
        $my_classes = $this->my_class->getTeacherClasses($teacher_id);
        $class_ids = $my_classes->pluck('id')->unique()->toArray();
        $students = \App\Models\StudentRecord::whereIn('my_class_id', $class_ids)->with('user')->get();
        
        $recent_chats = \App\Models\TeacherChat::where('teacher_id', $teacher_id)
            ->with('student')
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('student_id');

        return view('pages.teacher.chat_index', compact('students', 'recent_chats'));
    }

    public function chat($student_id)
    {
        $teacher_id = \Auth::user()->id;
        $student = \App\User::findOrFail($student_id);
        
        // Mark as read
        \App\Models\TeacherChat::where('teacher_id', $teacher_id)
            ->where('student_id', $student_id)
            ->where('sender_type', 'student')
            ->update(['is_read' => true]);

        $messages = \App\Models\TeacherChat::where('teacher_id', $teacher_id)
            ->where('student_id', $student_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('pages.teacher.chat', compact('student', 'messages'));
    }

    public function sendMessage(Request $request, $student_id)
    {
        $request->validate(['message' => 'required|string']);
        
        \App\Models\TeacherChat::create([
            'teacher_id' => \Auth::user()->id,
            'student_id' => $student_id,
            'message' => $request->message,
            'sender_type' => 'teacher',
            'is_read' => false
        ]);

        return back();
    }

    public function notifications()
    {
        $notifications = \App\Models\TeacherNotification::where('teacher_id', \Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('pages.teacher.notifications', compact('notifications'));
    }

    public function markNotificationsRead()
    {
        \App\Models\TeacherNotification::where('teacher_id', \Auth::user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }
}
