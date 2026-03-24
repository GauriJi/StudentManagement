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

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'user_type' => $request->user_type,
            'password'  => Hash::make($request->password),
            'code'      => Qs::generateUserCode(),
            'photo'     => Qs::getDefaultUserImage(),
        ]);

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
