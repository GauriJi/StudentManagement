@extends('layouts.master')
@section('page_title', 'Create User')

@section('content')
<div class="d-flex align-items-center mb-3">
    <a href="{{ route('sa.users.index') }}" class="btn btn-light btn-sm mr-2" style="border-radius:8px;"><i class="icon-arrow-left8"></i></a>
    <div>
        <h5 class="font-weight-bold mb-0">Create New User</h5>
        <small class="text-muted">Add a new user and assign their role</small>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius:14px;max-width:700px;">
    <div class="card-body">
        <form action="{{ route('sa.users.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="font-weight-semibold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="Enter full name" required style="border-radius:8px;">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-semibold">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="user@example.com" required style="border-radius:8px;">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-semibold">Role <span class="text-danger">*</span></label>
                    <select name="user_type" class="form-control @error('user_type') is-invalid @enderror" required style="border-radius:8px;">
                        <option value="">— Select Role —</option>
                        @foreach($user_types as $type)
                        <option value="{{ $type }}" {{ old('user_type') == $type ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $type)) }}
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
                    <label class="font-weight-semibold">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="Min 6 characters" required style="border-radius:8px;">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-semibold">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required style="border-radius:8px;">
                </div>
            </div>
            <div class="mt-2">
                <button type="submit" class="btn btn-primary px-4" style="border-radius:8px;"><i class="icon-user-plus mr-1"></i> Create User</button>
                <a href="{{ route('sa.users.index') }}" class="btn btn-light px-3 ml-2" style="border-radius:8px;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
