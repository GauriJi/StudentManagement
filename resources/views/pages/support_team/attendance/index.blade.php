@extends('layouts.master')
@section('page_title', 'Manage Attendance')
@section('content')

<style>
    .glass-card { background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(0,0,0,0.05); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .btn-submit { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; border-radius: 30px; padding: 10px 30px; border: none; font-weight: bold; width: 100%; transition: all 0.3s;}
    .btn-submit:hover { color: white; box-shadow: 0 5px 15px rgba(30, 60, 114, 0.4); transform: translateY(-2px); }
    .form-group label { font-weight: bold; color: #475569; }
</style>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card glass-card mt-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 text-center">
                <i class="icon-calendar3 text-primary mb-2" style="font-size: 3rem;"></i>
                <h4 class="mb-0 font-weight-bold text-dark">Manage Class Attendance</h4>
                <p class="text-muted">Select a class and date to take or update attendance.</p>
            </div>

            <div class="card-body px-5 pb-5 pt-3">
                <form method="post" action="{{ route('attendance.manage') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="my_class_id">Select Class <span class="text-danger">*</span></label>
                                <select required data-placeholder="Choose..." class="form-control select" name="my_class_id" id="my_class_id">
                                    <option value=""></option>
                                    @foreach($my_classes as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select Date <span class="text-danger">*</span></label>
                                <input name="date" required type="date" value="{{ date('Y-m-d') }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-submit btn-lg"><i class="icon-paperplane mr-2"></i> Fetch Attendance Sheet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
