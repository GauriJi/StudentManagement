@extends('layouts.master')
@section('page_title', 'Staff Attendance')

@section('content')
<div class="d-flex align-items-center mb-3">
    <a href="{{ route('sa.staff.index') }}" class="btn btn-light btn-sm mr-2" style="border-radius:8px;"><i class="icon-arrow-left8"></i></a>
    <div>
        <h5 class="font-weight-bold mb-0"><i class="icon-calendar mr-2 text-success"></i>Staff Attendance</h5>
        <small class="text-muted">Mark or view daily attendance for all staff members</small>
    </div>
</div>

@if(session('flash_success'))
    <div class="alert alert-success alert-dismissible border-0"><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>{{ session('flash_success') }}</div>
@endif

<div class="card border-0 shadow-sm" style="border-radius:14px;max-width:700px;">
    <div class="card-header bg-white border-0">
        <h6 class="font-weight-bold mb-0">Record Attendance</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('sa.staff.mark_attendance') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="font-weight-semibold">Date</label>
                <input type="date" name="date" class="form-control" value="{{ $today }}" max="{{ $today }}" style="border-radius:8px;max-width:200px;">
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-3">
                    <thead class="thead-light">
                        <tr>
                            <th>Staff Member</th>
                            <th>Role</th>
                            <th class="text-center">Present</th>
                            <th class="text-center">Absent</th>
                            <th class="text-center">Leave</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($staff as $s)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $s->photo }}" width="30" height="30" class="rounded-circle mr-2">
                                <span class="font-weight-semibold">{{ $s->name }}</span>
                            </div>
                        </td>
                        <td><small class="text-capitalize text-muted">{{ str_replace('_',' ',$s->user_type) }}</small></td>
                        @php $currentStatus = $attendance[$s->id] ?? 'present'; @endphp
                        <td class="text-center">
                            <input type="radio" name="status[{{ $s->id }}]" value="present" {{ $currentStatus == 'present' ? 'checked' : '' }}>
                        </td>
                        <td class="text-center">
                            <input type="radio" name="status[{{ $s->id }}]" value="absent" {{ $currentStatus == 'absent' ? 'checked' : '' }}>
                        </td>
                        <td class="text-center">
                            <input type="radio" name="status[{{ $s->id }}]" value="leave" {{ $currentStatus == 'leave' ? 'checked' : '' }}>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No staff members found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($staff->count())
            <button type="submit" class="btn btn-success px-4" style="border-radius:8px;"><i class="icon-checkmark3 mr-1"></i> Save Attendance</button>
            @endif
        </form>
    </div>
</div>
@endsection
