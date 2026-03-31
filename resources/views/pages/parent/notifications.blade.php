@extends('layouts.master')
@section('page_title', 'Notifications')
@section('content')
<style>
.notif-hero{background:linear-gradient(135deg,#1e3a5f,#0f172a);border-radius:16px;padding:24px 28px;color:#fff;margin-bottom:24px;box-shadow:0 8px 32px rgba(0,0,0,.2);}
.notif-card{border-radius:12px;border:none;transition:all .2s;margin-bottom:12px;}
.notif-card:hover{transform:translateX(3px);box-shadow:0 6px 20px rgba(0,0,0,.1);}
.notif-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;}
.type-absent    {background:#fee2e2;color:#dc2626;}
.type-present   {background:#d1fae5;color:#059669;}
.type-marks     {background:#dbeafe;color:#2563eb;}
.type-holiday   {background:#fef3c7;color:#d97706;}
.type-event     {background:#ede9fe;color:#7c3aed;}
.type-homework  {background:#fce7f3;color:#db2777;}
.type-admin     {background:#e0f2fe;color:#0284c7;}
.type-general   {background:#f1f5f9;color:#475569;}
.type-default   {background:#f1f5f9;color:#475569;}
.unread-dot{width:8px;height:8px;border-radius:50%;background:#3b82f6;flex-shrink:0;margin-top:5px;}
.filter-tab{padding:7px 20px;border-radius:30px;font-weight:600;font-size:.85rem;border:2px solid #e2e8f0;background:#fff;color:#64748b;text-decoration:none;transition:all .2s;}
.filter-tab:hover{border-color:#3b82f6;color:#3b82f6;text-decoration:none;}
.filter-tab.active{background:#3b82f6;border-color:#3b82f6;color:#fff;}
.source-badge-child{background:#dbeafe;color:#1d4ed8;font-size:.7rem;padding:2px 8px;border-radius:20px;font-weight:600;}
.source-badge-direct{background:#d1fae5;color:#059669;font-size:.7rem;padding:2px 8px;border-radius:20px;font-weight:600;}
</style>

{{-- Hero --}}
<div class="notif-hero">
    <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:12px">
        <div class="d-flex align-items-center" style="gap:16px">
            <i class="icon-bell2" style="font-size:2rem;color:#fcd34d"></i>
            <div>
                <h4 class="mb-0 font-weight-bold">Notifications</h4>
                <small style="opacity:.7">Alerts for you and your children</small>
            </div>
        </div>
        <form action="{{ route('parent.notifications.markread') }}" method="POST">
            @csrf
            <button type="submit" class="btn" style="background:rgba(255,255,255,.15);color:#fff;border-radius:30px;font-weight:600;border:none;">
                <i class="icon-checkmark-circle2 mr-1"></i> Mark All Read
            </button>
        </form>
    </div>
</div>

@if(session('flash_success'))
    <div class="alert alert-success alert-dismissible border-0" style="border-radius:12px;">
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        {{ session('flash_success') }}
    </div>
@endif

{{-- Filter Tabs --}}
<div class="mb-3 d-flex flex-wrap align-items-center" style="gap:8px">
    <a href="{{ route('parent.notifications') }}"
       class="filter-tab {{ $filter === 'all' ? 'active' : '' }}">
        <i class="icon-list3 mr-1"></i> All
    </a>
    <a href="{{ route('parent.notifications') }}?filter=children"
       class="filter-tab {{ $filter === 'children' ? 'active' : '' }}">
        <i class="icon-users4 mr-1"></i> My Children
    </a>
    <a href="{{ route('parent.notifications') }}?filter=direct"
       class="filter-tab {{ $filter === 'direct' ? 'active' : '' }}">
        <i class="icon-bell2 mr-1"></i> Direct (School)
    </a>

    @if($filter === 'children' || $filter === 'all')
    <div class="ml-auto d-flex flex-wrap" style="gap:6px">
        <a href="{{ route('parent.notifications') }}?filter={{ $filter }}"
           class="btn btn-sm {{ !request('child') ? 'btn-dark' : 'btn-light border' }}" style="border-radius:20px;">
            All Children
        </a>
        @foreach($children as $c)
        <a href="{{ route('parent.notifications') }}?filter={{ $filter }}&child={{ $c->user_id }}"
           class="btn btn-sm {{ request('child') == $c->user_id ? 'btn-primary' : 'btn-light border' }}" style="border-radius:20px;">
            @if($c->user && $c->user->photo)
            <img src="{{ $c->user->photo }}" width="16" height="16" class="rounded-circle mr-1">
            @endif
            {{ $c->user->name ?? 'Child' }}
        </a>
        @endforeach
    </div>
    @endif
</div>

{{-- Notifications List --}}
@forelse($notifications as $n)
@php
    $type = strtolower($n->type ?? 'general');
    $notifSource = $n->notif_source ?? 'child';
    $icons = [
        'absent'   => ['icon-calendar', 'absent'],
        'present'  => ['icon-checkmark3', 'present'],
        'marks'    => ['icon-stats-bars2', 'marks'],
        'holiday'  => ['icon-sun4', 'holiday'],
        'event'    => ['icon-calendar52', 'event'],
        'homework' => ['icon-pencil7', 'homework'],
        'admin'    => ['icon-bell2', 'admin'],
        'general'  => ['icon-bell2', 'general'],
    ];
    $iconData = $icons[$type] ?? ['icon-bell2', 'general'];
@endphp
<div class="card notif-card shadow-sm {{ !$n->is_read ? 'border-left border-primary' : '' }}" style="border-radius:12px;">
    <div class="card-body py-3 d-flex align-items-start" style="gap:14px">
        <div class="notif-icon type-{{ $iconData[1] }}">
            <i class="{{ $iconData[0] }}"></i>
        </div>
        <div class="flex-grow-1" style="min-width:0;">
            <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap:6px">
                <div class="font-weight-semibold" style="font-size:.95rem;">{{ $n->title }}</div>
                <div class="d-flex align-items-center" style="gap:6px">
                    {{-- Source Badge --}}
                    @if($notifSource === 'direct')
                        <span class="source-badge-direct"><i class="icon-shield mr-1"></i>School</span>
                    @else
                        <span class="source-badge-child"><i class="icon-user mr-1"></i>Child</span>
                    @endif
                    @if(!$n->is_read)
                    <span class="unread-dot"></span>
                    @endif
                    <small class="text-muted text-nowrap">{{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}</small>
                </div>
            </div>
            <p class="text-muted mb-1 mt-1" style="font-size:13px;">{{ $n->message }}</p>
            @if(isset($n->student) && $n->student && $notifSource === 'child')
            <small class="badge badge-light border">
                <i class="icon-user mr-1"></i>{{ $n->student->name }}
            </small>
            @endif
            @if($notifSource === 'direct' && isset($n->sender) && $n->sender)
            <small class="badge badge-light border">
                <i class="icon-user-tie mr-1"></i>From: {{ $n->sender->name }}
            </small>
            @endif
        </div>
    </div>
</div>
@empty
<div class="text-center text-muted py-5">
    <i class="icon-bell2 d-block mb-3" style="font-size:3rem;opacity:.3;"></i>
    <h6 class="font-weight-semibold">No notifications yet</h6>
    <small>You're all caught up!</small>
</div>
@endforelse

{{ $notifications->appends(request()->query())->links() }}
@endsection
