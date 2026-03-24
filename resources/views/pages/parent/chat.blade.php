@extends('layouts.master')
@section('page_title', 'Chat')
@section('content')
<style>
.chat-box{height:420px;overflow-y:auto;background:#f8fafc;border-radius:14px;padding:20px;display:flex;flex-direction:column;gap:12px;}
.msg-bubble{max-width:72%;padding:10px 16px;border-radius:16px;font-size:14px;line-height:1.5;position:relative;}
.msg-parent{background:linear-gradient(135deg,#1e3a5f,#2563eb);color:#fff;border-radius:16px 16px 4px 16px;align-self:flex-end;}
.msg-teacher{background:#fff;border:1px solid #e2e8f0;color:#1e293b;border-radius:16px 16px 16px 4px;align-self:flex-start;}
.msg-wrapper{display:flex;flex-direction:column;}
.msg-wrapper.own{align-items:flex-end;}
.msg-time{font-size:11px;opacity:.6;margin-top:3px;}
.chat-input-bar{background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:12px 16px;display:flex;align-items:center;gap:10px;margin-top:12px;}
.chat-input-bar input{flex:1;border:none;outline:none;font-size:14px;background:transparent;}
.chat-input-bar .send-btn{border-radius:10px;padding:8px 18px;font-weight:600;}
</style>

<div class="d-flex align-items-center mb-3">
    <a href="{{ route('parent.chat.index') }}" class="btn btn-light btn-sm mr-2" style="border-radius:8px;"><i class="icon-arrow-left8"></i></a>
    <img src="{{ $teacher->photo }}" width="38" height="38" class="rounded-circle mr-2 border">
    <div>
        <div class="font-weight-bold">{{ $teacher->name }}</div>
        <small class="text-muted">Teacher</small>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius:16px;">
    <div class="card-body p-4">

        {{-- Child Selector (if multiple children) --}}
        @if($children->count() > 1)
        <div class="mb-3">
            <small class="text-muted font-weight-semibold">Sending as child:</small>
            <div class="d-flex flex-wrap mt-1" id="child-selector">
                @foreach($children as $c)
                <label class="btn btn-sm {{ $loop->first ? 'btn-dark' : 'btn-light border' }} mr-2 mb-1" style="border-radius:8px;cursor:pointer;">
                    <input type="radio" name="selected_child" value="{{ $c->user_id }}" {{ $loop->first ? 'checked' : '' }} class="d-none">
                    <img src="{{ $c->user->photo }}" width="18" height="18" class="rounded-circle mr-1">{{ $c->user->name }}
                </label>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Chat Bubble Area --}}
        <div class="chat-box" id="chat-area">
            @forelse($messages as $msg)
            @php $isParent = $msg->sender_type === 'parent'; @endphp
            <div class="msg-wrapper {{ $isParent ? 'own' : '' }}">
                <div class="msg-bubble {{ $isParent ? 'msg-parent' : 'msg-teacher' }}">
                    {{ $msg->message }}
                </div>
                <span class="msg-time {{ $isParent ? 'text-right' : '' }}">
                    {{ $isParent ? 'You' : $teacher->name }} &bull; {{ \Carbon\Carbon::parse($msg->created_at)->format('d M, g:i A') }}
                </span>
            </div>
            @empty
            <div class="text-center text-muted py-4 my-auto w-100">
                <i class="icon-bubbles3 d-block mb-2" style="font-size:2.5rem;opacity:.3;"></i>
                No messages yet. Start the conversation!
            </div>
            @endforelse
        </div>

        {{-- Send Form --}}
        <form action="{{ route('parent.chat.send', $teacher->id) }}" method="POST" id="chat-form">
            @csrf
            <input type="hidden" name="student_id" id="student_id_input"
                   value="{{ $context_child->user_id ?? ($children->first()->user_id ?? '') }}">
            <div class="chat-input-bar">
                <i class="icon-bubbles3 text-muted"></i>
                <input type="text" name="message" placeholder="Type a message..." autocomplete="off" required id="msg-input">
                <button type="submit" class="btn btn-primary send-btn">
                    <i class="icon-paperplane"></i>
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
// Auto-scroll to bottom of chat
var chatArea = document.getElementById('chat-area');
if (chatArea) chatArea.scrollTop = chatArea.scrollHeight;

// Update student_id when selecting a child
document.querySelectorAll('input[name="selected_child"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.getElementById('student_id_input').value = this.value;
        // Update button styles
        document.querySelectorAll('#child-selector label').forEach(function(lbl) {
            lbl.classList.remove('btn-dark');
            lbl.classList.add('btn-light', 'border');
        });
        this.closest('label').classList.add('btn-dark');
        this.closest('label').classList.remove('btn-light', 'border');
    });
});
</script>
@endsection
@endsection
