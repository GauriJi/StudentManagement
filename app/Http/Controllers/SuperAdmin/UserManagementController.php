<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('user_type', $request->role);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $d['users']      = $query->latest()->paginate(20);
        $d['user_types'] = Qs::getAllUserTypes();
        return view('pages.super_admin.users.index', $d);
    }

    public function create()
    {
        $d['user_types'] = Qs::getAllUserTypes();
        $d['states'] = \App\Models\State::all();
        $d['nationals'] = \App\Models\Nationality::all();
        $d['blood_groups'] = \App\Models\BloodGroup::all();
        return view('pages.super_admin.users.create', $d);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'user_type' => 'required|string',
            'password'  => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'user_type' => $request->user_type,
            'password'  => Hash::make($request->password),
            'code'      => Qs::generateUserCode(),
            'photo'     => Qs::getDefaultUserImage(),
            'username'  => $request->username,
            'gender'    => $request->gender,
            'phone'     => $request->phone,
            'phone2'    => $request->phone2,
            'address'   => $request->address,
            'nal_id'    => $request->nal_id,
            'bg_id'     => $request->bg_id,
            'state_id'  => $request->state_id,
            'lga_id'    => $request->lga_id,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'father_occupation' => $request->father_occupation,
            'yearly_income' => $request->yearly_income,
            'relationship_to_student' => $request->relationship_to_student,
            'dob'       => $request->dob,
        ]);

        if($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $f = Qs::getFileMetaData($photo);
            $f['name'] = 'photo.' . $f['ext'];
            $f['path'] = $photo->storeAs('uploads/users/'.$user->code, $f['name']);
            $user->update(['photo' => asset('storage/' . $f['path'])]);
        }

        if($request->user_type === 'student') {
            $sr = [];
            $sr['user_id'] = $user->id;
            $sr['session'] = Qs::getSetting('current_session');
            // Adding a fallback to 1 to ensure model doesn't fail on required class if not provided in this view
            $sr['my_class_id'] = $request->my_class_id ?? 1; 
            $sr['section_id'] = $request->section_id ?? 1;
            
            $sr['father_name'] = $request->father_name;
            $sr['mother_name'] = $request->mother_name;
            $sr['father_occupation'] = $request->father_occupation;
            $sr['yearly_income'] = $request->yearly_income;

            $docFields = ['aadhar_card', 'prev_marksheet', 'birth_certificate'];
            foreach ($docFields as $doc) {
                if ($request->hasFile($doc)) {
                    $file = $request->file($doc);
                    $f = Qs::getFileMetaData($file);
                    $f['name'] = $doc . '.' . $f['ext'];
                    $f['path'] = $file->storeAs('uploads/student/'.$user->code, $f['name']);
                    $sr[$doc] = asset('storage/' . $f['path']);
                }
            }
            \App\Models\StudentRecord::create($sr);
        }

        if($request->user_type === 'teacher' || $request->user_type === 'staff') {
            \App\Models\StaffRecord::create([
                'user_id' => $user->id,
                'emp_date' => $request->emp_date,
                'qualification' => $request->qualification,
                'specialization' => $request->specialization,
            ]);
        }

        return redirect()->route('sa.users.index')->with('flash_success', 'User created successfully.');
    }

    public function edit($id)
    {
        $d['user']       = User::findOrFail($id);
        $d['user_types'] = Qs::getAllUserTypes();
        return view('pages.super_admin.users.edit', $d);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $id,
            'user_type' => 'required|string',
        ]);

        $data = $request->only('name', 'email', 'user_type', 'phone', 'address');

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('sa.users.index')->with('flash_success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        if (Qs::headSA($id)) {
            return back()->with('flash_danger', 'Cannot delete the primary Super Admin.');
        }
        User::findOrFail($id)->delete();
        return back()->with('flash_success', 'User deleted successfully.');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->update(['password' => Hash::make('password')]);
        return back()->with('flash_success', 'Password reset to "password" successfully.');
    }
}
