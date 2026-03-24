@extends('layouts.master')
@section('page_title', 'Chat with Students')
@section('content')

<style>
    .glass-card { background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(0,0,0,0.05); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .student-list-item { border-radius: 8px; transition: all 0.2s; border: 1px solid transparent; margin-bottom: 8px; }
    .student-list-item:hover { background-color: #f8fafc; border-color: #e2e8f0; transform: translateX(5px); }
    .st-photo-med { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0; }
    .badge-unread { background-color: #ef4444; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.75rem; }
</style>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card glass-card">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2">
                <h5 class="mb-0 font-weight-bold text-primary"><i class="icon-bubbles3 mr-2"></i> Messages</h5>
                <p class="text-muted mb-0">Select a student to start chatting.</p>
            </div>

            <div class="card-body">
                
                @if($recent_chats->count() > 0)
                    <h6 class="font-weight-bold text-muted mb-3 text-uppercase font-size-sm">Recent Conversations</h6>
                    <div class="mb-4">
                        @foreach($recent_chats as $chat)
                            <a href="{{ route('teacher.chat', $chat->student_id) }}" class="text-default">
                                <div class="d-flex align-items-center p-2 student-list-item">
                                    <img src="{{ $chat->student->photo }}" class="st-photo-med mr-3" alt="photo">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 font-weight-bold">{{ $chat->student->name }}</h6>
                                        <span class="text-muted font-size-sm text-truncate d-block" style="max-width: 250px;">{{ $chat->message }}</span>
                                    </div>
                                    @if(!$chat->is_read && $chat->sender_type == 'student')
                                        <span class="badge-unread ml-auto">New</span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif

                <h6 class="font-weight-bold text-muted mb-3 text-uppercase font-size-sm">All My Students</h6>
                <div class="row">
                    @forelse($students as $st)
                        <div class="col-md-6">
                            <a href="{{ route('teacher.chat', $st->user->id) }}" class="text-default">
                                <div class="d-flex align-items-center p-2 student-list-item">
                                    <img src="{{ $st->user->photo }}" class="st-photo-med mr-3" alt="photo">
                                    <div>
                                        <h6 class="mb-0 font-weight-bold">{{ $st->user->name }}</h6>
                                        <span class="text-muted font-size-sm">{{ $st->my_class->name }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted py-4">
                            No students found.
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
