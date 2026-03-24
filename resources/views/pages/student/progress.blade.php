@extends('layouts.master')
@section('page_title', 'My Progress')
@section('content')
<style>
.prog-hero{background:linear-gradient(135deg,#1e3a5f,#0f172a);border-radius:16px;padding:28px;color:#fff;margin-bottom:28px;box-shadow:0 8px 32px rgba(0,0,0,.2);}
.ring-wrap{position:relative;width:120px;height:120px;margin:0 auto 12px;}
.ring-wrap svg{transform:rotate(-90deg);}
.ring-label{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);font-size:1.25rem;font-weight:800;color:#1e3a5f;}
.prog-card{border-radius:16px;border:none;box-shadow:0 4px 20px rgba(0,0,0,.08);height:100%;overflow:hidden;}
.prog-card .p-header{padding:16px 20px;font-weight:700;color:#fff;font-size:.92rem;}
.prog-card .p-body{padding:18px 20px;}
.subject-bar{margin-bottom:14px;}
.subject-bar .s-name{font-size:.85rem;font-weight:600;color:#374151;margin-bottom:4px;}
.bar-bg{background:#e5e7eb;border-radius:100px;height:9px;overflow:hidden;}
.bar-fill{height:9px;border-radius:100px;transition:width .8s ease;}
.extra-badge{display:inline-flex;align-items:center;gap:6px;background:#f1f5f9;border-radius:10px;padding:8px 14px;font-size:.83rem;font-weight:600;margin:4px;color:#475569;}
.extra-badge i{font-size:1.1rem;}
</style>

<div class="prog-hero">
    <h4 class="font-weight-bold mb-1"><i class="icon-stats-bars2"></i> My Progress Overview</h4>
    <small style="opacity:.7">Session: {{ $session }} &bull; {{ optional(optional($sr)->my_class)->name }}</small>
</div>

{{-- Ring charts row --}}
<div class="row mb-4 text-center">
    @foreach([
        [$attPct,'#10b981','Attendance %'],
        [$avgScore,'#3b82f6','Avg Score %'],
        [$assignPct,'#f59e0b','Tasks Done %'],
        [$doubtsTotal ? round($doubtsAns/$doubtsTotal*100) : 0,'#8b5cf6','Doubts Answered'],
    ] as [$val,$color,$label])
    <div class="col-6 col-md-3 mb-4">
        <div class="card prog-card">
            <div class="p-body">
                @php $r=52; $circ=2*M_PI*$r; $dash=round($circ*min($val,100)/100,2); @endphp
                <div class="ring-wrap">
                    <svg width="120" height="120" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="{{ $r }}" fill="none" stroke="#e5e7eb" stroke-width="10"/>
                        <circle cx="60" cy="60" r="{{ $r }}" fill="none" stroke="{{ $color }}" stroke-width="10"
                            stroke-dasharray="{{ $dash }} {{ round($circ,2) }}" stroke-linecap="round"/>
                    </svg>
                    <div class="ring-label" style="color:{{ $color }}">{{ $val }}%</div>
                </div>
                <p style="font-size:.82rem;color:#64748b;font-weight:600;margin:0">{{ $label }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row">
    {{-- Subject-wise marks --}}
    <div class="col-md-6 mb-4">
        <div class="card prog-card">
            <div class="p-header" style="background:linear-gradient(90deg,#1e3a5f,#1d4ed8);">
                <i class="icon-books"></i> Subject-wise Performance
            </div>
            <div class="p-body">
                @forelse($subjectMarks as $sm)
                @php $c=$sm['avg']>=70?'#10b981':($sm['avg']>=55?'#3b82f6':($sm['avg']>=40?'#f59e0b':'#ef4444')); @endphp
                <div class="subject-bar">
                    <div class="d-flex justify-content-between mb-1">
                        <div class="s-name">{{ $sm['subject'] }}</div>
                        <strong style="color:{{ $c }};font-size:.88rem">{{ $sm['avg'] }}%</strong>
                    </div>
                    <div class="bar-bg"><div class="bar-fill" style="width:{{ min(100,$sm['avg']) }}%;background:{{ $c }}"></div></div>
                </div>
                @empty
                <p class="text-muted text-center py-3">No marks data yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Exams + Extras --}}
    <div class="col-md-6 mb-4">
        {{-- Exam history --}}
        <div class="card prog-card mb-4">
            <div class="p-header" style="background:linear-gradient(90deg,#7c3aed,#6d28d9)">
                <i class="icon-trophy2"></i> Exam History
            </div>
            <div class="p-body" style="padding:0">
                @forelse($examRecords as $er)
                <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-bottom:1px solid #f1f5f9">
                    <span style="font-size:.88rem;font-weight:600;color:#1e3a5f">{{ optional($er->exam)->name ?? 'Exam' }}</span>
                    <div class="d-flex" style="gap:16px">
                        <div class="text-center">
                            <div style="font-size:1.1rem;font-weight:800;color:#3b82f6">{{ $er->ave ?? '—' }}%</div>
                            <div style="font-size:.7rem;color:#94a3b8">Avg</div>
                        </div>
                        <div class="text-center">
                            <div style="font-size:1.1rem;font-weight:800;color:#f59e0b">{{ $er->pos ?? '—' }}</div>
                            <div style="font-size:.7rem;color:#94a3b8">Rank</div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center py-3">No exam records yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Extra-curricular placeholder --}}
        <div class="card prog-card">
            <div class="p-header" style="background:linear-gradient(90deg,#ea580c,#dc2626)">
                <i class="icon-medal2"></i> Extra-Curricular &amp; Sports
            </div>
            <div class="p-body">
                <p style="font-size:.82rem;color:#64748b;margin-bottom:12px">Activities &amp; achievements recognised for this session:</p>
                @foreach([['icon-trophy2','Sports','Active participant in school sports'],['icon-music2','Arts & Crafts','Enrolled in drawing club'],['icon-users4','House Points','Participating in house events'],['icon-badge','Behaviour','Good conduct badge']] as [$ico,$title,$desc])
                <div class="extra-badge" style="width:100%;margin-bottom:6px">
                    <i class="{{ $ico }}" style="color:#ea580c"></i>
                    <div>
                        <div style="font-weight:700;font-size:.82rem">{{ $title }}</div>
                        <div style="font-size:.74rem;color:#94a3b8">{{ $desc }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
