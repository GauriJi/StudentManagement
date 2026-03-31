<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\MyClass;
use App\Models\ParentNotification;
use App\Models\StudentNotification;
use App\Models\TeacherNotification;
use App\Models\StudentRecord;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class NotificationController extends Controller
{
    public function index()
    {
        $d['classes'] = MyClass::orderBy('id')->get();

        // Combine recent notifications from all tables for history
        $studentNotifs = DB::table('student_notifications')
            ->select('title', 'message', 'type', 'created_at', DB::raw("'student' as target_type"), 'student_id as recipient_id')
            ->latest()
            ->take(30);

        $teacherNotifs = DB::table('teacher_notifications')
            ->select('title', 'message', 'type', 'created_at', DB::raw("'teacher' as target_type"), 'teacher_id as recipient_id')
            ->latest()
            ->take(30);

        // Check if parent_notifications table exists
        $hasParentTable = \Schema::hasTable('parent_notifications');
        if ($hasParentTable) {
            $parentNotifs = DB::table('parent_notifications')
                ->select('title', 'message', 'type', 'created_at', DB::raw("'parent' as target_type"), 'parent_id as recipient_id')
                ->latest()
                ->take(30);

            $allNotifs = $studentNotifs->unionAll($teacherNotifs)->unionAll($parentNotifs);
        } else {
            $allNotifs = $studentNotifs->unionAll($teacherNotifs);
        }

        $d['recent_notifications'] = DB::query()
            ->fromSub($allNotifs, 'combined')
            ->orderByDesc('created_at')
            ->take(30)
            ->get();

        return view('pages.super_admin.notifications.index', $d);
    }

    public function send(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:150',
            'message' => 'required|string',
            'targets' => 'required|array|min:1',
            'targets.*' => 'in:all_students,all_teachers,all_parents,class_students',
        ]);

        $targets = $request->targets;
        $sentTo = [];

        // --- Send to All Students or Class-specific Students ---
        if (in_array('all_students', $targets) || in_array('class_students', $targets)) {
            $query = StudentRecord::query();
            if (in_array('class_students', $targets) && !in_array('all_students', $targets) && $request->filled('class_id')) {
                $query->where('my_class_id', $request->class_id);
                $className = MyClass::find($request->class_id);
                $sentTo[] = 'Students of ' . ($className ? $className->name : 'Class #' . $request->class_id);
            } else {
                $sentTo[] = 'All Students';
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

        // --- Send to All Teachers ---
        if (in_array('all_teachers', $targets)) {
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
            $sentTo[] = 'All Teachers';
        }

        // --- Send to All Parents ---
        if (in_array('all_parents', $targets)) {
            $parents = User::where('user_type', 'parent')->get();
            foreach ($parents as $parent) {
                ParentNotification::create([
                    'parent_id'  => $parent->id,
                    'sender_id'  => Auth::id(),
                    'type'       => 'admin',
                    'title'      => $request->title,
                    'message'    => $request->message,
                    'is_read'    => 0,
                ]);
            }
            $sentTo[] = 'All Parents';
        }

        $sentToStr = implode(', ', $sentTo);
        return back()->with('flash_success', "Notification sent successfully to: {$sentToStr}");
    }
}
