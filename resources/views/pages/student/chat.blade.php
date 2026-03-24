@extends('layouts.master')
@section('page_title', 'Chat – {{ $teacher->name }}')
@section('content')
<style>
.chat-wrap{max-width:750px;margin:0 auto;}
.chat-hero{background:linear-gradient(135deg,#1e3a5f,#0f172a);border-radius:16px;padding:20px 28px;color:#fff;margin-bottom:20px;box-shadow:0 8px 32px rgba(0,0,0,.2);}
.chat-box{background:#f8fafc;border-radius:16px;padding:20px;min-height:350px;max-height:480px;overflow-y:auto;display:flex;flex-direction:column;gap:10px;margin-bottom:16px;box-shadow:inset 0 2px 8px rgba(0,0,0,.05);}
.bubble{max-width:72%;padding:10px 16px;border-radius:16px;font-size:.88rem;line-height:1.55;position:relative;}
.bubble.mine{background:linear-gradient(135deg,#3b82f6,#1d4ed8);color:#fff;align-self:flex-end;border-bottom-right-radius:4px;}
.bubble.theirs{background:#fff;color:#1e293b;align-self:flex-start;border-bottom-left-radius:4px;box-shadow:0 2px 8px rgba(0,0,0,.08);}
.bubble-meta{font-size:.7rem;opacity:.7;margin-top:4px;}
.chat-form{background:#fff;border-radius:14px;box-shadow:0 4px 16px rgba(0,0,0,.08);padding:14px 18px;display:flex;gap:10px;align-items:flex-end;}
.chat-form textarea{flex:1;border:1px solid #e2e8f0;border-radius:10px;padding:10px 14px;font-size:.88rem;resize:none;height:52px;transition:border .2s;}
.chat-form textarea:focus{outline:none;border-color:#3b82f6;}
.chat-form button{background:linear-gradient(135deg,#3b82f6,#1d4ed8);color:#fff;border:none;border-radius:10px;padding:12px 18px;cursor:pointer;font-weight:600;transition:opacity .2s;}
.chat-form button:hover{opacity:.88;}
</style>

<div class="chat-wrap">
    <div class="chat-hero d-flex align-items-center" style="gap:14px">
        <img src="{{ $teacher->photo ?? asset('global_assets/images/user.png') }}" style="width:52px;height:52px;border-radius:50%;object-fit:cover;border:2px solid #38bdf8" alt="{{ $teacher->name }}">
        <div>
            <h5 class="mb-0 font-weight-bold">{{ $teacher->name }}</h5>
            <small style="opacity:.7">Teacher &bull; {{ optional(optional($sr)->my_class)->name }}</small>
        </div>
        <a href="{{ route('student.chat.index') }}" class="ml-auto btn btn-sm" style="background:rgba(255,255,255,.15);color:#fff;border-radius:30px">
            <i class="icon-arrow-left22"></i> Back
        </a>
    </div>

    <div class="chat-box" id="chatBox">
        @forelse($messages as $msg)
        @php $mine = $msg->sender_type === 'student'; @endphp
        <div style="display:flex;flex-direction:column;align-items:{{ $mine ? 'flex-end' : 'flex-start' }}">
            <div class="bubble {{ $mine ? 'mine' : 'theirs' }}">
                {{ $msg->message }}
                <div class="bubble-meta">{{ $msg->created_at->format('d M, g:i A') }}</div>
            </div>
        </div>
        @empty
        <div class="text-center text-muted py-4" style="font-size:.9rem">Start the conversation…</div>
        @endforelse
    </div>

    <form action="{{ route('student.chat.send', $teacher_id_hashed) }}" method="POST" class="chat-form">
        @csrf
        <textarea name="message" placeholder="Type your message…" required></textarea>
        <button type="submit"><i class="icon-paperplane"></i></button>
    </form>
</div>

@endsection
@section('scripts')
<script>
    const cb = document.getElementById('chatBox');
    if(cb) cb.scrollTop = cb.scrollHeight;
</script>
@endsection
