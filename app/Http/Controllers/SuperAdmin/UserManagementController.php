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
        $d['my_classes'] = \App\Models\MyClass::orderBy('name', 'asc')->get();
        $d['dorms'] = \App\Models\Dorm::orderBy('name', 'asc')->get();
        return view('pages.super_admin.users.create', $d);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'username'  => 'nullable|string|unique:users,username',
            'user_type' => 'required|string',
            'password'  => 'required|string|min:6|confirmed',
        ]);

        if ($request->user_type === 'student') {
            $request->validate([
                'my_class_id' => 'required',
                'section_id'  => 'required',
                'adm_no'      => 'required|string|unique:student_records,adm_no',
            ], [
                'section_id.required' => 'Please select a class section.',
                'my_class_id.required' => 'Please select a class.',
                'adm_no.unique' => 'This Admission Number is already in use by another student.'
            ]);
        }

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
            'alternate_number' => $request->alternate_number,
            'address'   => $request->address,
            'nal_id'    => $request->nal_id,
            'bg_id'     => $request->bg_id,
            'state_id'  => $request->state_id,
            'lga_id'    => $request->lga_id,
            'city'      => $request->city,
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
            $sr['my_class_id'] = $request->my_class_id; 
            $sr['section_id'] = $request->section_id;
            $sr['adm_no'] = $request->adm_no;
            $sr['year_admitted'] = $request->year_admitted;
            $sr['dorm_id'] = $request->dorm_id;
            $sr['dorm_room_no'] = $request->dorm_room_no;
            $sr['house'] = $request->house;
            
            $sr['father_name'] = $request->father_name;
            $sr['mother_name'] = $request->mother_name;
            $sr['father_occupation'] = $request->father_occupation;
            $sr['yearly_income'] = $request->yearly_income;
            $sr['city'] = $request->city;

            // Auto-create parent logic
            if ($request->filled('father_username') || $request->filled('mother_username') || $request->filled('father_email') || $request->filled('mother_email')) {
                $dobPassword = $request->filled('dob') ? date('d-m-Y', strtotime($request->dob)) : 'password';
                $parentName = $request->father_name ?? $request->mother_name ?? 'Parent of ' . $user->name;
                $parentUsername = $request->father_username ?? $request->mother_username;
                $parentEmail = $request->father_email ?? $request->mother_email ?? (strtolower($parentUsername).'@parent.local');

                // Check if user already exists to avoid conflict
                $existingParent = User::where('email', $parentEmail)->orWhere('username', $parentUsername)->first();
                if ($existingParent) {
                    $sr['my_parent_id'] = $existingParent->id;
                } else {
                    $parentUser = User::create([
                        'name' => $parentName,
                        'email' => $parentEmail,
                        'username' => $parentUsername,
                        'password' => Hash::make($dobPassword),
                        'user_type' => 'parent',
                        'code' => Qs::generateUserCode(),
                        'photo' => Qs::getDefaultUserImage(),
                    ]);
                    $sr['my_parent_id'] = $parentUser->id;
                }
            }

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
        $dec_id = Qs::decodeHash($id) ?: $id;
        $d['user']       = User::findOrFail($dec_id);

        if ($d['user']->user_type === 'student') {
            $sr = \App\Models\StudentRecord::where('user_id', $d['user']->id)->first();
            if ($sr) {
                return redirect()->route('students.edit', Qs::hash($sr->id));
            }
        }

        $d['user_types'] = Qs::getAllUserTypes();
        return view('pages.super_admin.users.edit', $d);
    }

    public function update(Request $request, $id)
    {
        $dec_id = Qs::decodeHash($id) ?: $id;
        $user = User::findOrFail($dec_id);

        $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $id,
            'user_type' => 'required|string',
        ]);

        $data = $request->only('name', 'email', 'user_type', 'phone', 'alternate_number', 'address');

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('sa.users.index')->with('flash_success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $dec_id = Qs::decodeHash($id) ?: $id;
        if (Qs::headSA($dec_id)) {
            return back()->with('flash_danger', 'Cannot delete the primary Super Admin.');
        }
        User::findOrFail($dec_id)->delete();
        return back()->with('flash_success', 'User deleted successfully.');
    }

    public function resetPassword($id)
    {
        $dec_id = Qs::decodeHash($id) ?: $id;
        $user = User::findOrFail($dec_id);
        $user->update(['password' => Hash::make('password')]);
        return back()->with('flash_success', 'Password reset to "password" successfully.');
    }
}
