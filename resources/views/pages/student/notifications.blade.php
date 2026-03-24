@extends('layouts.master')
@section('page_title', 'My Notifications')
@section('content')
<style>
.notif-hero{background:linear-gradient(135deg,#1e3a5f,#0f172a);border-radius:16px;padding:24px 28px;color:#fff;margin-bottom:24px;box-shadow:0 8px 32px rgba(0,0,0,.2);}
.notif-card{border-radius:14px;border:none;box-shadow:0 4px 16px rgba(0,0,0,.07);margin-bottom:14px;transition:transform .2s;overflow:hidden;}
.notif-card:hover{transform:translateY(-2px);}
.notif-card.unread{background:#eff6ff;border-left:4px solid #3b82f6;}
.notif-card.read{background:#fff;border-left:4px solid #e2e8f0;opacity:0.85;}
.notif-icon-wrap{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#fff;flex-shrink:0;}
.type-homework{background:linear-gradient(135deg,#f59e0b,#d97706);}
.type-exam{background:linear-gradient(135deg,#ef4444,#b91c1c);}
.type-message{background:linear-gradient(135deg,#3b82f6,#1d4ed8);}
.type-assignment{background:linear-gradient(135deg,#10b981,#059669);}
.type-event{background:linear-gradient(135deg,#8b5cf6,#6d28d9);}
.type-general{background:linear-gradient(135deg,#64748b,#475569);}
.notif-title{font-weight:700;color:#1e3a5f;margin-bottom:4px;font-size:1.05rem;}
.notif-title.unread-text{color:#1d4ed8;}
.notif-meta{font-size:.8rem;color:#64748b;}
.notif-body{font-size:.9rem;color:#475569;margin-top:8px;}
</style>

<div class="notif-hero d-flex align-items-center" style="gap:16px">
    <i class="icon-bell2" style="font-size:2rem;color:#fcd34d"></i>
    <div>
        <h4 class="mb-0 font-weight-bold">My Notifications</h4>
        <small style="opacity:.7">Recent alerts, messages, and updates</small>
    </div>
    <div class="ml-auto">
        <form action="{{ route('student.notifications.markread') }}" method="POST">
            @csrf
            <button type="submit" class="btn" style="background:rgba(255,255,255,.15);color:#fff;border-radius:30px;font-weight:600">
                <i class="icon-checkmark-circle2 mr-1"></i> Mark All as Read
            </button>
        </form>
    </div>
</div>

@forelse($notifications as $n)
<div class="card notif-card {{ $n->is_read ? 'read' : 'unread' }}">
    <div class="card-body p-3 d-flex" style="gap:16px">
        <div class="notif-icon-wrap type-{{ $n->type }}">
            <i class="icon-bell2"></i>
        </div>
        <div style="flex:1">
            <div class="d-flex justify-content-between align-items-start">
                <div class="notif-title {{ !$n->is_read ? 'unread-text' : '' }}">{{ $n->title }}</div>
                <div class="notif-meta text-right">
                    <i class="icon-history"></i> {{ $n->created_at->diffForHumans() }}
                </div>
            </div>
            
            <div class="notif-meta mt-1">
                <span class="badge badge-light mr-2" style="text-transform:uppercase;font-size:0.7rem;letter-spacing:0.5px">{{ $n->type }}</span>
                @if($n->sender)
                    <i class="icon-user"></i> From: {{ $n->sender->name }}
                @endif
            </div>
            
            <div class="notif-body">
                {!! nl2br(e($n->message)) !!}
            </div>
        </div>
    </div>
</div>
@empty
<div class="text-center py-5">
    <h5 class="font-weight-bold text-muted">You're all caught up!</h5>
</div>
@endforelse

@endsection
