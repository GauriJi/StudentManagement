@extends('layouts.master')
@section('page_title', 'Class Timetable')
@section('content')
<style>
.tt-hero{background:linear-gradient(135deg,#0f172a,#1e3a5f);border-radius:16px;padding:24px 28px;color:#fff;margin-bottom:24px;box-shadow:0 8px 32px rgba(0,0,0,.2);}
.tt-table{width:100%;border-collapse:separate;border-spacing:0;border-radius:14px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08);}
.tt-table thead th{background:linear-gradient(90deg,#1e3a5f,#1d4ed8);color:#fff;padding:14px 12px;font-size:.85rem;font-weight:700;text-align:center;border:none;}
.tt-table tbody tr:nth-child(even){background:#f8fafc;}
.tt-table tbody tr:nth-child(odd){background:#fff;}
.tt-table td{padding:14px 12px;text-align:center;font-size:.85rem;border-bottom:1px solid #f1f5f9;vertical-align:middle;}
.tt-table td:first-child{font-weight:700;color:#1e3a5f;background:#eff6ff;border-right:2px solid #dbeafe;}
.slot-pill{display:inline-block;background:linear-gradient(135deg,#dbeafe,#bfdbfe);color:#1d4ed8;border-radius:8px;padding:4px 10px;font-size:.78rem;font-weight:600;}
</style>

<div class="tt-hero d-flex align-items-center" style="gap:16px">
    <i class="icon-calendar3" style="font-size:2rem;color:#38bdf8"></i>
    <div>
        <h4 class="mb-0 font-weight-bold">Class Timetable</h4>
        <small style="opacity:.7">
            {{ optional(optional($sr)->my_class)->name }}
            @if($ttr) &bull; {{ $ttr->name }} @endif
        </small>
    </div>
</div>

@if($ttr && $timeSlots->count())
<div class="card" style="border-radius:16px;border:none;box-shadow:0 4px 20px rgba(0,0,0,.08);overflow:hidden">
    <div class="card-body p-0">
        <table class="tt-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Time From</th>
                    <th>Time To</th>
                </tr>
            </thead>
            <tbody>
                @foreach($timeSlots as $i => $slot)
                @php
                    $from = $slot->time_from ?? ($slot->hour_from.':'.$slot->min_from.' '.$slot->meridian_from);
                    $to   = $slot->time_to   ?? ($slot->hour_to.':'.$slot->min_to.' '.$slot->meridian_to);
                @endphp
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td><span class="slot-pill">{{ $from }}</span></td>
                    <td><span class="slot-pill">{{ $to }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="text-center py-5">
    <i class="icon-calendar3" style="font-size:3.5rem;color:#cbd5e1"></i>
    <p class="mt-3 text-muted">No timetable has been set up for your class yet.</p>
</div>
@endif
@endsection
