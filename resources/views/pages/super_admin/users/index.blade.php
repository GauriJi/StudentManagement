@extends('layouts.master')
@section('page_title', 'User Management')

@section('content')
<style>
    .sa-table th { font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: #666; }
    .role-badge { padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; text-transform: capitalize; }
    .role-super_admin { background: #fde8e8; color: #c0392b; }
    .role-admin       { background: #fdebd0; color: #e67e22; }
    .role-teacher     { background: #d5f5e3; color: #27ae60; }
    .role-accountant  { background: #d6eaf8; color: #2980b9; }
    .role-librarian   { background: #e8daef; color: #8e44ad; }
    .role-student     { background: #eaf2ff; color: #1a73e8; }
    .role-parent      { background: #fef9e7; color: #b7950b; }
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="font-weight-bold mb-0"><i class="icon-users4 mr-2 text-primary"></i>User Management</h5>
        <small class="text-muted">Create, manage roles, and control access for all users</small>
    </div>
    <a href="{{ route('sa.users.create') }}" class="btn btn-primary" style="border-radius:10px;">
        <i class="icon-user-plus mr-1"></i> Add New User
    </a>
</div>

{{-- Flash Messages --}}
@if(session('flash_success'))
    <div class="alert alert-success alert-dismissible border-0"><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>{{ session('flash_success') }}</div>
@endif
@if(session('flash_danger'))
    <div class="alert alert-danger alert-dismissible border-0"><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>{{ session('flash_danger') }}</div>
@endif

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-3" style="border-radius:12px;">
    <div class="card-body py-2">
        <form method="GET" class="form-inline">
            <div class="mr-2 mb-1">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name or email..." value="{{ request('search') }}" style="min-width:200px;border-radius:8px;">
            </div>
            <div class="mr-2 mb-1">
                <select name="role" class="form-control form-control-sm" style="border-radius:8px;">
                    <option value="">All Roles</option>
                    @foreach($user_types as $type)
                    <option value="{{ $type }}" {{ request('role') == $type ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$type)) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-sm btn-primary mr-1" style="border-radius:8px;"><i class="icon-search4"></i> Filter</button>
            <a href="{{ route('sa.users.index') }}" class="btn btn-sm btn-light" style="border-radius:8px;">Clear</a>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius:14px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover sa-table mb-0">
                <thead class="thead-light">
                    <tr><th width="40">#</th><th>User</th><th>Email</th><th>Phone</th><th>Role</th><th class="text-center">Actions</th></tr>
                </thead>
                <tbody>
                @forelse($users as $i => $u)
                <tr>
                    <td class="text-muted">{{ $users->firstItem() + $i }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ $u->photo }}" width="34" height="34" class="rounded-circle mr-2 border">
                            <div>
                                <div class="font-weight-semibold">{{ $u->name }}</div>
                                <small class="text-muted">{{ $u->code }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->phone ?? '—' }}</td>
                    <td><span class="role-badge role-{{ $u->user_type }}">{{ str_replace('_',' ',$u->user_type) }}</span></td>
                    <td class="text-center">
                        <a href="{{ route('sa.users.edit', Qs::hash($u->id)) }}" class="btn btn-sm btn-outline-primary mr-1" title="Edit"><i class="icon-pencil7"></i></a>

                        {{-- Reset Password --}}
                        <form action="{{ route('sa.users.reset_pass', Qs::hash($u->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('Reset password to default for {{ $u->name }}?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-warning mr-1" title="Reset Password"><i class="icon-key"></i></button>
                        </form>

                        {{-- Delete --}}
                        @if(!Qs::headSA($u->id))
                        <form action="{{ route('sa.users.destroy', Qs::hash($u->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete user {{ $u->name }}? This cannot be undone.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="icon-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No users found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="card-body border-top pt-3">
            {{ $users->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
