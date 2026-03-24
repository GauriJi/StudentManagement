@extends('layouts.master')
@section('page_title', 'My Students')
@section('content')

<style>
    .glass-card { background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(0,0,0,0.05); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .nav-tabs-highlight .nav-link.active { border-bottom-color: #2a5298; color: #2a5298; font-weight: bold; }
    .student-card { border-radius: 10px; border: 1px solid #f1f5f9; transition: all 0.2s ease-in-out; }
    .student-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); border-color: #e2e8f0; }
    .st-photo { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0; padding: 2px; }
    .btn-action { border-radius: 20px; font-size: 0.8rem; font-weight: bold; background: #f8fafc; color: #475569; transition: all 0.2s; border: 1px solid transparent; }
    .btn-action:hover { background: #2a5298; color: white; border-color: #2a5298; }
    .btn-action.btn-msg:hover { background: #0ea5e9; color: white; border-color: #0ea5e9; }
</style>

<div class="card glass-card">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h5 class="mb-0 font-weight-bold text-primary"><i class="icon-users4 mr-2"></i> My Students</h5>
    </div>

    <div class="card-body">
        @if($my_classes->count() > 0)
        <ul class="nav nav-tabs nav-tabs-highlight nav-justified mb-4">
            @foreach($my_classes as $mc)
                <li class="nav-item">
                    <a href="#class{{ $mc->id }}" class="nav-link {{ $loop->first ? 'active' : '' }}" data-toggle="tab">
                        <i class="icon-graduation2 mr-2"></i> {{ $mc->name }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content">
            @foreach($my_classes as $mc)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="class{{ $mc->id }}">
                    <div class="row">
                        @forelse($students->where('my_class_id', $mc->id) as $s)
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="card student-card h-100 p-3 mb-0">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $s->user->photo }}" class="st-photo mr-3" alt="photo">
                                    <div style="overflow: hidden;">
                                        <h6 class="mb-0 font-weight-bold text-dark text-truncate" title="{{ $s->user->name }}">{{ $s->user->name }}</h6>
                                        <span class="text-muted font-size-sm">ADM: {{ $s->adm_no }}</span>
                                    </div>
                                </div>
                                <div class="mt-3 d-flex justify-content-between align-items-center border-top pt-2">
                                    <a href="{{ route('teacher.chat', $s->user->id) }}" class="btn btn-sm btn-action btn-msg text-info"><i class="icon-bubbles3 mr-1"></i> Message</a>
                                    <a href="{{ route('students.show', Qs::hash($s->id)) }}" class="btn btn-sm btn-action"><i class="icon-eye mr-1"></i> Profile</a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5">
                            <i class="icon-user-block text-light" style="font-size: 3rem;"></i>
                            <h5 class="mt-2 text-muted">No Students Found</h5>
                        </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
        @else
            <div class="alert bg-light border-0 d-flex align-items-center">
                <i class="icon-info22 text-primary mr-3" style="font-size: 2rem;"></i>
                <div>
                    <h5 class="mb-1 font-weight-bold">No Classes Assigned</h5>
                    <p class="mb-0 text-muted">Students will appear here once you are assigned to a class.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
