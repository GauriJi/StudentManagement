@extends('layouts.master')
@section('page_title', 'Staff Attendance')
@section('content')

<style>
.attendance-card { border-radius: 12px; transition: all 0.2s; border-left: 5px solid #ccc; background: #fff;}
.attendance-card.border-success { border-left-color: #28a745; }
.attendance-card.border-danger { border-left-color: #dc3545; }
.attendance-card.border-secondary { border-left-color: #6c757d; }

.status-radio { display: none; }
.status-btn { 
    border-radius: 20px; 
    padding: 6px 14px; 
    font-weight: 600; 
    cursor: pointer; 
    border: 2px solid transparent; 
    opacity: 0.5; 
    transition: all 0.3s; 
    margin: 0 2px;
}
.status-radio:checked + .status-present { background-color: #28a745; color: white; opacity: 1; border-color: #28a745; box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3); }
.status-radio:checked + .status-absent { background-color: #dc3545; color: white; opacity: 1; border-color: #dc3545; box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3); }
.status-radio:checked + .status-half_day { background-color: #6c757d; color: white; opacity: 1; border-color: #6c757d; box-shadow: 0 4px 10px rgba(108, 117, 125, 0.3); }

/* Default wireframe for labels */
.status-present { border-color: #28a745; color: #28a745; }
.status-absent { border-color: #dc3545; color: #dc3545; }
.status-half_day { border-color: #6c757d; color: #6c757d; }

.attendance-card:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(0,0,0,0.05); }
.role-tag { display:inline-block; padding: 2px 8px; border-radius:999px; font-size:10px; font-weight:700; text-transform:capitalize; }
</style>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white pb-0 border-0 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title font-weight-bold mb-0">Staff Attendance</h5>
                <p class="text-muted">Marking attendance for <strong>{{ date('d M Y', strtotime($date)) }}</strong></p>
            </div>
            <div>
                <a href="{{ route('admin.staff.attendance_report') }}" class="btn btn-outline-info mr-2" style="border-radius:10px;"><i class="icon-list mr-1"></i> View Report</a>
                <a href="{{ route('admin.staff.index') }}" class="btn btn-light" style="border-radius:10px;"><i class="icon-arrow-left5 mr-1"></i> Back to Staff</a>
            </div>
        </div>

        <div class="card-body bg-light pt-4">
            <form method="post" action="{{ route('admin.staff.mark_attendance') }}">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">
                
                <div class="row">
                    @foreach($staff as $s)
                        @php
                            $st = isset($attendances[$s->id]) ? $attendances[$s->id]->status : 'present';
                            $borderClass = $st == 'present' ? 'border-success' : ($st == 'absent' ? 'border-danger' : 'border-secondary');

                            $student_attendance = isset($all_attendances[$s->id]) ? $all_attendances[$s->id] : collect();
                            $total_days = $student_attendance->count();
                            $present_days = $student_attendance->where('status', 'present')->count();
                            $attendance_perc = $total_days > 0 ? round(($present_days / $total_days) * 100) : 0;
                        @endphp
                        
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card attendance-card h-100 shadow-sm {{ $borderClass }}" id="card_{{ $s->id }}">
                                <div class="card-body p-3 d-flex flex-column justify-content-between">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="{{ $s->photo }}" class="rounded-circle mr-3 border" width="45" height="45" alt="photo">
                                        <div>
                                            <h6 class="mb-0 font-weight-bold text-dark">{{ $s->name }}</h6>
                                            <span class="role-tag bg-light text-muted border">{{ str_replace('_',' ',$s->user_type) }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mt-auto bg-white p-2 border rounded" style="background:#fcfcfc;">
                                        <label class="mb-0">
                                            <input type="radio" name="status[{{ $s->id }}]" value="present" class="status-radio" {{ $st == 'present' ? 'checked' : '' }} onclick="updateCard('{{$s->id}}', 'success')">
                                            <span class="status-btn status-present">Present</span>
                                        </label>
                                        <label class="mb-0">
                                            <input type="radio" name="status[{{ $s->id }}]" value="half_day" class="status-radio" {{ $st == 'half_day' ? 'checked' : '' }} onclick="updateCard('{{$s->id}}', 'secondary')">
                                            <span class="status-btn status-half_day">Half Day</span>
                                        </label>
                                        <label class="mb-0">
                                            <input type="radio" name="status[{{ $s->id }}]" value="absent" class="status-radio" {{ $st == 'absent' ? 'checked' : '' }} onclick="updateCard('{{$s->id}}', 'danger')">
                                            <span class="status-btn status-absent">Absent</span>
                                        </label>
                                    </div>

                                    <div class="text-center mt-2">
                                        <small class="text-muted"><i class="icon-stats-growth"></i> Overall Attendance: <strong>{{ $attendance_perc }}%</strong></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <hr>
                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-success btn-lg shadow-sm px-4 rounded-pill"><b><i class="icon-checkmark4 mr-2"></i></b> Save Staff Attendance</button>
                </div>
            </form>
        </div>
    </div>

<script>
function updateCard(userId, type) {
    let card = document.getElementById('card_' + userId);
    card.classList.remove('border-success', 'border-danger', 'border-secondary');
    card.classList.add('border-' + type);
}
</script>
@endsection
