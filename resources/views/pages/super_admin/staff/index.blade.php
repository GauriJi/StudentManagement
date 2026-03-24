@extends('layouts.master')
@section('page_title', 'Staff Management')

@section('content')
<style>
    .staff-card { border:none; border-radius:12px; transition: transform 0.2s, box-shadow 0.2s; }
    .staff-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
    .role-tag { display:inline-block; padding: 3px 10px; border-radius:999px; font-size:11px; font-weight:700; text-transform:capitalize; }
    .role-super_admin { background:#fde8e8; color:#c0392b; }
    .role-admin       { background:#fdebd0; color:#e67e22; }
    .role-teacher     { background:#d5f5e3; color:#27ae60; }
    .role-accountant  { background:#d6eaf8; color:#2980b9; }
    .role-librarian   { background:#e8daef; color:#8e44ad; }
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="font-weight-bold mb-0"><i class="icon-office mr-2 text-secondary"></i>Staff Management</h5>
        <small class="text-muted">Manage all staff members ({{ $staff->count() }} total)</small>
    </div>
    <div>
        <a href="{{ route('sa.staff.attendance') }}" class="btn btn-outline-success mr-2" style="border-radius:10px;">
            <i class="icon-calendar mr-1"></i> Staff Attendance
        </a>
        <a href="{{ route('sa.staff.create') }}" class="btn btn-primary" style="border-radius:10px;">
            <i class="icon-user-plus mr-1"></i> Add Staff
        </a>
    </div>
</div>

@if(session('flash_success'))
    <div class="alert alert-success alert-dismissible border-0"><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>{{ session('flash_success') }}</div>
@endif
@if(session('flash_danger'))
    <div class="alert alert-danger alert-dismissible border-0"><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>{{ session('flash_danger') }}</div>
@endif

<div class="row">
@forelse($staff as $s)
<div class="col-md-4 col-lg-3 mb-3">
    <div class="card staff-card shadow-sm">
        <div class="card-body text-center py-4">
            <img src="{{ $s->photo }}" width="70" height="70" class="rounded-circle border mb-2">
            <h6 class="font-weight-bold mb-1">{{ $s->name }}</h6>
            <span class="role-tag role-{{ $s->user_type }}">{{ str_replace('_',' ',$s->user_type) }}</span>
            <div class="text-muted font-size-sm mt-1">{{ $s->email }}</div>
            @if($s->phone)
                <div class="text-muted font-size-sm"><i class="icon-phone2"></i> {{ $s->phone }}</div>
            @endif
            @if($s->staff->first())
                <div class="text-muted font-size-sm mt-1"><small>Joined: {{ \Carbon\Carbon::parse($s->staff->first()->emp_date)->format('d M Y') }}</small></div>
            @endif
            @if(!Qs::headSA($s->id))
            <form action="{{ route('sa.staff.destroy', $s->id) }}" method="POST" class="mt-2" onsubmit="return confirm('Remove {{ $s->name }} from staff? This will delete the user account.')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;"><i class="icon-trash mr-1"></i>Remove</button>
            </form>
            @endif
        </div>
    </div>
</div>
@empty
<div class="col-12 text-center text-muted py-5">No staff members found.</div>
@endforelse
</div>
@endsection
