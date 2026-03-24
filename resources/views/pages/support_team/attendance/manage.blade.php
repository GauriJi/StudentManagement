@extends('layouts.master')
@section('page_title', 'Mark Attendance')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Mark Attendance for {{ $my_class->name }} on {{ date('d M Y', strtotime($date)) }}</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('attendance.store') }}">
                @csrf
                <input type="hidden" name="my_class_id" value="{{ $my_class->id }}">
                <input type="hidden" name="date" value="{{ $date }}">
                
                <table class="table datatable-button-html5-columns">
                    <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Student Name</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($students as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $s->user->name }}</td>
                            <td>
                                @php
                                    $st = isset($attendances[$s->user_id]) ? $attendances[$s->user_id]->status : 'present';
                                @endphp
                                <select name="status[{{ $s->user_id }}]" class="form-control select">
                                    <option value="present" {{ $st == 'present' ? 'selected' : '' }}>Present</option>
                                    <option value="absent" {{ $st == 'absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="late" {{ $st == 'late' ? 'selected' : '' }}>Late</option>
                                </select>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <br>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Save Attendance <i class="icon-paperplane ml-2"></i></button>
                </div>
            </form>
        </div>
    </div>
@endsection
