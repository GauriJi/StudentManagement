@extends('layouts.master')
@section('page_title', 'Exam Performance')
@section('content')
<style>
.exam-hero{background:linear-gradient(135deg,#1e3a5f,#0f172a);border-radius:16px;padding:24px 28px;color:#fff;margin-bottom:24px;box-shadow:0 8px 32px rgba(0,0,0,.2);}
.exam-tab .nav-link{border:none;border-radius:10px;padding:12px 20px;font-weight:700;color:#64748b;margin-right:10px;transition:all .3s;}
.exam-tab .nav-link.active{background:linear-gradient(135deg,#3b82f6,#1d4ed8);color:#fff;box-shadow:0 4px 12px rgba(59,130,246,.3);}
.subject-row{background:#fff;border-radius:14px;padding:18px 20px;margin-bottom:12px;border:1px solid #f1f5f9;display:flex;align-items:center;justify-content:between;transition:all .2s;}
.subject-row:hover{border-color:#3b82f6;transform:scale(1.005);}
.mark-box{width:60px;height:60px;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:800;background:#f8fafc;}
.grade-badge{padding:4px 10px;border-radius:6px;font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:0.5px;}
</style>

<div class="exam-hero">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="font-weight-bold mb-1">Academic Performance</h3>
            <p class="mb-0 opacity-75">Session: {{ Qs::getCurrentSession() }}</p>
        </div>
        <div class="col-auto">
            <div class="d-flex" style="gap:20px">
                <div class="text-center">
                    <div style="font-size:1.5rem;font-weight:800">{{ $marks->count() }}</div>
                    <div style="font-size:.75rem;opacity:.7">Exams Taken</div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($marks->count() > 0)
<ul class="nav nav-tabs exam-tab border-0 mb-4" id="examTabs">
    @foreach($marks as $exam_id => $m)
    <li class="nav-item">
        <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-toggle="tab" href="#exam-{{ $exam_id }}">
            {{ optional($m->first()->exam)->name }}
        </a>
    </li>
    @endforeach
</ul>

<div class="tab-content">
    @foreach($marks as $exam_id => $group)
    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="exam-{{ $exam_id }}">
        @foreach($group as $m)
        <div class="subject-row shadow-sm">
            <div class="d-flex align-items-center" style="gap:15px;flex:1">
                <div class="bg-light p-2 rounded-circle" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center">
                    <i class="icon-notebook text-primary"></i>
                </div>
                <div>
                    <h6 class="font-weight-bold mb-0 text-slate-700">{{ optional($m->subject)->name }}</h6>
                    <small class="text-muted">{{ optional($m->subject)->slug }}</small>
                </div>
            </div>
            
            <div style="flex:1" class="px-4 d-none d-md-block">
                @php $pct = $m->t_mark; @endphp
                <div class="progress" style="height:8px;border-radius:10px">
                    @php $color = $pct >= 80 ? 'bg-success' : ($pct >= 60 ? 'bg-primary' : ($pct >= 40 ? 'bg-warning' : 'bg-danger')); @endphp
                    <div class="progress-bar {{ $color }}" style="width: {{ $pct }}%"></div>
                </div>
            </div>

            <div class="d-flex align-items-center" style="gap:20px">
                <div class="mark-box">
                    <span style="font-size:1.1rem;color:#1e3a5f">{{ $m->t_mark ?? '-' }}</span>
                    <span style="font-size:.65rem;color:#94a3b8">Total</span>
                </div>
                <div class="text-right d-none d-sm-block">
                    @php $grade = $m->grade ? $m->grade->name : 'N/A'; @endphp
                    <span class="grade-badge bg-blue-100 text-blue">{{ $grade }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach
</div>
@else
<div class="text-center py-5">
    <i class="icon-books" style="font-size:4rem;color:#cbd5e1"></i>
    <h5 class="mt-3 text-muted">No exam results published yet.</h5>
</div>
@endif
@endsection
