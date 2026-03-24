@extends('layouts.master')
@section('page_title', 'Teacher Substitution')
@section('content')
<style>
.teacher-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:999px;border:1.5px solid #e9ecef;font-size:12px;font-weight:600;}
.teacher-badge.free{background:#d1fae5;border-color:#6ee7b7;color:#065f46;}
.teacher-badge.busy{background:#fee2e2;border-color:#fca5a5;color:#991b1b;}
.subj-row{border-radius:12px;margin-bottom:6px;padding:12px 16px;border:1px solid #f0f0f0;background:#fff;transition:box-shadow .2s;}
.subj-row:hover{box-shadow:0 4px 12px rgba(0,0,0,.07);}
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="font-weight-bold mb-0"><i class="icon-shuffle mr-2" style="color:#8e44ad;"></i>Teacher Substitution System</h5>
        <small class="text-muted">Manage temporary teacher replacements &mdash; {{ $today }}</small>
    </div>
</div>

@if(session('flash_success'))
    <div class="alert alert-success border-0 alert-dismissible"><button class="close" data-dismiss="alert">&times;</button>{{ session('flash_success') }}</div>
@endif

{{-- Summary: Free Teachers --}}
<div class="card border-0 shadow-sm mb-3" style="border-radius:14px;">
    <div class="card-body py-3">
        <strong class="text-success mr-2"><i class="icon-user-check mr-1"></i>Teachers Currently Free</strong>
        @forelse($free_teachers as $ft)
            <span class="teacher-badge free mr-1 mb-1">
                <img src="{{ $ft->photo }}" width="20" height="20" class="rounded-circle">{{ $ft->name }}
            </span>
        @empty
            <span class="text-muted">All teachers are currently assigned to subjects.</span>
        @endforelse
    </div>
</div>

{{-- Subject-wise teacher assignment --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
        <h6 class="font-weight-bold mb-0">Subjects & Assigned Teachers — Assign Substitute</h6>
        <span class="badge badge-info">{{ $subjects->count() }} subjects</span>
    </div>
    <div class="card-body">
        @forelse($subjects as $subject)
        <div class="subj-row">
            <div class="row align-items-center">
                {{-- Subject & Class --}}
                <div class="col-md-3">
                    <div class="font-weight-semibold">{{ $subject->name }}</div>
                    <small class="text-muted">{{ $subject->my_class->name ?? '—' }}</small>
                </div>
                {{-- Current Teacher --}}
                <div class="col-md-3">
                    @if($subject->teacher)
                        <span class="teacher-badge busy">
                            <img src="{{ $subject->teacher->photo }}" width="20" height="20" class="rounded-circle">
                            {{ $subject->teacher->name }}
                        </span>
                    @else
                        <span class="text-muted">— No teacher assigned</span>
                    @endif
                </div>
                {{-- Assign Substitute --}}
                <div class="col-md-5">
                    <div class="d-flex align-items-center">
                        <select class="form-control form-control-sm mr-2" style="border-radius:8px;">
                            <option value="">— Select Substitute —</option>
                            @foreach($free_teachers as $ft)
                            <option value="{{ $ft->id }}">{{ $ft->name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-sm btn-success px-3" style="border-radius:8px;white-space:nowrap;"
                            onclick="this.closest('.subj-row').style.background='#f0fdf4'; this.textContent='✓ Assigned';">
                            Assign
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center text-muted py-5">
            <i class="icon-book2" style="font-size:3rem;opacity:.3;"></i>
            <p class="mt-2">No subjects found. <a href="{{ route('subjects.index') }}">Add subjects</a> first.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- Active Timetables for reference --}}
<div class="card border-0 shadow-sm mb-3" style="border-radius:16px;">
    <div class="card-header bg-white border-0">
        <h6 class="font-weight-bold mb-0"><i class="icon-table2 mr-2 text-info"></i>Current Session Timetables</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light"><tr><th>Class</th><th>Exam</th><th>Periods (Slots)</th></tr></thead>
                <tbody>
                @forelse($timetables as $ttr)
                <tr>
                    <td class="font-weight-semibold">{{ $ttr->my_class->name ?? '—' }}</td>
                    <td>{{ $ttr->exam->name ?? '—' }}</td>
                    <td><a href="{{ route('ttr.show', $ttr->id) }}" class="btn btn-xs btn-outline-info" style="border-radius:6px;font-size:12px;">View Slots</a></td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted py-3">No timetables for current session. <a href="{{ route('tt.index') }}">Create one</a>.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- All Teachers Status --}}
<div class="card border-0 shadow-sm" style="border-radius:16px;">
    <div class="card-header bg-white border-0">
        <h6 class="font-weight-bold mb-0">All Teachers — Subject Load</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light"><tr><th>Teacher</th><th>Subjects Assigned</th><th>Status</th></tr></thead>
                <tbody>
                @foreach($teachers as $t)
                @php $subjectCount = $subjects->where('teacher_id', $t->id)->count(); @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ $t->photo }}" width="30" height="30" class="rounded-circle mr-2 border">
                            <span class="font-weight-semibold">{{ $t->name }}</span>
                        </div>
                    </td>
                    <td>{{ $subjectCount }} {{ $subjectCount == 1 ? 'subject' : 'subjects' }}</td>
                    <td>
                        @if($subjectCount > 0)
                            <span class="badge badge-warning">Busy ({{ $subjectCount }})</span>
                        @else
                            <span class="badge badge-success">Free</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
