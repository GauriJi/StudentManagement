<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\StaffRecord;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffManagementController extends Controller
{
    public function index()
    {
        $staffTypes = ['super_admin', 'admin', 'teacher', 'accountant', 'librarian'];
        $d['staff'] = User::whereIn('user_type', $staffTypes)
            ->with('staff')
            ->latest()
            ->get();

        $d['staff_types'] = $staffTypes;
        return view('pages.super_admin.staff.index', $d);
    }

    public function create()
    {
        $d['staff_types'] = ['admin', 'teacher', 'accountant', 'librarian'];
        return view('pages.super_admin.staff.create', $d);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'user_type' => 'required|string',
            'emp_date'  => 'required|date',
            'password'  => 'required|min:6',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'user_type' => $request->user_type,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'code'      => Qs::generateUserCode(),
            'photo'     => Qs::getDefaultUserImage(),
        ]);

        StaffRecord::create([
            'user_id'  => $user->id,
            'emp_date' => $request->emp_date,
            'code'     => Qs::generateUserCode(),
        ]);

        return redirect()->route('sa.staff.index')->with('flash_success', 'Staff member added successfully.');
    }

    public function destroy($id)
    {
        if (Qs::headSA($id)) {
            return back()->with('flash_danger', 'Cannot remove the primary Super Admin.');
        }
        $user = User::findOrFail($id);
        StaffRecord::where('user_id', $id)->delete();
        $user->delete();
        return back()->with('flash_success', 'Staff member removed successfully.');
    }

    public function attendance()
    {
        $staffTypes = ['super_admin', 'admin', 'teacher', 'accountant', 'librarian'];
        $d['staff'] = User::whereIn('user_type', $staffTypes)->get();
        $d['today'] = date('Y-m-d');
        // Load any previously saved attendance from the session-backed cache
        $d['attendance'] = collect(session('staff_attendance_' . date('Y-m-d'), []));
        return view('pages.super_admin.staff.attendance', $d);
    }

    public function markAttendance(Request $request)
    {
        $request->validate([
            'date'   => 'required|date',
            'status' => 'required|array',
        ]);

        // Store in session for demonstration; replace with DB table if a staff_attendance migration is added
        session(['staff_attendance_' . $request->date => $request->status]);

        return back()->with('flash_success', 'Staff attendance for ' . $request->date . ' has been recorded successfully.');
    }
}
