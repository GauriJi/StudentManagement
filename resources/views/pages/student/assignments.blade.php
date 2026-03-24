@extends('layouts.master')
@section('page_title', 'Assignments')
@section('content')
<style>
.assign-hero{background:linear-gradient(135deg,#1e3a5f,#0f172a);border-radius:16px;padding:24px 28px;color:#fff;margin-bottom:24px;box-shadow:0 8px 32px rgba(0,0,0,.2);}
.assign-card{border-radius:14px;border:none;box-shadow:0 4px 16px rgba(0,0,0,.07);margin-bottom:14px;transition:transform .2s;}
.assign-card:hover{transform:translateY(-2px);}
.assign-card .left-bar{width:5px;border-radius:14px 0 0 14px;}
.status-pending  .left-bar{background:#f59e0b;}
.status-submitted .left-bar{background:#3b82f6;}
.status-graded .left-bar{background:#10b981;}
.badge-pending  {background:#fef3c7;color:#92400e;font-weight:700;}
.badge-submitted{background:#dbeafe;color:#1e40af;font-weight:700;}
.badge-graded   {background:#d1fae5;color:#065f46;font-weight:700;}
.assign-title{font-weight:700;color:#1e3a5f;margin-bottom:4px;}
.assign-meta{font-size:.8rem;color:#94a3b8;}
</style>

<div class="assign-hero d-flex align-items-center" style="gap:16px">
    <i class="icon-pencil7" style="font-size:2rem;color:#fbbf24"></i>
    <div>
        <h4 class="mb-0 font-weight-bold">Assignments</h4>
        <small style="opacity:.7">Session: {{ $session }}</small>
    </div>
    <div class="ml-auto d-flex" style="gap:12px">
        @foreach(['pending'=>$assignments->where('status','pending')->count(),'submitted'=>$assignments->where('status','submitted')->count(),'graded'=>$assignments->where('status','graded')->count()] as $s=>$cnt)
        <div style="text-align:center;background:rgba(255,255,255,.1);border-radius:10px;padding:8px 16px">
            <div style="font-size:1.4rem;font-weight:800">{{ $cnt }}</div>
            <div style="font-size:.72rem;opacity:.8">{{ ucfirst($s) }}</div>
        </div>
        @endforeach
    </div>
</div>

@forelse($assignments as $a)
<div class="assign-card card status-{{ $a->status }}">
    <div class="d-flex">
        <div class="left-bar"></div>
        <div class="card-body py-3" style="flex:1">
            <div class="d-flex align-items-start justify-content-between flex-wrap" style="gap:8px">
                <div>
                    <div class="assign-title">{{ $a->title }}</div>
                    <div class="assign-meta">
                        <i class="icon-graduation2"></i> {{ optional($a->subject)->name ?? 'General' }} &bull;
                        By: {{ optional($a->teacher)->name ?? 'Teacher' }}
                        @if($a->due_date)
                        &bull; <i class="icon-calendar3"></i> Due: {{ \Carbon\Carbon::parse($a->due_date)->format('d M Y') }}
                        @endif
                    </div>
                    @if($a->description)
                    <p class="mt-2 mb-0" style="font-size:.85rem;color:#475569">{{ $a->description }}</p>
                    @endif
                </div>
                <span class="badge badge-{{ $a->status }} px-3 py-2" style="border-radius:30px;font-size:.78rem">{{ ucfirst($a->status) }}</span>
            </div>
        </div>
    </div>
</div>
@empty
<div class="text-center py-5">
    <i class="icon-pencil7" style="font-size:3.5rem;color:#cbd5e1"></i>
    <p class="mt-3 text-muted">No assignments yet. Check back later!</p>
</div>
@endforelse
@endsection
