@extends('layouts.master')
@section('page_title', 'Manage Attendance')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Attendance</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('attendance.manage') }}">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="my_class_id" class="font-weight-semibold">Select Class <span class="text-danger">*</span></label>
                            <select required data-placeholder="Select Class" class="form-control select" name="my_class_id" id="my_class_id">
                                <option value=""></option>
                                @foreach($my_classes as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-semibold">Select Date <span class="text-danger">*</span></label>
                            <input name="date" required type="date" value="{{ date('Y-m-d') }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-4 mt-4">
                        <button type="submit" class="btn btn-primary">Manage Attendance <i class="icon-paperplane ml-2"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
