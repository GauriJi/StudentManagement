@extends('layouts.master')
@section('page_title', 'Chat with Teacher')
@section('content')
<style>
.chat-hero{background:linear-gradient(135deg,#1e3a5f,#0f172a);border-radius:16px;padding:24px 28px;color:#fff;margin-bottom:24px;box-shadow:0 8px 32px rgba(0,0,0,.2);}
.teacher-card{border-radius:14px;border:none;box-shadow:0 4px 16px rgba(0,0,0,.08);margin-bottom:14px;transition:all .2s;overflow:hidden;}
.teacher-card:hover{transform:translateY(-3px);box-shadow:0 8px 28px rgba(0,0,0,.13);}
.teacher-card a{display:flex;align-items:center;padding:16px 20px;color:inherit;text-decoration:none;gap:14px;}
.teacher-card img{width:52px;height:52px;border-radius:50%;object-fit:cover;border:2px solid #e5e7eb;}
.teacher-name{font-weight:700;color:#1e3a5f;margin:0;}
.teacher-meta{font-size:.8rem;color:#94a3b8;}
.unread-badge{background:#ef4444;color:#fff;border-radius:50%;width:20px;height:20px;font-size:.72rem;display:flex;align-items:center;justify-content:center;font-weight:700;margin-left:auto;}
.last-msg{font-size:.8rem;color:#64748b;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
</style>

<div class="chat-hero d-flex align-items-center" style="gap:16px">
    <i class="icon-bubbles4" style="font-size:2rem;color:#38bdf8"></i>
    <div>
        <h4 class="mb-0 font-weight-bold">Chat with Teacher</h4>
        <small style="opacity:.7">{{ optional(optional($sr)->my_class)->name }} &bull; Select a teacher to start chatting</small>
    </div>
</div>

@forelse($teachers as $t)
@php
    $msgs = $lastMessages[$t->id] ?? collect();
    $lastMsg = $msgs->first();
    $unread  = $msgs->where('sender_type','teacher')->where('is_read',false)->count();
@endphp
<div class="teacher-card card">
    <a href="{{ route('student.chat', Qs::hash($t->id)) }}">
        <img src="{{ $t->photo ?? asset('global_assets/images/user.png') }}" alt="{{ $t->name }}">
        <div style="flex:1">
            <p class="teacher-name">{{ $t->name }}</p>
            @if($lastMsg)
                <div class="last-msg">{{ $lastMsg->message }}</div>
            @else
                <div class="teacher-meta">No messages yet</div>
            @endif
        </div>
        @if($unread > 0)
            <span class="unread-badge">{{ $unread }}</span>
        @endif
        <i class="icon-arrow-right8 text-muted ml-2"></i>
    </a>
</div>
@empty
<div class="text-center py-5">
    <i class="icon-bubbles4" style="font-size:3.5rem;color:#cbd5e1"></i>
    <p class="mt-3 text-muted">No teachers found for your class yet.</p>
</div>
@endforelse
@endsection
