@extends('layouts.master')
@section('page_title', 'Edit Student')
@section('content')

        <div class="card">
            <div class="card-header bg-white header-elements-inline">
                <h6 id="ajax-title" class="card-title">Please fill The form Below To Edit record of {{ $sr->user->name }}</h6>

                {!! Qs::getPanelOptions() !!}
            </div>

            <form method="post" enctype="multipart/form-data" class="wizard-form steps-validation ajax-update" data-reload="#ajax-title" action="{{ route('students.update', Qs::hash($sr->id)) }}" data-fouc>
                @csrf @method('PUT')
                <h6>Personal data</h6>
                <fieldset>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Full Name: <span class="text-danger">*</span></label>
                                <input value="{{ $sr->user->name }}" required type="text" name="name" placeholder="Full Name" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address: <span class="text-danger">*</span></label>
                                <input value="{{ $sr->user->address }}" class="form-control" placeholder="Address" name="address" type="text" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Email address: <span class="text-danger">*</span></label>
                                <input required value="{{ $sr->user->email  }}" type="email" name="email" class="form-control" placeholder="your@email.com">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="gender">Gender: <span class="text-danger">*</span></label>
                                <select class="select form-control" id="gender" name="gender" required data-fouc data-placeholder="Choose..">
                                    <option value=""></option>
                                    <option {{ ($sr->user->gender  == 'Male' ? 'selected' : '') }} value="Male">Male</option>
                                    <option {{ ($sr->user->gender  == 'Female' ? 'selected' : '') }} value="Female">Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Phone:</label>
                                <input value="{{ $sr->user->phone  }}" type="text" name="phone" class="form-control" placeholder="" >
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Telephone:</label>
                                <input value="{{ $sr->user->phone2  }}" type="text" name="phone2" class="form-control" placeholder="" >
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date of Birth: <span class="text-danger">*</span></label>
                                <input name="dob" value="{{ $sr->user->dob  }}" type="text" class="form-control date-pick" placeholder="Select Date..." required>

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nal_id">Nationality: <span class="text-danger">*</span></label>
                                <select data-placeholder="Choose..." required name="nal_id" id="nal_id" class="select-search form-control">
                                    <option value=""></option>
                                    @foreach($nationals as $na)
                                        <option {{  ($sr->user->nal_id  == $na->id ? 'selected' : '') }} value="{{ $na->id }}">{{ $na->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="state_id">State: <span class="text-danger">*</span></label>
                            <select onchange="getLGA(this.value)" required data-placeholder="Choose.." class="select-search form-control" name="state_id" id="state_id">
                                <option value=""></option>
                                @foreach($states as $st)
                                    <option {{ ($sr->user->state_id  == $st->id ? 'selected' : '') }} value="{{ $st->id }}">{{ $st->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="lga_id">District: <span class="text-danger">*</span></label>
                            <select required data-placeholder="Select State First" class="select-search form-control" name="lga_id" id="lga_id">
                                @if($sr->user->lga_id)
                                    <option selected value="{{ $sr->user->lga_id }}">{{ $sr->user->lga->name}}</option>
                                @endif
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="city">City: <span class="text-danger">*</span></label>
                            <input value="{{ $sr->city ?? $sr->user->city ?? '' }}" required type="text" name="city" id="city" placeholder="City" class="form-control">
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bg_id">Blood Group: </label>
                                <select class="select form-control" id="bg_id" name="bg_id" data-fouc data-placeholder="Choose..">
                                    <option value=""></option>
                                    @foreach(App\Models\BloodGroup::all() as $bg)
                                        <option {{ ($sr->user->bg_id  == $bg->id ? 'selected' : '') }} value="{{ $bg->id }}">{{ $bg->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Upload Passport Photo:</label>
                                <input value="{{ old('photo') }}" accept="image/*" type="file" name="photo" class="form-input-styled" data-fouc>
                                <span class="form-text text-muted">Accepted Images: jpeg, png. Max file size 2Mb</span>
                            </div>
                        </div>
                    </div>

                </fieldset>

                <h6>Student Data</h6>
                <fieldset>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="my_class_id">Class: </label>
                                <select onchange="getClassSections(this.value)" name="my_class_id" required id="my_class_id" class="form-control select-search" data-placeholder="Select Class">
                                    <option value=""></option>
                                    @foreach($my_classes as $c)
                                        <option {{ $sr->my_class_id == $c->id ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="section_id">Section: </label>
                                <select name="section_id" required id="section_id" class="form-control select" data-placeholder="Select Section">
                                    <option value="{{ $sr->section_id }}">{{ $sr->section->name }}</option>
                                </select>
                            </div>
                        </div>



                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="year_admitted">Year Admitted: </label>
                                <select name="year_admitted" data-placeholder="Choose..." id="year_admitted" class="select-search form-control">
                                    <option value=""></option>
                                    @for($y=date('Y', strtotime('- 10 years')); $y<=date('Y'); $y++)
                                        <option {{ ($sr->year_admitted == $y) ? 'selected' : '' }} value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="dorm_id">Dormitory: </label>
                            <select data-placeholder="Choose..."  name="dorm_id" id="dorm_id" class="select-search form-control">
                                <option value=""></option>
                                @foreach($dorms as $d)
                                    <option {{ ($sr->dorm_id == $d->id) ? 'selected' : '' }} value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Dormitory Room No:</label>
                                <input type="text" name="dorm_room_no" placeholder="Dormitory Room No" class="form-control" value="{{ $sr->dorm_room_no }}">
                            </div>
                        </div>
                    </div>

                    {{-- Family Details --}}
                    <div class="row mt-2">
                        <div class="col-12">
                            <h6 class="text-muted border-bottom pb-1 mb-2"><i class="icon-users mr-1"></i> Family Details</h6>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Father's Name: <span class="text-danger">*</span></label>
                                <input type="text" name="father_name" placeholder="Father's Full Name" class="form-control" value="{{ $sr->father_name }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mother's Name: <span class="text-danger">*</span></label>
                                <input type="text" name="mother_name" placeholder="Mother's Full Name" class="form-control" value="{{ $sr->mother_name }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Father's Occupation: <span class="text-danger">*</span></label>
                                <input type="text" name="father_occupation" placeholder="e.g. Farmer, Business" class="form-control" value="{{ $sr->father_occupation }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Yearly Income (₹): <span class="text-danger">*</span></label>
                                <input type="number" name="yearly_income" placeholder="Annual Income in ₹" class="form-control" min="0" step="0.01" value="{{ $sr->yearly_income }}" required>
                            </div>
                        </div>
                    </div>
                </fieldset>


                <h6>Documents</h6>
                <fieldset>
                    <div class="row">
                        {{-- Aadhaar Card --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="d-block">Aadhaar Card: {!! !$sr->aadhar_card ? '<span class="text-danger">*</span>' : '' !!}</label>
                                @if($sr->aadhar_card)
                                    <a href="{{ $sr->aadhar_card }}" target="_blank" class="btn btn-sm btn-light border mb-1">
                                        <i class="icon-file-check mr-1"></i> View Current
                                    </a>
                                @endif
                                <input type="file" name="aadhar_card" accept=".jpg,.jpeg,.png,.pdf" class="form-input-styled" data-fouc {{ !$sr->aadhar_card ? 'required' : '' }}>
                                <span class="form-text text-muted">Accepted: jpeg, png, pdf. Max 2MB</span>
                            </div>
                        </div>

                        {{-- Previous Marksheet --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="d-block">Previous Year Marksheet: {!! !$sr->prev_marksheet ? '<span class="text-danger">*</span>' : '' !!}</label>
                                @if($sr->prev_marksheet)
                                    <a href="{{ $sr->prev_marksheet }}" target="_blank" class="btn btn-sm btn-light border mb-1">
                                        <i class="icon-file-check mr-1"></i> View Current
                                    </a>
                                @endif
                                <input type="file" name="prev_marksheet" accept=".jpg,.jpeg,.png,.pdf" class="form-input-styled" data-fouc {{ !$sr->prev_marksheet ? 'required' : '' }}>
                                <span class="form-text text-muted">Accepted: jpeg, png, pdf. Max 5MB</span>
                            </div>
                        </div>

                        {{-- Birth Certificate --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="d-block">Birth Certificate: {!! !$sr->birth_certificate ? '<span class="text-danger">*</span>' : '' !!}</label>
                                @if($sr->birth_certificate)
                                    <a href="{{ $sr->birth_certificate }}" target="_blank" class="btn btn-sm btn-light border mb-1">
                                        <i class="icon-file-check mr-1"></i> View Current
                                    </a>
                                @endif
                                <input type="file" name="birth_certificate" accept=".jpg,.jpeg,.png,.pdf" class="form-input-styled" data-fouc {{ !$sr->birth_certificate ? 'required' : '' }}>
                                <span class="form-text text-muted">Accepted: jpeg, png, pdf. Max 2MB</span>
                            </div>
                        </div>
                    </div>
                </fieldset>

            </form>
        </div>
@endsection

