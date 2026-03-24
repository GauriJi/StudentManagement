@extends('layouts.master')
@section('page_title', 'Notifications')
@section('content')
<style>
.notif-card{border-radius:12px;border:none;transition:all .2s;margin-bottom:12px;}
.notif-card:hover{transform:translateX(3px);}
.notif-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;}
.type-absent    {background:#fee2e2;color:#dc2626;}
.type-present   {background:#d1fae5;color:#059669;}
.type-marks     {background:#dbeafe;color:#2563eb;}
.type-holiday   {background:#fef3c7;color:#d97706;}
.type-event     {background:#ede9fe;color:#7c3aed;}
.type-homework  {background:#fce7f3;color:#db2777;}
.type-admin     {background:#e0f2fe;color:#0284c7;}
.type-default   {background:#f1f5f9;color:#475569;}
.unread-dot{width:8px;height:8px;border-radius:50%;background:#3b82f6;flex-shrink:0;margin-top:5px;}
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="font-weight-bold mb-0"><i class="icon-bell2 mr-2 text-warning"></i>Notifications</h5>
        <small class="text-muted">Alerts for all your children</small>
    </div>
    <form action="{{ route('parent.notifications.markread') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-primary" style="border-radius:8px;">
            <i class="icon-checkmark3 mr-1"></i> Mark All Read
        </button>
    </form>
</div>

@if(session('flash_success'))
    <div class="alert alert-success alert-dismissible border-0"><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>{{ session('flash_success') }}</div>
@endif

{{-- Child Filter Tabs --}}
<div class="mb-3 d-flex flex-wrap">
    <a href="{{ route('parent.notifications') }}"
       class="btn btn-sm mr-2 mb-1 {{ !request('child') ? 'btn-dark' : 'btn-light border' }}" style="border-radius:8px;">
        All Children
    </a>
    @foreach($children as $c)
    <a href="{{ route('parent.notifications') }}?child={{ $c->user_id }}"
       class="btn btn-sm mr-2 mb-1 {{ request('child') == $c->user_id ? 'btn-dark' : 'btn-light border' }}" style="border-radius:8px;">
        <img src="{{ $c->user->photo }}" width="18" height="18" class="rounded-circle mr-1">
        {{ $c->user->name }}
    </a>
    @endforeach
</div>

@forelse($notifications as $n)
@php
    $type = strtolower($n->type ?? 'default');
    $icons = [
        'absent'   => ['icon-calendar', 'absent'],
        'present'  => ['icon-checkmark3', 'present'],
        'marks'    => ['icon-stats-bars2', 'marks'],
        'holiday'  => ['icon-sun4', 'holiday'],
        'event'    => ['icon-calendar52', 'event'],
        'homework' => ['icon-pencil7', 'homework'],
        'admin'    => ['icon-bell2', 'admin'],
    ];
    $iconData = $icons[$type] ?? ['icon-bell2', 'default'];
@endphp
<div class="card notif-card shadow-sm {{ !$n->is_read ? 'border-left border-primary' : '' }}" style="border-radius:12px;">
    <div class="card-body py-3 d-flex align-items-start">
        <div class="notif-icon type-{{ $iconData[1] }} mr-3">
            <i class="{{ $iconData[0] }}"></i>
        </div>
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-start">
                <div class="font-weight-semibold">{{ $n->title }}</div>
                <div class="d-flex align-items-center ml-2">
                    @if(!$n->is_read)
                    <span class="unread-dot mr-2"></span>
                    @endif
                    <small class="text-muted text-nowrap">{{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}</small>
                </div>
            </div>
            <p class="text-muted mb-1 mt-1" style="font-size:13px;">{{ $n->message }}</p>
            @if($n->student)
            <small class="badge badge-light border">
                <i class="icon-user mr-1"></i>{{ $n->student->name }}
            </small>
            @endif
        </div>
    </div>
</div>
@empty
<div class="text-center text-muted py-5">
    <i class="icon-bell2 d-block mb-3" style="font-size:3rem;opacity:.3;"></i>
    No notifications yet.
</div>
@endforelse

{{ $notifications->links() }}
@endsection
