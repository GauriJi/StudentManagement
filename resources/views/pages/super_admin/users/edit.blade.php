@extends('layouts.master')
@section('page_title', 'Edit User')

@section('content')
<div class="d-flex align-items-center mb-3">
    <a href="{{ route('sa.users.index') }}" class="btn btn-light btn-sm mr-2" style="border-radius:8px;"><i class="icon-arrow-left8"></i></a>
    <div>
        <h5 class="font-weight-bold mb-0">Edit User: {{ $user->name }}</h5>
        <small class="text-muted">Update user details and role assignment</small>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius:14px;max-width:700px;">
    <div class="card-body">
        <form action="{{ route('sa.users.update', Qs::hash($user->id)) }}" method="POST">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="font-weight-semibold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}" required style="border-radius:8px;">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-semibold">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}" required style="border-radius:8px;">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-semibold">Role <span class="text-danger">*</span></label>
                    <select name="user_type" class="form-control" required style="border-radius:8px;">
                        @foreach($user_types as $type)
                        <option value="{{ $type }}" {{ $user->user_type == $type ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $type)) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-semibold">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" style="border-radius:8px;">
                </div>
                <div class="col-12 mb-1">
                    <hr class="my-2">
                    <small class="text-muted">Leave password fields blank to keep the current password.</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-semibold">New Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="Min 6 characters" style="border-radius:8px;">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-semibold">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password" style="border-radius:8px;">
                </div>
            </div>
            <div class="mt-2">
                <button type="submit" class="btn btn-primary px-4" style="border-radius:8px;"><i class="icon-checkmark3 mr-1"></i> Update User</button>
                <a href="{{ route('sa.users.index') }}" class="btn btn-light px-3 ml-2" style="border-radius:8px;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
