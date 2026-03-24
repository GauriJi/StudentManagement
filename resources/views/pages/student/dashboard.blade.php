@extends('layouts.master')
@section('page_title', 'Student Dashboard')
@section('content')
<style>
.st-hero{background:linear-gradient(135deg,#1e3a5f,#0f172a);border-radius:20px;padding:35px;color:#fff;margin-bottom:30px;box-shadow:0 10px 30px rgba(0,0,0,.15);position:relative;overflow:hidden;}
.st-hero::after{content:'';position:absolute;top:-50px;right:-50px;width:200px;height:200px;background:rgba(255,255,255,.05);border-radius:50%;}
.st-avatar{width:90px;height:90px;border-radius:20px;object-fit:cover;border:3px solid rgba(255,255,255,.2);box-shadow:0 8px 16px rgba(0,0,0,.2);}
.stat-card{border-radius:18px;border:none;transition:all .3s ease;overflow:hidden;height:100%;}
.stat-card:hover{transform:translateY(-5px);box-shadow:0 12px 24px rgba(0,0,0,.1) !important;}
.stat-icon{width:50px;height:50px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin-bottom:15px;}
.quick-link{display:flex;align-items:center;padding:15px;border-radius:12px;background:#f8fafc;margin-bottom:12px;transition:all .2s;color:#334155;border:1px solid #f1f5f9;}
.quick-link:hover{background:#eff6ff;border-color:#bfdbfe;color:#1e40af;text-decoration:none;}
.quick-link i{width:35px;height:35px;border-radius:8px;background:#fff;display:flex;align-items:center;justify-content:center;margin-right:12px;box-shadow:0 2px 4px rgba(0,0,0,.05);}
</style>

<div class="st-hero">
    <div class="row align-items-center">
        <div class="col-md-auto text-center text-md-left mb-3 mb-md-0">
            <img src="{{ Auth::user()->photo }}" class="st-avatar" alt="avatar">
        </div>
        <div class="col-md text-center text-md-left">
            <h2 class="font-weight-bold mb-1">Welcome back, {{ Auth::user()->name }}!</h2>
            <p class="mb-0 opacity-75" style="font-size:1.1rem">
                <i class="icon-graduation2"></i> {{ optional($sr->my_class)->name }} &nbsp; 
                <i class="icon-vcard"></i> Roll: {{ $sr->roll_no }} &nbsp;
                <i class="icon-calendar3"></i> Session: {{ $session }}
            </p>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-sm-6 col-xl-3 mb-4">
        <div class="card stat-card shadow-sm">
            <div class="card-body">
                <div class="stat-icon bg-success-100 text-success"><i class="icon-alarm"></i></div>
                <h3 class="font-weight-bold mb-0">{{ $att_pct }}%</h3>
                <p class="text-muted mb-0">Attendance</p>
                <div class="progress mt-3" style="height:6px;border-radius:10px">
                    <div class="progress-bar bg-success" style="width: {{ $att_pct }}%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3 mb-4">
        <div class="card stat-card shadow-sm">
            <div class="card-body">
                <div class="stat-icon bg-blue-100 text-blue"><i class="icon-stats-bars2"></i></div>
                <h3 class="font-weight-bold mb-0">{{ $avg_score }}%</h3>
                <p class="text-muted mb-0">Average Score</p>
                <div class="progress mt-3" style="height:6px;border-radius:10px">
                    <div class="progress-bar bg-blue" style="width: {{ $avg_score }}%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3 mb-4">
        <div class="card stat-card shadow-sm">
            <div class="card-body">
                <div class="stat-icon bg-orange-100 text-orange"><i class="icon-pencil7"></i></div>
                <h3 class="font-weight-bold mb-0">{{ $pending_tasks }}</h3>
                <p class="text-muted mb-0">Pending Tasks</p>
                <p class="mt-2 mb-0" style="font-size:.8rem"><span class="text-orange">Needs attention</span></p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3 mb-4">
        <div class="card stat-card shadow-sm">
            <div class="card-body">
                <div class="stat-icon bg-purple-100 text-purple"><i class="icon-question7"></i></div>
                <h3 class="font-weight-bold mb-0">{{ $open_doubts }}</h3>
                <p class="text-muted mb-0">Open Doubts</p>
                <p class="mt-2 mb-0" style="font-size:.8rem"><span class="text-purple">Waiting for reply</span></p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius:18px">
            <div class="card-header bg-white py-3">
                <h6 class="card-title font-weight-bold mb-0"><i class="icon-calendar52 text-primary"></i> Academic Schedule</h6>
            </div>
            <div class="card-body">
                <div class="row no-gutters text-center border rounded overflow-hidden">
                    <div class="col p-3 border-right bg-light">
                        <div class="text-muted small mb-1">Status</div>
                        <div class="badge badge-success">Ongoing Session</div>
                    </div>
                    <div class="col p-3 border-right">
                        <div class="text-muted small mb-1">Exams</div>
                        <div class="font-weight-bold">Upcoming: Finals</div>
                    </div>
                    <div class="col p-3">
                        <div class="text-muted small mb-1">Today's Class</div>
                        <div class="font-weight-bold text-primary">English, Math, Sci</div>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('student.timetable') }}" class="btn btn-primary btn-block py-2" style="border-radius:10px">View Full Timetable</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius:18px">
            <div class="card-header bg-white py-3">
                <h6 class="card-title font-weight-bold mb-0">Quick Access</h6>
            </div>
            <div class="card-body p-3">
                <a href="{{ route('student.notes') }}" class="quick-link">
                    <i class="icon-file-text2 text-info"></i>
                    <span>Latest Study Notes</span>
                </a>
                <a href="{{ route('student.progress') }}" class="quick-link">
                    <i class="icon-stats-bars2 text-success"></i>
                    <span>My Progress Report</span>
                </a>
                <a href="{{ route('student.chat.index') }}" class="quick-link">
                    <i class="icon-bubbles4 text-warning"></i>
                    <span>Teacher Chat</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
