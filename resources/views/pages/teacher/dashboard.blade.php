@extends('layouts.master')
@section('page_title', 'Teacher Dashboard')

@section('content')
<style>
    .t-card { border: none; border-radius: 12px; transition: transform 0.2s; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    .t-card:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(0,0,0,0.1); }
    .bg-gradient-t1 { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; }
    .bg-gradient-t2 { background: linear-gradient(135deg, #FF416C 0%, #FF4B2B 100%); color: white; }
    .bg-gradient-t3 { background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%); color: white; }
    .bg-gradient-t4 { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; }
    .stat-icon { font-size: 2.5rem; opacity: 0.8; }
    .class-badge { background: #f0f4f8; border-left: 4px solid #1e3c72; padding: 10px; border-radius: 4px; margin-bottom: 10px; }
</style>

<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card t-card bg-gradient-t1 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0 font-weight-bold">{{ $subjects->count() }}</h3>
                    <span class="text-uppercase text-white font-size-sm">My Subjects</span>
                </div>
                <i class="icon-books stat-icon"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card t-card bg-gradient-t2 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0 font-weight-bold">{{ $total_students }}</h3>
                    <span class="text-uppercase text-white font-size-sm">Total Students</span>
                </div>
                <i class="icon-users4 stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card t-card bg-gradient-t3 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0 font-weight-bold">{{ $pending_doubts ?? 0 }}</h3>
                    <span class="text-uppercase text-white font-size-sm">Pending Doubts</span>
                </div>
                <i class="icon-bubbles4 stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card t-card bg-gradient-t4 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0 font-weight-bold">{{ $unread_chats ?? 0 }}</h3>
                    <span class="text-uppercase text-white font-size-sm">Unread Msgs</span>
                </div>
                <i class="icon-bubbles3 stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white header-elements-inline">
                <h6 class="card-title font-weight-bold"><i class="icon-graduation2 mr-2"></i> My Classes & Subjects</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($my_classes as $mc)
                    <div class="col-md-6 text-left">
                        <div class="class-badge d-flex justify-content-between align-items-center bg-light border-left-success">
                            <div>
                                <span class="d-block font-weight-bold text-success">{{ $mc->name }}</span>
                                <span class="text-muted font-size-sm">Class Teacher</span>
                            </div>
                            <a href="{{ route('teacher.students') }}" class="btn btn-sm btn-success border"><i class="icon-users mr-1"></i> Students</a>
                        </div>
                    </div>
                    @empty
                    @endforelse

                    @forelse($subjects as $s)
                    <div class="col-md-6 text-left">
                        <div class="class-badge d-flex justify-content-between align-items-center">
                            <div>
                                <span class="d-block font-weight-bold text-primary">{{ $s->my_class->name }}</span>
                                <span class="text-muted font-size-sm">{{ $s->name }}</span>
                            </div>
                            <a href="{{ route('teacher.students') }}" class="btn btn-sm btn-light border"><i class="icon-users mr-1"></i> View</a>
                        </div>
                    </div>
                    @empty
                    @if($my_classes->count() == 0)
                    <div class="col-12 text-center text-muted py-3">No classes or subjects assigned yet.</div>
                    @endif
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white pb-0 border-0">
                <h6 class="card-title font-weight-bold"><i class="icon-calendar mr-2"></i> Quick Actions</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('teacher.attendance') }}" class="btn btn-block btn-outline-primary mb-2 text-left"><i class="icon-calendar mr-2"></i> Mark Attendance</a>
                <a href="{{ route('teacher.assignments') }}" class="btn btn-block btn-outline-danger mb-2 text-left"><i class="icon-book2 mr-2"></i> Add Assignment</a>
                <a href="{{ route('teacher.study_materials') }}" class="btn btn-block btn-outline-success mb-2 text-left"><i class="icon-file-pdf mr-2"></i> Upload Notes</a>
                <a href="{{ route('teacher.exams') }}" class="btn btn-block btn-outline-info text-left"><i class="icon-books mr-2"></i> Manage Marks</a>
            </div>
        </div>
    </div>
</div>
@endsection
