@extends('layouts.master')
@section('page_title', 'Chat with ' . $student->name)
@section('content')

<style>
    .chat-container { border-radius: 12px; border: 1px solid rgba(0,0,0,0.05); box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; background: #fff;}
    .chat-header { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); padding: 15px 20px; color: white; display: flex; align-items: center;}
    .chat-body { height: 450px; overflow-y: auto; padding: 20px; background: #f8fafc; }
    .chat-footer { padding: 15px 20px; background: white; border-top: 1px solid #e2e8f0; }
    
    .msg-bubble { max-width: 75%; padding: 12px 16px; border-radius: 18px; margin-bottom: 15px; position: relative; font-size: 0.95rem; }
    .msg-received { background: white; border: 1px solid #e2e8f0; border-bottom-left-radius: 4px; align-self: flex-start; float: left; clear: both;}
    .msg-sent { background: #1e3c72; color: white; border-bottom-right-radius: 4px; align-self: flex-end; float: right; clear: both;}
    
    .msg-time { font-size: 11px; margin-top: 5px; opacity: 0.7; text-align: right; display: block; }
    .msg-sent .msg-time { color: rgba(255,255,255,0.8); }
    .msg-received .msg-time { color: #94a3b8; }
    
    .st-photo-chat { width: 40px; height: 40px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.5); margin-right: 15px; }
    .btn-send { background: #1e3c72; color: white; border-radius: 30px; padding: 10px 25px; font-weight: bold; border: none; transition: transform 0.2s;}
    .btn-send:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(30,60,114,0.3); color: white;}
    .chat-input { border-radius: 30px; padding: 12px 20px; background: #f1f5f9; border: 1px solid #e2e8f0; }
    .chat-input:focus { background: #fff; box-shadow: 0 0 0 3px rgba(30,60,114,0.1); border-color: #1e3c72; }
</style>

<div class="row justify-content-center">
    <div class="col-md-8">
        <a href="{{ route('teacher.chat.index') }}" class="btn btn-light mb-3 btn-sm border" style="border-radius: 20px;"><i class="icon-arrow-left8 mr-1"></i> Back to Messages</a>
        
        <div class="chat-container">
            <div class="chat-header">
                <img src="{{ $student->photo }}" class="st-photo-chat" alt="photo">
                <div>
                    <h5 class="mb-0 font-weight-bold">{{ $student->name }}</h5>
                    <span class="font-size-sm opacity-75">Student</span>
                </div>
            </div>

            <div class="chat-body" id="chatBody">
                @forelse($messages as $m)
                    <div class="msg-bubble {{ $m->sender_type == 'teacher' ? 'msg-sent' : 'msg-received' }}">
                        {{ $m->message }}
                        <span class="msg-time">{{ $m->created_at->format('M d, g:i a') }}</span>
                    </div>
                @empty
                    <div class="text-center text-muted" style="margin-top: 150px;">
                        <i class="icon-bubbles3 mb-2" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p>No messages yet. Send a message to start the conversation.</p>
                    </div>
                @endforelse
            </div>

            <div class="chat-footer">
                <form action="{{ route('teacher.chat.send', $student->id) }}" method="POST">
                    @csrf
                    <div class="d-flex align-items-center">
                        <input type="text" name="message" class="form-control chat-input mr-3" placeholder="Type your message here..." required autocomplete="off">
                        <button type="submit" class="btn btn-send"><i class="icon-paperplane"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var chatBody = document.getElementById("chatBody");
        chatBody.scrollTop = chatBody.scrollHeight;
    });
</script>
@endsection
