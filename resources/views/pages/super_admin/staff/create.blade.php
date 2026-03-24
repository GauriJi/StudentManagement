@extends('layouts.master')
@section('page_title', 'Add Staff Member')

@section('content')
<div class="d-flex align-items-center mb-3">
    <a href="{{ route('sa.staff.index') }}" class="btn btn-light btn-sm mr-2" style="border-radius:8px;"><i class="icon-arrow-left8"></i></a>
    <div>
        <h5 class="font-weight-bold mb-0">Add New Staff Member</h5>
        <small class="text-muted">Create a user account and assign a staff role</small>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius:14px;max-width:700px;">
    <div class="card-body">
        <form action="{{ route('sa.staff.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="font-weight-semibold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="Staff full name" required style="border-radius:8px;">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="staff@school.com" required style="border-radius:8px;">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-semibold">Role <span class="text-danger">*</span></label>
                    <select name="user_type" class="form-control @error('user_type') is-invalid @enderror" required style="border-radius:8px;">
                        <option value="">— Select Role —</option>
                        @foreach($staff_types as $type)
                        <option value="{{ $type }}" {{ old('user_type') == $type ? 'selected' : '' }}>
                            {{ ucwords($type) }}
                        </option>
                        @endforeach
                    </select>
                    @error('user_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-semibold">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="Phone number" style="border-radius:8px;">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-semibold">Date of Employment <span class="text-danger">*</span></label>
                    <input type="date" name="emp_date" class="form-control @error('emp_date') is-invalid @enderror"
                           value="{{ old('emp_date', date('Y-m-d')) }}" required style="border-radius:8px;">
                    @error('emp_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-semibold">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="Min 6 characters" required style="border-radius:8px;">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-2">
                <button type="submit" class="btn btn-primary px-4" style="border-radius:8px;"><i class="icon-user-tie mr-1"></i> Add Staff</button>
                <a href="{{ route('sa.staff.index') }}" class="btn btn-light px-3 ml-2" style="border-radius:8px;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
