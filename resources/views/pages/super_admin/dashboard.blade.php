@extends('layouts.master')
@section('page_title', 'Super Admin Dashboard')

@section('content')
<style>
    .sa-card { border: none; border-radius: 14px; transition: transform 0.2s, box-shadow 0.2s; }
    .sa-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,0.12); }
    .bg-g1 { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); color: #fff; }
    .bg-g2 { background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%); color: #fff; }
    .bg-g3 { background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%); color: #fff; }
    .bg-g4 { background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%); color: #fff; }
    .bg-g5 { background: linear-gradient(135deg, #2980b9 0%, #3498db 100%); color: #fff; }
    .bg-g6 { background: linear-gradient(135deg, #e67e22 0%, #f39c12 100%); color: #fff; }
    .stat-icon { font-size: 2.8rem; opacity: 0.7; }
    .sa-badge { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; }
    .role-tag { background: rgba(0,0,0,0.08); border-radius: 4px; padding: 2px 7px; font-size: 11px; font-weight: 600; }
    .quick-btn { border-radius: 10px; padding: 10px 16px; font-weight: 600; font-size: 13px; transition: all 0.2s; }
    .quick-btn:hover { transform: translateY(-2px); }
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="font-weight-bold mb-0">Super Admin Dashboard</h4>
        <small class="text-muted">{{ date('l, d F Y') }}</small>
    </div>
    <div class="text-right">
            <span class="badge badge-success px-3 py-2" style="font-size:14px;border-radius:8px;">
                Total Collected: ₹{{ number_format($total_fees_collected) }}
            </span>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row mb-2">
    <div class="col-6 col-md-2 mb-3">
        <div class="card sa-card bg-g1 p-3 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div><h3 class="mb-0 font-weight-bold">{{ $total_users }}</h3><small class="text-uppercase opacity-75">All Users</small></div>
                <i class="icon-users4 stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2 mb-3">
        <div class="card sa-card bg-g2 p-3 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div><h3 class="mb-0 font-weight-bold">{{ $total_students }}</h3><small class="text-uppercase opacity-75">Students</small></div>
                <i class="icon-graduation2 stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2 mb-3">
        <div class="card sa-card bg-g3 p-3 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div><h3 class="mb-0 font-weight-bold">{{ $total_teachers }}</h3><small class="text-uppercase opacity-75">Teachers</small></div>
                <i class="icon-books stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2 mb-3">
        <div class="card sa-card bg-g4 p-3 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div><h3 class="mb-0 font-weight-bold">{{ $total_staff }}</h3><small class="text-uppercase opacity-75">Staff</small></div>
                <i class="icon-office stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2 mb-3">
        <div class="card sa-card bg-g5 p-3 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div><h3 class="mb-0 font-weight-bold">{{ $total_admins }}</h3><small class="text-uppercase opacity-75">Admins</small></div>
                <i class="icon-shield2 stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2 mb-3">
        <div class="card sa-card bg-g6 p-3 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div><h3 class="mb-0 font-weight-bold">₹{{ number_format($total_fees_collected) }}</h3><small class="text-uppercase opacity-75">Fees Collected</small></div>
                <i class="icon-wallet stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Recent Payments --}}
    <div class="col-md-8 mb-3">
        <div class="card border-0 shadow-sm" style="border-radius:14px;">
            <div class="card-header bg-white border-0 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="card-title font-weight-bold mb-0"><i class="icon-wallet mr-2 text-success"></i>Recent Fee Payments</h6>
                <a href="{{ route('sa.fees.index') }}" class="btn btn-sm btn-outline-success" style="border-radius:8px;">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light"><tr><th>Student</th><th>Payment</th><th>Amount</th><th>Date</th></tr></thead>
                        <tbody>
                        @forelse($recent_payments as $pr)
                        <tr>
                            <td>
                                @if($pr->student)
                                    <span class="font-weight-semibold">{{ $pr->student->name }}</span>
                                @else <span class="text-muted">—</span> @endif
                            </td>
                            <td>{{ $pr->payment->title ?? '—' }}</td>
                            <td><span class="badge badge-success">₹{{ number_format($pr->amt_paid) }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($pr->created_at)->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No payments yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions & Recent Users --}}
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm mb-3" style="border-radius:14px;">
            <div class="card-header bg-white border-0 pb-0">
                <h6 class="card-title font-weight-bold mb-0"><i class="icon-lightning mr-2 text-warning"></i>Quick Actions</h6>
            </div>
            <div class="card-body pt-2">
                <a href="{{ route('sa.users.create') }}" class="btn btn-outline-primary btn-block quick-btn mb-2 text-left"><i class="icon-user-plus mr-2"></i>Add New User</a>
                <a href="{{ route('sa.staff.create') }}" class="btn btn-outline-secondary btn-block quick-btn mb-2 text-left"><i class="icon-user-tie mr-2"></i>Add Staff Member</a>
                <a href="{{ route('sa.notifications.index') }}" class="btn btn-outline-warning btn-block quick-btn mb-2 text-left"><i class="icon-bell2 mr-2"></i>Send Notification</a>
                <a href="{{ route('sa.staff.attendance') }}" class="btn btn-outline-success btn-block quick-btn mb-2 text-left"><i class="icon-calendar mr-2"></i>Staff Attendance</a>
                <a href="{{ route('settings') }}" class="btn btn-outline-dark btn-block quick-btn text-left"><i class="icon-gear mr-2"></i>System Settings</a>
            </div>
        </div>

        {{-- User Types Breakdown --}}
        <div class="card border-0 shadow-sm" style="border-radius:14px;">
            <div class="card-header bg-white border-0 pb-0">
                <h6 class="card-title font-weight-bold mb-0"><i class="icon-pie-chart mr-2 text-info"></i>Users by Role</h6>
            </div>
            <div class="card-body pt-2">
                @foreach($users_by_type as $type => $count)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-capitalize">{{ str_replace('_', ' ', $type) }}</span>
                    <span class="badge badge-pill badge-light border font-weight-bold">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Recent Users --}}
<div class="card border-0 shadow-sm" style="border-radius:14px;">
    <div class="card-header bg-white border-0 pb-0 d-flex justify-content-between align-items-center">
        <h6 class="card-title font-weight-bold mb-0"><i class="icon-user-plus mr-2 text-primary"></i>Recently Registered Users</h6>
        <a href="{{ route('sa.users.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;">Manage Users</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light"><tr><th>Name</th><th>Email</th><th>Role</th><th>Joined</th></tr></thead>
                <tbody>
                @forelse($recent_users as $u)
                <tr>
                    <td class="d-flex align-items-center">
                        <img src="{{ $u->photo }}" width="30" height="30" class="rounded-circle mr-2">
                        <span class="font-weight-semibold">{{ $u->name }}</span>
                    </td>
                    <td>{{ $u->email }}</td>
                    <td><span class="role-tag text-capitalize">{{ str_replace('_',' ',$u->user_type) }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($u->created_at)->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-3">No users.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
