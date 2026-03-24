@extends('layouts.master')
@section('page_title', 'Child Performance')
@section('content')
<style>
.child-switcher a{border-radius:10px;padding:8px 16px;font-weight:600;font-size:13px;transition:all .2s;}
.child-switcher a.active{background:#1e3a5f;color:#fff;}
.grade-A{color:#27ae60;font-weight:700;}
.grade-B{color:#2980b9;font-weight:700;}
.grade-C{color:#f39c12;font-weight:700;}
.grade-D,.grade-F{color:#e74c3c;font-weight:700;}
.score-bar{height:8px;border-radius:6px;background:#e9ecef;margin-top:4px;}
.score-fill{height:8px;border-radius:6px;background:linear-gradient(90deg,#1e3a5f,#3b82f6);}
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="font-weight-bold mb-0"><i class="icon-stats-bars2 mr-2 text-primary"></i>Child Performance</h5>
        <small class="text-muted">Academic marks and score breakdown</small>
    </div>
</div>

{{-- Child Switcher --}}
@if($children->count() > 1)
<div class="child-switcher d-flex flex-wrap gap-2 mb-4">
    @foreach($children as $c)
    <a href="{{ route('parent.performance', $c->user_id) }}"
       class="{{ $c->user_id == $child->user_id ? 'active btn btn-dark' : 'btn btn-light border' }}">
        <img src="{{ $c->user->photo }}" width="20" height="20" class="rounded-circle mr-1">
        {{ $c->user->name }}
    </a>
    @endforeach
</div>
@endif

{{-- Child Info Banner --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:16px;background:linear-gradient(135deg,#1e3a5f,#16213e);color:#fff;">
    <div class="card-body p-4 d-flex align-items-center">
        <img src="{{ $child->user->photo }}" width="60" height="60" class="rounded-circle mr-3 border" style="border-color:rgba(255,255,255,.3)!important;">
        <div class="flex-grow-1">
            <h5 class="font-weight-bold mb-0">{{ $child->user->name }}</h5>
            <small class="opacity-75">{{ $child->my_class->name ?? '—' }} &bull; {{ $child->section->name ?? '' }}</small>
        </div>
        <div class="text-center ml-4">
            <h2 class="font-weight-bold mb-0">{{ $overall_avg }}%</h2>
            <small class="opacity-75">Overall Average</small>
        </div>
    </div>
</div>

{{-- Marks by Exam --}}
@forelse($by_exam as $exam_name => $exam_marks)
<div class="card border-0 shadow-sm mb-3" style="border-radius:14px;">
    <div class="card-header bg-white border-0 pb-0">
        <h6 class="font-weight-bold mb-0"><i class="icon-books mr-2 text-primary"></i>{{ $exam_name ?? 'Exam' }}</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Subject</th>
                        <th class="text-center">Score</th>
                        <th class="text-center">Grade</th>
                        <th width="200">Progress</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($exam_marks as $mark)
                <tr>
                    <td class="font-weight-semibold">{{ $mark->subject->name ?? '—' }}</td>
                    <td class="text-center font-weight-bold">{{ $mark->cum ?? 0 }}</td>
                    <td class="text-center">
                        @php $g = $mark->grade->grade ?? 'N/A'; @endphp
                        <span class="grade-{{ $g }}">{{ $g }}</span>
                    </td>
                    <td>
                        <div class="score-bar">
                            <div class="score-fill" style="width:{{ min($mark->cum ?? 0, 100) }}%"></div>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
                <tfoot class="bg-light">
                    <tr>
                        <td class="font-weight-bold">Average</td>
                        <td class="text-center font-weight-bold text-primary">{{ round($exam_marks->avg('cum')) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@empty
<div class="text-center text-muted py-5">
    <i class="icon-stats-bars2 d-block mb-3" style="font-size:3rem;opacity:.3;"></i>
    No marks recorded yet for {{ $child->user->name }}.
</div>
@endforelse
@endsection
