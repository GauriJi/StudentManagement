@extends('layouts.master')
@section('page_title', 'Mark Attendance')
@section('content')

<style>
.attendance-card { border-radius: 12px; transition: all 0.2s; border-left: 5px solid #ccc; background: #fff;}
.attendance-card.border-success { border-left-color: #28a745; }
.attendance-card.border-danger { border-left-color: #dc3545; }
.attendance-card.border-warning { border-left-color: #ffc107; }

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
.status-radio:checked + .status-late { background-color: #ffc107; color: white; opacity: 1; border-color: #ffc107; box-shadow: 0 4px 10px rgba(255, 193, 7, 0.3); }

/* Default wireframe for labels */
.status-present { border-color: #28a745; color: #28a745; }
.status-absent { border-color: #dc3545; color: #dc3545; }
.status-late { border-color: #ffc107; color: #ffc107; text-shadow: none; }

.attendance-card:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(0,0,0,0.05); }
</style>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white pb-0 border-0">
            <h5 class="card-title font-weight-bold mb-0">Modern Attendance System</h5>
            <p class="text-muted">Currently marking for <strong>{{ $my_class->name }}</strong> on <strong>{{ date('d M Y', strtotime($date)) }}</strong></p>
        </div>

        <div class="card-body bg-light pt-4">
            <form method="post" action="{{ route('attendance.store') }}">
                @csrf
                <input type="hidden" name="my_class_id" value="{{ $my_class->id }}">
                <input type="hidden" name="date" value="{{ $date }}">
                
                <div class="row">
                    @foreach($students as $s)
                        @php
                            $st = isset($attendances[$s->user_id]) ? $attendances[$s->user_id]->status : 'present';
                            $borderClass = $st == 'present' ? 'border-success' : ($st == 'absent' ? 'border-danger' : 'border-warning');
                        @endphp
                        
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card attendance-card h-100 shadow-sm {{ $borderClass }}" id="card_{{ $s->user_id }}">
                                <div class="card-body p-3 d-flex flex-column justify-content-between">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="{{ $s->user->photo }}" class="rounded-circle mr-3 border" width="45" height="45" alt="photo">
                                        <div>
                                            <h6 class="mb-0 font-weight-bold text-dark">{{ $s->user->name }}</h6>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mt-auto bg-white p-2 border rounded" style="background:#fcfcfc;">
                                        <label class="mb-0">
                                            <input type="radio" name="status[{{ $s->user_id }}]" value="present" class="status-radio" {{ $st == 'present' ? 'checked' : '' }} onclick="updateCard('{{$s->user_id}}', 'success')">
                                            <span class="status-btn status-present">Present</span>
                                        </label>
                                        <label class="mb-0">
                                            <input type="radio" name="status[{{ $s->user_id }}]" value="late" class="status-radio" {{ $st == 'late' ? 'checked' : '' }} onclick="updateCard('{{$s->user_id}}', 'warning')">
                                            <span class="status-btn status-late">Late</span>
                                        </label>
                                        <label class="mb-0">
                                            <input type="radio" name="status[{{ $s->user_id }}]" value="absent" class="status-radio" {{ $st == 'absent' ? 'checked' : '' }} onclick="updateCard('{{$s->user_id}}', 'danger')">
                                            <span class="status-btn status-absent">Absent</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <hr>
                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-success btn-lg shadow-sm px-4 rounded-pill"><b><i class="icon-checkmark4 mr-2"></i></b> Save Attendance</button>
                </div>
            </form>
        </div>
    </div>

<script>
function updateCard(userId, type) {
    let card = document.getElementById('card_' + userId);
    card.classList.remove('border-success', 'border-danger', 'border-warning');
    card.classList.add('border-' + type);
}
</script>
@endsection
