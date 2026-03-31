@extends('layouts.master')
@section('page_title', 'My Notifications')
@section('content')

<style>
    .glass-card { background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(0,0,0,0.05); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .notif-item { border-radius: 8px; transition: all 0.2s; border: 1px solid transparent; padding: 15px; margin-bottom: 10px; background: #fff; border: 1px solid #f1f5f9;}
    .notif-item:hover { box-shadow: 0 4px 10px rgba(0,0,0,0.05); transform: translateY(-2px); border-color: #e2e8f0; }
    .notif-unread { background: #f8fafc; border-left: 4px solid #1e3c72; }
    .notif-icon-box { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: #e0e7ff; color: #1e3c72; font-size: 1.5rem; margin-right: 20px;}
    .btn-mark-read { cursor: pointer; color: #1e3c72; font-weight: bold; font-size: 0.85rem; }
    .btn-mark-read:hover { text-decoration: underline; }
</style>

<div class="row">
    <div class="col-md-9 mx-auto">
        <div class="card glass-card">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-primary"><i class="icon-bell3 mr-2"></i> All Notifications</h5>
                @if($notifications->where('is_read', false)->count() > 0)
                    <a href="#" id="mark-all-read" class="btn-mark-read"><i class="icon-checkmark3 mr-1"></i> Mark all as read</a>
                @endif
            </div>

            <div class="card-body">
                @forelse($notifications as $n)
                    <div class="d-flex notif-item {{ !$n->is_read ? 'notif-unread' : '' }}">
                        <div class="notif-icon-box">
                            <i class="icon-bell2"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 font-weight-bold {{ !$n->is_read ? 'text-dark' : 'text-muted' }}">{{ $n->title }}</h6>
                            <p class="mb-1 text-muted">{{ $n->message }}</p>
                            <span class="text-muted font-size-sm"><i class="icon-alarm mr-1"></i> {{ $n->created_at->diffForHumans() }}</span>
                        </div>
                        @if(!$n->is_read)
                            <div class="ml-3">
                                <span class="badge badge-primary badge-pill">New</span>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="icon-bell3 text-light" style="font-size: 3.5rem;"></i>
                        <h4 class="mt-3 text-muted font-weight-bold">No Notifications</h4>
                        <p class="text-muted">You're all caught up!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let markReadBtn = document.getElementById('mark-all-read');
        if(markReadBtn) {
            markReadBtn.addEventListener('click', function(e) {
                e.preventDefault();
                fetch('{{ route("teacher.notifications.markread") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        location.reload();
                    }
                });
            });
        }
    });
</script>

@endsection
