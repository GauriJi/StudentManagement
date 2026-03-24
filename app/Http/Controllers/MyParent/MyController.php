<?php

namespace App\Http\Controllers\MyParent;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Mark;
use App\Models\Payment;
use App\Models\PaymentRecord;
use App\Models\StudentNotification;
use App\Models\StudentRecord;
use App\Models\Subject;
use App\Models\TeacherChat;
use App\Repositories\StudentRepo;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyController extends Controller
{
    protected $student;

    public function __construct(StudentRepo $student)
    {
        $this->student = $student;
    }

    /** Helper: get all children of the logged-in parent */
    private function myChildren()
    {
        return StudentRecord::where('my_parent_id', Auth::id())
            ->with(['user', 'my_class', 'section'])
            ->get();
    }

    /* ===================== 1. Dashboard ===================== */
    public function dashboard()
    {
        $children = $this->myChildren();

        // Attendance % per child
        $children->each(function ($child) {
            $total  = Attendance::where('student_id', $child->user_id)->count();
            $present = Attendance::where('student_id', $child->user_id)->where('status', 'present')->count();
            $child->att_pct = $total > 0 ? round(($present / $total) * 100) : 0;

            // Latest avg score
            $marks = Mark::where('student_id', $child->user_id)->avg('cum');
            $child->avg_score = round($marks ?? 0);

            // Unread notifications
            $child->unread_notifs = StudentNotification::where('student_id', $child->user_id)
                ->where('is_read', 0)->count();
        });

        $d['children'] = $children;
        $d['total_children'] = $children->count();
        $recent_notifs = StudentNotification::whereIn('student_id', $children->pluck('user_id'))
            ->where('is_read', 0)->latest()->take(5)->get();
        $d['recent_notifs'] = $recent_notifs;

        return view('pages.parent.dashboard', $d);
    }

    /* ===================== 2. My Children (old) ===================== */
    public function children()
    {
        $data['students'] = $this->student->getRecord(['my_parent_id' => Auth::user()->id])->with(['my_class', 'section'])->get();
        return view('pages.parent.children', $data);
    }

    /* ===================== 3. Child Performance ===================== */
    public function performance($child_id)
    {
        // Verify this child belongs to the logged-in parent
        $child = StudentRecord::where('user_id', $child_id)
            ->where('my_parent_id', Auth::id())
            ->with(['user', 'my_class', 'section'])
            ->firstOrFail();

        $marks = Mark::where('student_id', $child_id)
            ->with(['subject', 'exam', 'grade'])
            ->orderBy('exam_id')
            ->get();

        $byExam = $marks->groupBy('exam.name');
        $overallAvg = round($marks->avg('cum') ?? 0);

        $d['child']      = $child;
        $d['by_exam']    = $byExam;
        $d['overall_avg'] = $overallAvg;
        $d['children']   = $this->myChildren(); // for switcher

        return view('pages.parent.performance', $d);
    }

    /* ===================== 4. Fee Payment ===================== */
    public function fees()
    {
        $children = $this->myChildren();
        $childUserIds = $children->pluck('user_id');

        $records = PaymentRecord::whereIn('student_id', $childUserIds)
            ->with(['payment', 'student', 'receipt'])
            ->latest()
            ->get();

        $d['children'] = $children;
        $d['records']  = $records;
        $d['total_paid'] = $records->sum('amt_paid');
        $d['total_balance'] = $records->sum('balance');

        return view('pages.parent.fees', $d);
    }

    /* ===================== 5. Notifications ===================== */
    public function notifications()
    {
        $children = $this->myChildren();
        $childUserIds = $children->pluck('user_id');

        $notifications = StudentNotification::whereIn('student_id', $childUserIds)
            ->with('student')
            ->latest()
            ->paginate(20);

        $d['notifications'] = $notifications;
        $d['children']      = $children;

        return view('pages.parent.notifications', $d);
    }

    public function markNotificationsRead(Request $request)
    {
        $children = $this->myChildren();
        StudentNotification::whereIn('student_id', $children->pluck('user_id'))
            ->where('is_read', 0)
            ->update(['is_read' => 1]);
        return back()->with('flash_success', 'All notifications marked as read.');
    }

    /* ===================== 6. Chat with Teacher ===================== */
    public function chatIndex()
    {
        $children = $this->myChildren();

        // Get class teachers for each child's class (subjects assigned to teacher)
        $teachers = collect();
        foreach ($children as $child) {
            $classTeachers = Subject::where('my_class_id', $child->my_class_id)
                ->whereNotNull('teacher_id')
                ->with('teacher')
                ->get()
                ->pluck('teacher')
                ->filter()
                ->unique('id');
            foreach ($classTeachers as $teacher) {
                $teacher->child_name = $child->user->name;
                $teacher->child_class = $child->my_class->name ?? '';
                $teachers->push($teacher);
            }
        }

        $d['teachers']  = $teachers->unique('id');
        $d['children']  = $children;
        return view('pages.parent.chat_index', $d);
    }

    public function chat($teacher_id)
    {
        $teacher = User::findOrFail($teacher_id);

        // Get all children of this parent as sender ids
        $children = $this->myChildren();
        $childUserIds = $children->pluck('user_id');

        // Load messages between this parent's children and the teacher
        $messages = TeacherChat::where('teacher_id', $teacher_id)
            ->whereIn('student_id', $childUserIds)
            ->orderBy('created_at')
            ->get();

        // Use first child as the context student for sending
        $contextChild = $children->first();

        $d['teacher']      = $teacher;
        $d['messages']     = $messages;
        $d['context_child'] = $contextChild;
        $d['children']     = $children;

        return view('pages.parent.chat', $d);
    }

    public function sendMessage(Request $request, $teacher_id)
    {
        $request->validate(['message' => 'required|string|max:1000', 'student_id' => 'required']);

        // Verify child belongs to parent
        $child = StudentRecord::where('user_id', $request->student_id)
            ->where('my_parent_id', Auth::id())
            ->firstOrFail();

        TeacherChat::create([
            'student_id'  => $request->student_id,
            'teacher_id'  => $teacher_id,
            'message'     => $request->message,
            'sender_type' => 'parent',
            'is_read'     => 0,
        ]);

        return back();
    }
}