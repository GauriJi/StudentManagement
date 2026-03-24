@extends('layouts.master')
@section('page_title', 'Academic Calendar')
@section('content')
<style>
.cal-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:4px;}
.cal-cell{min-height:80px;border:1px solid #f0f0f0;border-radius:8px;padding:5px;background:#fff;position:relative;}
.cal-cell.today{border-color:#3498db;background:#f0f7ff;}
.cal-cell.other-month{background:#f8f9fa;opacity:.5;}
.cal-day-num{font-size:12px;font-weight:700;color:#555;margin-bottom:3px;}
.cal-event-dot{display:block;font-size:10px;border-radius:4px;padding:1px 5px;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#fff;font-weight:600;}
.type-badge-holiday{background:#e74c3c;}
.type-badge-event{background:#3498db;}
.type-badge-exam{background:#f39c12;}
.type-badge-notice{background:#27ae60;}
.day-hdr{text-align:center;font-size:12px;font-weight:700;color:#888;padding:6px 0;}
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="font-weight-bold mb-0"><i class="icon-calendar3 mr-2 text-danger"></i>Academic Calendar</h5>
        <small class="text-muted">School-wide holidays, events, exams, and notices</small>
    </div>
    <button class="btn btn-danger" data-toggle="modal" data-target="#addEventModal" style="border-radius:10px;">
        <i class="icon-plus3 mr-1"></i> Add Entry
    </button>
</div>

@if(session('flash_success'))
    <div class="alert alert-success alert-dismissible border-0"><button type="button" class="close" data-dismiss="alert">&times;</button>{{ session('flash_success') }}</div>
@endif

{{-- Month Navigation --}}
<div class="card border-0 shadow-sm mb-3" style="border-radius:12px;">
    <div class="card-body py-2">
        <div class="d-flex align-items-center justify-content-between">
            @php
                $prevMonth = date('Y-m', strtotime($month.'-01 -1 month'));
                $nextMonth = date('Y-m', strtotime($month.'-01 +1 month'));
            @endphp
            <a href="?month={{ $prevMonth }}" class="btn btn-sm btn-light" style="border-radius:8px;"><i class="icon-arrow-left8"></i></a>
            <h6 class="font-weight-bold mb-0">{{ $month_name }}</h6>
            <a href="?month={{ $nextMonth }}" class="btn btn-sm btn-light" style="border-radius:8px;"><i class="icon-arrow-right8"></i></a>
        </div>
    </div>
</div>

{{-- Legend --}}
<div class="mb-3 d-flex flex-wrap gap-2">
    <span class="badge type-badge-holiday px-3 py-1" style="border-radius:8px;font-size:12px;">Holiday</span>
    <span class="badge type-badge-event px-3 py-1 ml-1" style="border-radius:8px;font-size:12px;">Event</span>
    <span class="badge type-badge-exam px-3 py-1 ml-1" style="border-radius:8px;font-size:12px;">Exam</span>
    <span class="badge type-badge-notice px-3 py-1 ml-1" style="border-radius:8px;font-size:12px;">Notice</span>
</div>

{{-- Calendar Grid --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:16px;overflow:hidden;">
    <div class="card-body p-3">
        {{-- Day headers --}}
        <div class="cal-grid mb-1">
            @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $d)
            <div class="day-hdr">{{ $d }}</div>
            @endforeach
        </div>

        {{-- Calendar days --}}
        @php
            $firstDay  = $calendar_grid['first_day']; // 1=Mon
            $daysTotal = $calendar_grid['days'];
            $todayDate = date('Y-m-d');
        @endphp
        <div class="cal-grid">
            {{-- Empty cells before first day --}}
            @for($i = 1; $i < $firstDay; $i++)
            <div class="cal-cell other-month"></div>
            @endfor

            {{-- Actual days --}}
            @for($day = 1; $day <= $daysTotal; $day++)
            @php
                $dateStr = $month.'-'.str_pad($day, 2, '0', STR_PAD_LEFT);
                $isToday = ($dateStr === $todayDate);
                $dayEvents = $month_events->filter(function($e) use ($dateStr) {
                    return $e->start_date->format('Y-m-d') == $dateStr
                        || ($e->end_date && $e->start_date->format('Y-m-d') <= $dateStr && $e->end_date->format('Y-m-d') >= $dateStr);
                });
            @endphp
            <div class="cal-cell {{ $isToday ? 'today' : '' }}">
                <div class="cal-day-num {{ $isToday ? 'text-primary' : '' }}">{{ $day }}</div>
                @foreach($dayEvents->take(3) as $ev)
                <span class="cal-event-dot type-badge-{{ $ev->type }}" title="{{ $ev->title }}">{{ $ev->title }}</span>
                @endforeach
            </div>
            @endfor
        </div>
    </div>
</div>

{{-- Event List --}}
<div class="card border-0 shadow-sm" style="border-radius:16px;">
    <div class="card-header bg-white border-0">
        <h6 class="font-weight-bold mb-0">Events in {{ $month_name }}</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light"><tr><th>Title</th><th>Type</th><th>Start</th><th>End</th><th>Description</th><th class="text-center">Action</th></tr></thead>
                <tbody>
                @forelse($month_events as $ev)
                <tr>
                    <td class="font-weight-semibold">{{ $ev->title }}</td>
                    <td><span class="badge type-badge-{{ $ev->type }} px-2 text-capitalize">{{ $ev->type }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($ev->start_date)->format('d M Y') }}</td>
                    <td>{{ $ev->end_date ? \Carbon\Carbon::parse($ev->end_date)->format('d M Y') : '—' }}</td>
                    <td><small class="text-muted">{{ $ev->description ? mb_strimwidth($ev->description,0,50,'...') : '—' }}</small></td>
                    <td class="text-center">
                        <form action="{{ route('admin.calendar.destroy', $ev->id) }}" method="POST" onsubmit="return confirm('Delete this entry?')" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;"><i class="icon-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No events this month.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Event Modal --}}
<div class="modal fade" id="addEventModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden;">
            <div class="modal-header border-0" style="background:linear-gradient(135deg,#e74c3c,#c0392b);color:#fff;">
                <h5 class="modal-title font-weight-bold"><i class="icon-calendar3 mr-2"></i>Add Calendar Entry</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('admin.calendar.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="font-weight-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" required style="border-radius:8px;" placeholder="e.g. Diwali Holiday">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-semibold">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-control" required style="border-radius:8px;">
                                @foreach($types as $t)
                                <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-semibold">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" required style="border-radius:8px;" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-semibold">End Date</label>
                            <input type="date" name="end_date" class="form-control" style="border-radius:8px;">
                        </div>
                        <div class="col-12 mb-2">
                            <label class="font-weight-semibold">Description</label>
                            <textarea name="description" rows="2" class="form-control" style="border-radius:8px;" placeholder="Optional details..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius:8px;">Cancel</button>
                    <button type="submit" class="btn btn-danger px-4" style="border-radius:8px;"><i class="icon-plus3 mr-1"></i>Add Entry</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
