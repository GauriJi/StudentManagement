<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\MyClass;
use App\Models\StudentNotification;
use App\Models\TeacherNotification;
use App\Models\StudentRecord;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $d['classes']              = MyClass::orderBy('id')->get();
        $d['recent_notifications'] = StudentNotification::with('student.user')
            ->latest()
            ->take(20)
            ->get();
        return view('pages.super_admin.notifications.index', $d);
    }

    public function send(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:150',
            'message' => 'required|string',
            'target'  => 'required|in:all_students,all_teachers,class_students',
        ]);

        $now = now();

        if ($request->target === 'all_students' || $request->target === 'class_students') {
            $query = StudentRecord::query();
            if ($request->target === 'class_students' && $request->filled('class_id')) {
                $query->where('my_class_id', $request->class_id);
            }
            $studentRecords = $query->get();
            foreach ($studentRecords as $sr) {
                StudentNotification::create([
                    'student_id' => $sr->user_id,
                    'from_id'    => Auth::id(),
                    'type'       => 'admin',
                    'title'      => $request->title,
                    'message'    => $request->message,
                    'is_read'    => 0,
                    'session'    => date('Y'),
                ]);
            }
        }

        if ($request->target === 'all_teachers') {
            $teachers = User::where('user_type', 'teacher')->get();
            foreach ($teachers as $teacher) {
                TeacherNotification::create([
                    'teacher_id' => $teacher->id,
                    'sender_id'  => Auth::id(),
                    'type'       => 'admin',
                    'title'      => $request->title,
                    'message'    => $request->message,
                    'is_read'    => 0,
                ]);
            }
        }

        return back()->with('flash_success', 'Notification sent successfully.');
    }
}
