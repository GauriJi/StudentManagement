@extends('layouts.master')
@section('page_title', 'Parent Dashboard')
@section('content')
<style>
.p-hero{background:linear-gradient(135deg,#1e3a5f,#0f172a);border-radius:20px;padding:32px;color:#fff;margin-bottom:24px;box-shadow:0 10px 30px rgba(0,0,0,.15);position:relative;overflow:hidden;}
.p-hero::after{content:'';position:absolute;top:-60px;right:-60px;width:220px;height:220px;background:rgba(255,255,255,.05);border-radius:50%;}
.child-card{border-radius:18px;border:none;transition:all .3s;overflow:hidden;}
.child-card:hover{transform:translateY(-5px);box-shadow:0 12px 28px rgba(0,0,0,.12)!important;}
.child-avatar{width:65px;height:65px;border-radius:16px;object-fit:cover;border:3px solid rgba(255,255,255,.15);}
.att-bar{height:7px;border-radius:10px;background:#e9ecef;}
.att-fill{height:7px;border-radius:10px;}
.quick-btn{border-radius:12px;padding:12px 16px;font-weight:600;font-size:13px;transition:all .2s;display:flex;align-items:center;margin-bottom:10px;}
.quick-btn i{margin-right:10px;font-size:1.1rem;}
.quick-btn:hover{transform:translateX(3px);}
.notif-item{border-radius:10px;background:#f8fafc;padding:12px 16px;margin-bottom:10px;border-left:4px solid #e2e8f0;}
.notif-item.unread{background:#eff6ff;border-left-color:#3b82f6;}
</style>

{{-- Hero --}}
<div class="p-hero mb-4">
    <div class="row align-items-center">
        <div class="col-auto">
            <img src="{{ Auth::user()->photo }}" class="child-avatar" alt="photo">
        </div>
        <div class="col">
            <h3 class="font-weight-bold mb-1">Welcome, {{ Auth::user()->name }}!</h3>
            <p class="mb-0 opacity-75">{{ date('l, d F Y') }} &nbsp;|&nbsp;
                <i class="icon-users4"></i> {{ $total_children }} {{ $total_children == 1 ? 'child' : 'children' }} enrolled
            </p>
        </div>
        <div class="col-auto d-none d-md-block">
            <a href="{{ route('parent.notifications') }}" class="btn btn-light btn-sm" style="border-radius:10px;">
                <i class="icon-bell2 mr-1"></i>
                @if($recent_notifs->count()) <span class="badge badge-danger">{{ $recent_notifs->count() }}</span> @endif
                Alerts
            </a>
        </div>
    </div>
</div>

<div class="row">
    {{-- Children Cards --}}
    <div class="col-lg-8">
        <h6 class="font-weight-bold text-muted text-uppercase mb-3" style="font-size:11px;letter-spacing:1px;">
            <i class="icon-users4 mr-1"></i> Your Children
        </h6>
        <div class="row">
        @forelse($children as $child)
        <div class="col-md-6 mb-4">
            <div class="card child-card shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $child->user->photo }}" width="50" height="50" class="rounded-circle mr-3 border">
                        <div>
                            <div class="font-weight-bold">{{ $child->user->name }}</div>
                            <small class="text-muted">{{ $child->my_class->name ?? '—' }} &bull; {{ $child->section->name ?? '' }}</small>
                        </div>
                    </div>

                    {{-- Attendance --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Attendance</small>
                            <small class="font-weight-bold {{ $child->att_pct < 75 ? 'text-danger' : 'text-success' }}">{{ $child->att_pct }}%</small>
                        </div>
                        <div class="att-bar">
                            <div class="att-fill {{ $child->att_pct < 75 ? 'bg-danger' : 'bg-success' }}" style="width:{{ $child->att_pct }}%"></div>
                        </div>
                    </div>

                    {{-- Avg Score --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Average Score</small>
                            <small class="font-weight-bold text-primary">{{ $child->avg_score }}%</small>
                        </div>
                        <div class="att-bar">
                            <div class="att-fill bg-primary" style="width:{{ min($child->avg_score, 100) }}%"></div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="row no-gutters mt-3">
                        <div class="col pr-1">
                            <a href="{{ route('parent.performance', $child->user_id) }}" class="btn btn-primary btn-block btn-sm" style="border-radius:8px;">
                                <i class="icon-stats-bars2 mr-1"></i>Performance
                            </a>
                        </div>
                        <div class="col pl-1">
                            <a href="{{ route('parent.fees') }}" class="btn btn-outline-success btn-block btn-sm" style="border-radius:8px;">
                                <i class="icon-wallet mr-1"></i>Fees
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted py-5">
            <i class="icon-users4 d-block mb-3" style="font-size:3rem;opacity:.3;"></i>
            No children enrolled yet.
        </div>
        @endforelse
        </div>
    </div>

    {{-- Quick Actions & Recent Alerts --}}
    <div class="col-lg-4">
        <h6 class="font-weight-bold text-muted text-uppercase mb-3" style="font-size:11px;letter-spacing:1px;">
            <i class="icon-lightning mr-1"></i> Quick Actions
        </h6>
        <a href="{{ route('parent.fees') }}" class="quick-btn btn btn-light border w-100">
            <i class="icon-wallet text-success"></i> Fee Payments &amp; Receipts
        </a>
        <a href="{{ route('parent.chat.index') }}" class="quick-btn btn btn-light border w-100">
            <i class="icon-bubbles4 text-primary"></i> Chat with Teacher
        </a>
        <a href="{{ route('parent.notifications') }}" class="quick-btn btn btn-light border w-100">
            <i class="icon-bell2 text-warning"></i> View All Notifications
        </a>
        <a href="{{ route('my_account') }}" class="quick-btn btn btn-light border w-100">
            <i class="icon-user text-secondary"></i> My Account Settings
        </a>

        {{-- Recent Alerts --}}
        @if($recent_notifs->count())
        <h6 class="font-weight-bold text-muted text-uppercase mt-4 mb-3" style="font-size:11px;letter-spacing:1px;">
            <i class="icon-bell2 mr-1"></i> Recent Alerts
        </h6>
        @foreach($recent_notifs as $n)
        <div class="notif-item {{ !$n->is_read ? 'unread' : '' }}">
            <div class="font-weight-semibold font-size-sm">{{ mb_strimwidth($n->title, 0, 45, '...') }}</div>
            <small class="text-muted">{{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}</small>
        </div>
        @endforeach
        <a href="{{ route('parent.notifications') }}" class="btn btn-sm btn-outline-primary btn-block mt-1" style="border-radius:8px;">View All</a>
        @endif
    </div>
</div>
@endsection
