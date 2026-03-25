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

<div class="card border-0 shadow-sm" style="border-radius:14px;">
    <div class="card-body">
        <form action="{{ route('sa.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                {{-- Base common fields --}}
                <div class="col-md-6 mb-3">
                    <label class="font-weight-semibold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter full name" required style="border-radius:8px;">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-semibold">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="user@example.com" required style="border-radius:8px;">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-semibold">Role <span class="text-danger">*</span></label>
                    <select id="user_type_select" name="user_type" class="form-control" required style="border-radius:8px;">
                        <option value="">— Select Role —</option>
                        @foreach($user_types as $type)
                        <option value="{{ $type }}" {{ old('user_type') == $type ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $type)) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-semibold">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Min 6 characters" required style="border-radius:8px;">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="font-weight-semibold">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required style="border-radius:8px;">
                </div>

                {{-- Dynamic Fields --}}
                
                {{-- Phone --}}
                <div class="col-md-6 mb-3 role-field" data-roles="student,teacher,parent,admin,super_admin,accountant" style="display:none;">
                    <label class="font-weight-semibold">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="Phone number" style="border-radius:8px;">
                </div>
                <div class="col-md-6 mb-3 role-field" data-roles="student" style="display:none;">
                    <label class="font-weight-semibold">Telephone</label>
                    <input type="text" name="phone2" class="form-control" value="{{ old('phone2') }}" placeholder="Telephone" style="border-radius:8px;">
                </div>
                
                {{-- Address --}}
                <div class="col-md-6 mb-3 role-field" data-roles="student,teacher,parent" style="display:none;">
                    <label class="font-weight-semibold">Address / Residential Address <span class="text-danger" data-roles="student">*</span></label>
                    <input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="Address" style="border-radius:8px;">
                </div>

                {{-- Username --}}
                <div class="col-md-6 mb-3 role-field" data-roles="student,parent" style="display:none;">
                    <label class="font-weight-semibold">Username</label>
                    <input type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="Username" style="border-radius:8px;">
                </div>
                
                {{-- DOB --}}
                <div class="col-md-6 mb-3 role-field" data-roles="teacher" style="display:none;">
                    <label class="font-weight-semibold">Date of Birth</label>
                    <input type="date" name="dob" class="form-control" value="{{ old('dob') }}" style="border-radius:8px;">
                </div>
                
                {{-- Emp Date --}}
                <div class="col-md-6 mb-3 role-field" data-roles="teacher" style="display:none;">
                    <label class="font-weight-semibold">Date of Joining</label>
                    <input type="date" name="emp_date" class="form-control" value="{{ old('emp_date') }}" style="border-radius:8px;">
                </div>
                
                {{-- Gender --}}
                <div class="col-md-6 mb-3 role-field" data-roles="student,teacher" style="display:none;">
                    <label class="font-weight-semibold">Gender <span class="text-danger" data-roles="student">*</span></label>
                    <select class="select form-control" name="gender" data-fouc data-placeholder="Choose..">
                        <option value=""></option>
                        <option value="Male" {{ (old('gender') == 'Male' ? 'selected' : '') }}>Male</option>
                        <option value="Female" {{ (old('gender') == 'Female' ? 'selected' : '') }}>Female</option>
                    </select>
                </div>
                
                {{-- Nationality --}}
                <div class="col-md-6 mb-3 role-field" data-roles="student" style="display:none;">
                    <label class="font-weight-semibold">Nationality <span class="text-danger">*</span></label>
                    <select name="nal_id" id="nal_id" class="select-search form-control">
                        <option value=""></option>
                        @foreach($nationals as $nal)
                            <option {{ (old('nal_id') == $nal->id ? 'selected' : '') }} value="{{ $nal->id }}">{{ $nal->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                {{-- State & District --}}
                <div class="col-md-6 mb-3 role-field" data-roles="student" style="display:none;">
                    <label class="font-weight-semibold">State <span class="text-danger">*</span></label>
                    <select name="state_id" id="state_id" class="select-search form-control">
                        <option value=""></option>
                        @foreach($states as $st)
                            <option {{ (old('state_id') == $st->id ? 'selected' : '') }} value="{{ $st->id }}">{{ $st->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3 role-field" data-roles="student" style="display:none;">
                    <label class="font-weight-semibold">District (LGA) <span class="text-danger">*</span></label>
                    <select name="lga_id" id="lga_id" class="select-search form-control">
                        <option value="">Select State First</option>
                    </select>
                </div>

                {{-- Blood Group --}}
                <div class="col-md-6 mb-3 role-field" data-roles="student" style="display:none;">
                    <label class="font-weight-semibold">Blood Group</label>
                    <select name="bg_id" id="bg_id" class="select form-control">
                        <option value=""></option>
                        @foreach($blood_groups as $bg)
                            <option {{ (old('bg_id') == $bg->id ? 'selected' : '') }} value="{{ $bg->id }}">{{ $bg->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Documents (Student) --}}
                <div class="col-md-6 mb-3 role-field" data-roles="student" style="display:none;">
                    <label class="font-weight-semibold">Upload Passport Photo</label>
                    <input type="file" name="photo" class="form-control" accept="image/*" style="border-radius:8px;">
                </div>
                <div class="col-md-6 mb-3 role-field" data-roles="student" style="display:none;">
                    <label class="font-weight-semibold">Birth Certificate</label>
                    <input type="file" name="birth_certificate" class="form-control" style="border-radius:8px;">
                </div>
                <div class="col-md-6 mb-3 role-field" data-roles="student" style="display:none;">
                    <label class="font-weight-semibold">Previous Marksheet</label>
                    <input type="file" name="prev_marksheet" class="form-control" style="border-radius:8px;">
                </div>
                <div class="col-md-6 mb-3 role-field" data-roles="student" style="display:none;">
                    <label class="font-weight-semibold">Aadhar Card</label>
                    <input type="file" name="aadhar_card" class="form-control" style="border-radius:8px;">
                </div>
                
                <div class="col-12 role-field" data-roles="student,parent" style="display:none;">
                    <hr>
                    <h6 class="font-weight-bold">Family / Parent Details</h6>
                </div>
                
                {{-- Family Details --}}
                <div class="col-md-6 mb-3 role-field" data-roles="student,parent" style="display:none;">
                    <label class="font-weight-semibold">Fathers Name</label>
                    <input type="text" name="father_name" class="form-control" value="{{ old('father_name') }}" style="border-radius:8px;">
                </div>
                <div class="col-md-6 mb-3 role-field" data-roles="student,parent" style="display:none;">
                    <label class="font-weight-semibold">Mother Name</label>
                    <input type="text" name="mother_name" class="form-control" value="{{ old('mother_name') }}" style="border-radius:8px;">
                </div>
                <div class="col-md-6 mb-3 role-field" data-roles="student,parent" style="display:none;">
                    <label class="font-weight-semibold">Father Occupation</label>
                    <input type="text" name="father_occupation" class="form-control" value="{{ old('father_occupation') }}" style="border-radius:8px;">
                </div>
                <div class="col-md-6 mb-3 role-field" data-roles="student,parent" style="display:none;">
                    <label class="font-weight-semibold">Yearly Income</label>
                    <input type="text" name="yearly_income" class="form-control" value="{{ old('yearly_income') }}" style="border-radius:8px;">
                </div>
                <div class="col-md-6 mb-3 role-field" data-roles="parent" style="display:none;">
                    <label class="font-weight-semibold">Relationship to Student</label>
                    <input type="text" name="relationship_to_student" class="form-control" value="{{ old('relationship_to_student') }}" style="border-radius:8px;">
                </div>
                
                <div class="col-12 role-field" data-roles="teacher" style="display:none;">
                    <hr>
                    <h6 class="font-weight-bold">Academic Details</h6>
                </div>
                
                {{-- Academic Details for Teacher --}}
                <div class="col-md-6 mb-3 role-field" data-roles="teacher" style="display:none;">
                    <label class="font-weight-semibold">Highest Qualification</label>
                    <input type="text" name="qualification" class="form-control" value="{{ old('qualification') }}" style="border-radius:8px;">
                </div>
                <div class="col-md-6 mb-3 role-field" data-roles="teacher" style="display:none;">
                    <label class="font-weight-semibold">Specialization/Subject Expertise</label>
                    <input type="text" name="specialization" class="form-control" value="{{ old('specialization') }}" style="border-radius:8px;">
                </div>
                
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-4" style="border-radius:8px;"><i class="icon-user-plus mr-1"></i> Create User</button>
                <a href="{{ route('sa.users.index') }}" class="btn btn-light px-3 ml-2" style="border-radius:8px;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        function toggleFields() {
            var selectedRole = $('#user_type_select').val();
            $('.role-field').each(function() {
                var roles = $(this).data('roles').split(',');
                if(roles.includes(selectedRole)) {
                    $(this).slideDown();
                } else {
                    $(this).slideUp();
                }
            });
            
            // Highlight required fields based on role
            $('span.text-danger[data-roles]').each(function() {
                var roles = $(this).data('roles').split(',');
                if(roles.includes(selectedRole)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        $('#user_type_select').on('change', toggleFields);
        toggleFields(); // Initial run

        // Fetch LGAs based on selected state
        $('#state_id').on('change', function() {
            var state_id = $(this).val();
            if(state_id) {
                $.ajax({
                    url: '/ajax/get_lga/' + state_id,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        $('#lga_id').empty();
                        $('#lga_id').append('<option value="">Select LGA</option>');
                        $.each(data, function(key, value) {
                            $('#lga_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                        });
                    }
                });
            } else {
                $('#lga_id').empty();
                $('#lga_id').append('<option value="">Select State First</option>');
            }
        });
    });
</script>
@endsection
