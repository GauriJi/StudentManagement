@extends('layouts.master')
@section('page_title', 'Student Attendance Report')

@section('content')
<style>
    .role-tag { display:inline-block; padding: 3px 10px; border-radius:999px; font-size:11px; font-weight:700; text-transform:capitalize; }
    .status-badge { padding: 4px 12px; border-radius: 999px; font-weight: 600; font-size: 12px; }
    .status-present { background-color: #d4edda; color: #155724; }
    .status-absent { background-color: #f8d7da; color: #721c24; }
    .status-late { background-color: #fff3cd; color: #856404; }
</style>

<div class="card shadow-sm border-0 mb-3" style="border-radius: 12px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="card-body text-center py-4">
        <h6 class="font-weight-bold text-uppercase text-muted mb-1" style="letter-spacing: 1px;"><i class="icon-calendar2 mr-2"></i> Showing Attendance For</h6>
        <h3 class="text-primary font-weight-bold mb-0">{{ $period }}</h3>
        <p class="text-muted mt-2 mb-0">Class: <strong>{{ $my_class->name }}</strong></p>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
    <div class="card-body p-3">
        <form method="get" action="{{ route('attendance.report') }}">
            <input type="hidden" name="my_class_id" value="{{ $my_class->id }}">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label class="font-weight-semibold text-muted text-uppercase" style="font-size: 11px;">Filter by Specific Date</label>
                    <input type="date" name="date" value="{{ $filter_date }}" class="form-control form-control-lg bg-light" style="border-radius: 10px; border: 1px solid #dee2e6;">
                </div>
                <div class="col-md-1 text-center font-weight-bold text-muted py-2">
                    OR
                </div>
                <div class="col-md-4">
                    <label class="font-weight-semibold text-muted text-uppercase" style="font-size: 11px;">Filter by Entire Month</label>
                    <input type="month" name="month" value="{{ $filter_month }}" class="form-control form-control-lg bg-light" style="border-radius: 10px; border: 1px solid #dee2e6;">
                </div>
                <div class="col-md-3 mt-3 mt-md-0 d-flex">
                    <button type="submit" class="btn btn-primary btn-lg flex-grow-1 mr-2" style="border-radius: 10px;"><i class="icon-filter4 mr-2"></i> Apply Filter</button>
                    @if($filter_date || $filter_month)
                        <a href="{{ route('attendance.report', ['my_class_id' => $my_class->id]) }}" class="btn btn-light btn-lg" style="border-radius: 10px;" data-popup="tooltip" title="Clear Filters"><i class="icon-reset"></i></a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center pt-4 pb-2">
        <div>
            <h5 class="card-title font-weight-bold mb-0">Records Data</h5>
            <p class="text-muted mb-0">Historical attendance details</p>
        </div>
        <div>
            <a href="{{ route('attendance.index') }}" class="btn btn-light shadow-sm ml-2" style="border-radius:10px;">
                <i class="icon-arrow-left5 mr-1"></i> Back to Classes
            </a>
        </div>
    </div>

    <div class="card-body">
        <table class="table datatable-button-html5-columns table-hover border">
            <thead class="bg-light">
                <tr>
                    <th>S/N</th>
                    <th>Date</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $att)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="font-weight-semibold">{{ date('d M, Y', strtotime($att->date)) }}</td>
                        <td>
                            @if($att->student && $att->student->user)
                                <div class="d-flex align-items-center">
                                    <img src="{{ $att->student->user->photo }}" class="rounded-circle mr-2 border" width="30" height="30" alt="">
                                    <span>{{ $att->student->user->name }}</span>
                                </div>
                            @else
                                <span class="text-muted">Unknown Student</span>
                            @endif
                        </td>
                        <td>
                            <span class="role-tag bg-light border text-muted">{{ $my_class->name }}</span>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $att->status }}">{{ ucfirst(str_replace('_', ' ', $att->status)) }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
