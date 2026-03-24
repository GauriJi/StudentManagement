@extends('layouts.master')
@section('page_title', 'My Attendance')
@section('content')
<style>
.att-hero{background:linear-gradient(135deg,#1e3a5f,#0f172a);border-radius:16px;padding:24px 28px;color:#fff;margin-bottom:24px;box-shadow:0 8px 32px rgba(0,0,0,.2);}
.att-card{border-radius:14px;border:none;box-shadow:0 4px 16px rgba(0,0,0,.08);height:100%;}
.circle-progress{width:70px;height:70px;border-radius:50%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;position:relative;margin:0 auto 10px;}
.circle-label{font-weight:800;font-size:1.1rem;color:#1e3a5f;}
.cal-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:10px;}
.cal-day{aspect-ratio:1;border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;border:1px solid #f1f5f9;}
.state-p{background:#dcfce7;color:#166534;border-color:#bbf7d0;} /* Present */
.state-a{background:#fee2e2;color:#991b1b;border-color:#fecaca;} /* Absent */
.state-l{background:#fef9c3;color:#854d0e;border-color:#fef08a;} /* Late */
</style>

<div class="att-hero d-flex align-items-center" style="gap:20px">
    <i class="icon-alarm" style="font-size:2.5rem;color:#fbbf24"></i>
    <div>
        <h3 class="mb-1 font-weight-bold">Attendance Records</h3>
        <p class="mb-0 opacity-75">Academic Session: {{ $session }}</p>
    </div>
</div>

<div class="row mb-4 text-center">
    <div class="col-md-3 mb-3">
        <div class="card att-card p-3">
            <div class="circle-progress"><div class="circle-label text-success">{{ $attendances->where('status', 'present')->count() }}</div></div>
            <p class="text-muted font-weight-bold mb-0">Days Present</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card att-card p-3">
            <div class="circle-progress"><div class="circle-label text-danger">{{ $attendances->where('status', 'absent')->count() }}</div></div>
            <p class="text-muted font-weight-bold mb-0">Days Absent</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card att-card p-3">
            <div class="circle-progress"><div class="circle-label text-warning">{{ $attendances->where('status', 'late')->count() }}</div></div>
            <p class="text-muted font-weight-bold mb-0">Days Late</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card att-card p-3" style="background:#f8fafc">
            @php $pct = $attendances->count() > 0 ? round(($attendances->where('status','present')->count() / $attendances->count())*100) : 0; @endphp
            <div class="circle-progress" style="background:#fff"><div class="circle-label text-primary">{{ $pct }}%</div></div>
            <p class="text-muted font-weight-bold mb-0">Overall %</p>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius:18px">
    <div class="card-header bg-white py-3 border-0">
        <h6 class="card-title font-weight-bold mb-0"><i class="icon-calendar3 mr-2"></i> Attendance Calendar (Last 30 Records)</h6>
    </div>
    <div class="card-body">
        <div class="cal-grid">
            @foreach($attendances->take(-30) as $att)
            @php $sClass = $att->status == 'present' ? 'state-p' : ($att->status == 'absent' ? 'state-a' : 'state-l'); @endphp
            <div class="cal-day {{ $sClass }}" title="{{ $att->date }}">
                {{ \Carbon\Carbon::parse($att->date)->format('d') }}
            </div>
            @endforeach
        </div>
        <div class="mt-4 d-flex justify-content-center" style="gap:20px;font-size:.85rem">
            <span><i class="icon-primitive-dot text-success"></i> Present</span>
            <span><i class="icon-primitive-dot text-danger"></i> Absent</span>
            <span><i class="icon-primitive-dot text-warning"></i> Late</span>
        </div>
    </div>
</div>
@endsection
