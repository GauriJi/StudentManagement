{{--
    Shared Student Form Partial
    Variables expected:
      $isEdit   — true (edit mode) | false (create mode)
      $sr       — StudentRecord model (edit only, null on create)
    Data arrays (always passed):
      $my_classes, $parents, $dorms, $states, $nationals
--}}

@php
    // Helper: resolve value for a field (edit = existing data, create = old() input)
    // Usage: val('field_on_user', 'field_on_sr')
    $val = function(string $userField, string $srField = '') use ($isEdit, $sr) {
        if ($isEdit && $sr) {
            if ($srField && isset($sr->$srField))  return $sr->$srField;
            if (isset($sr->user->$userField))       return $sr->user->$userField;
        }
        return old($userField) ?? '';
    };
    $valSr = function(string $field) use ($isEdit, $sr) {
        return ($isEdit && $sr) ? ($sr->$field ?? '') : old($field, '');
    };
@endphp

{{-- ════════════════════════════════════════════════ --}}
{{-- STEP 1 — Personal Data                          --}}
{{-- ════════════════════════════════════════════════ --}}
<h6>Personal Data</h6>
<fieldset>

    {{-- Name / Address --}}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Full Name: <span class="text-danger">*</span></label>
                <input value="{{ $val('name') }}" required type="text" name="name"
                       placeholder="Full Name" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Address: <span class="text-danger">*</span></label>
                <input value="{{ $val('address') }}" required type="text" name="address"
                       placeholder="Address" class="form-control">
            </div>
        </div>
    </div>

    {{-- Email / Gender / Phone / Phone2 --}}
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label>Email Address: <span class="text-danger">*</span></label>
                <input value="{{ $val('email') }}" required type="email" name="email"
                       class="form-control" placeholder="your@email.com">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="gender">Gender: <span class="text-danger">*</span></label>
                <select class="select form-control" id="gender" name="gender" required
                        data-fouc data-placeholder="Choose..">
                    <option value=""></option>
                    <option {{ $val('gender') == 'Male'   ? 'selected' : '' }} value="Male">Male</option>
                    <option {{ $val('gender') == 'Female' ? 'selected' : '' }} value="Female">Female</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Phone:</label>
                <input value="{{ $val('phone') }}" type="text" name="phone"
                       class="form-control" placeholder="Mobile number">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Telephone:</label>
                <input value="{{ $val('phone2') }}" type="text" name="phone2"
                       class="form-control" placeholder="Alternate number">
            </div>
        </div>
    </div>

    {{-- DOB / Nationality / State / District / City --}}
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label>Date of Birth: <span class="text-danger">*</span></label>
                <input name="dob" value="{{ $val('dob') }}" type="text"
                       class="form-control date-pick" placeholder="Select Date..." required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="nal_id">Nationality: <span class="text-danger">*</span></label>
                <select data-placeholder="Choose..." required name="nal_id" id="nal_id"
                        class="select-search form-control">
                    <option value=""></option>
                    @foreach($nationals as $nal)
                        <option {{ $val('nal_id') == $nal->id ? 'selected' : '' }}
                                value="{{ $nal->id }}">{{ $nal->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="state_id">State: <span class="text-danger">*</span></label>
                <select onchange="getLGA(this.value)" required data-placeholder="Choose.."
                        class="select-search form-control" name="state_id" id="state_id">
                    <option value=""></option>
                    @foreach($states as $st)
                        <option {{ $val('state_id') == $st->id ? 'selected' : '' }}
                                value="{{ $st->id }}">{{ $st->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="lga_id">District: <span class="text-danger">*</span></label>
                <select required data-placeholder="Select State First"
                        class="select-search form-control" name="lga_id" id="lga_id">
                    @if($isEdit && $sr && $sr->user->lga_id)
                        <option selected value="{{ $sr->user->lga_id }}">
                            {{ $sr->user->lga->name ?? '' }}
                        </option>
                    @else
                        <option value=""></option>
                    @endif
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="city">City: <span class="text-danger">*</span></label>
                @php $cityVal = $isEdit ? ($sr->city ?? $sr->user->city ?? '') : old('city',''); @endphp
                <input value="{{ $cityVal }}" required type="text" name="city"
                       id="city" placeholder="City" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="bg_id">Blood Group:</label>
                <select class="select form-control" id="bg_id" name="bg_id"
                        data-fouc data-placeholder="Choose..">
                    <option value=""></option>
                    @foreach(\App\Models\BloodGroup::all() as $bg)
                        <option {{ $val('bg_id') == $bg->id ? 'selected' : '' }}
                                value="{{ $bg->id }}">{{ $bg->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="d-block">
                    Passport Photo:
                    @if($isEdit && $sr && $sr->user->photo && !str_contains($sr->user->photo,'no_image'))
                        <a href="{{ $sr->user->photo }}" target="_blank"
                           class="btn btn-sm btn-light border ml-2">
                            <i class="icon-eye mr-1"></i> View Current
                        </a>
                    @endif
                </label>
                <input value="{{ old('photo') }}" accept="image/*" type="file"
                       name="photo" class="form-input-styled" data-fouc>
                <span class="form-text text-muted">jpeg, png — Max 2MB. Leave blank to keep existing.</span>
            </div>
        </div>
    </div>

</fieldset>

{{-- ════════════════════════════════════════════════ --}}
{{-- STEP 2 — Student / Academic Data                 --}}
{{-- ════════════════════════════════════════════════ --}}
<h6>Student Data</h6>
<fieldset>

    <div class="row">
        {{-- Class --}}
        <div class="col-md-3">
            <div class="form-group">
                <label for="my_class_id">Class: <span class="text-danger">*</span></label>
                <select onchange="getClassSections(this.value)" data-placeholder="Choose..."
                        required name="my_class_id" id="my_class_id"
                        class="select-search form-control">
                    <option value=""></option>
                    @foreach($my_classes as $c)
                        <option {{ $valSr('my_class_id') == $c->id ? 'selected' : '' }}
                                value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Section --}}
        <div class="col-md-3">
            <div class="form-group">
                <label for="section_id">Section: <span class="text-danger">*</span></label>
                <select data-placeholder="Select Class First" required name="section_id"
                        id="section_id" class="select-search form-control">
                    @if($isEdit && $sr)
                        <option selected value="{{ $sr->section_id }}">{{ $sr->section->name ?? '' }}</option>
                    @else
                        <option {{ old('section_id') ? 'selected' : '' }} value="{{ old('section_id') }}">
                            {{ old('section_id') ? 'Selected' : '' }}
                        </option>
                    @endif
                </select>
            </div>
        </div>

        {{-- Year Admitted (read-only on create, visible dropdown on edit) --}}
        <div class="col-md-3">
            <div class="form-group">
                <label for="year_admitted">Year Admitted:</label>
                @if($isEdit)
                    <select name="year_admitted" data-placeholder="Choose..."
                            id="year_admitted" class="select-search form-control">
                        <option value=""></option>
                        @for($y = date('Y', strtotime('-10 years')); $y <= date('Y'); $y++)
                            <option {{ $valSr('year_admitted') == $y ? 'selected' : '' }}
                                    value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                @else
                    <div class="form-control bg-light text-muted" style="cursor:default">
                        <i class="icon-calendar3 mr-1 text-primary"></i>
                        {{ date('Y') }} <small>(auto)</small>
                    </div>
                    <span class="form-text text-muted">Set automatically to current year.</span>
                @endif
            </div>
        </div>

        {{-- Admission No (read-only on create, displayed info on edit) --}}
        <div class="col-md-3">
            <div class="form-group">
                <label>Admission Number:</label>
                @if($isEdit && $sr)
                    <div class="form-control bg-light font-weight-semibold"
                         style="cursor:default;font-family:monospace">
                        {{ $sr->adm_no }}
                    </div>
                    <span class="form-text text-muted">Cannot be changed after admission.</span>
                @else
                    <div class="form-control bg-light text-muted" style="cursor:default">
                        <i class="icon-medal2 mr-1 text-success"></i> Auto-generated
                    </div>
                    <span class="form-text text-muted">Format: <code>CODE/TYPE/{{ date('Y') }}/001</code></span>
                @endif
            </div>
        </div>
    </div>

    {{-- Dormitory / Room / House --}}
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="dorm_id">Dormitory:</label>
                <select data-placeholder="Choose..." name="dorm_id" id="dorm_id"
                        class="select-search form-control">
                    <option value=""></option>
                    @foreach($dorms as $d)
                        <option {{ $valSr('dorm_id') == $d->id ? 'selected' : '' }}
                                value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Dormitory Room No:</label>
                <input type="text" name="dorm_room_no" placeholder="Room number"
                       class="form-control" value="{{ $valSr('dorm_room_no') }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Sport House:</label>
                <input type="text" name="house" placeholder="Sport House"
                       class="form-control" value="{{ $valSr('house') }}">
            </div>
        </div>
    </div>

    {{-- Family Details --}}
    <div class="row mt-2">
        <div class="col-12">
            <h6 class="text-muted border-bottom pb-1 mb-2">
                <i class="icon-users mr-1"></i> Family Details
            </h6>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Father's Name: <span class="text-danger">*</span></label>
                <input type="text" name="father_name" placeholder="Father's Full Name"
                       class="form-control" value="{{ $valSr('father_name') }}" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Mother's Name: <span class="text-danger">*</span></label>
                <input type="text" name="mother_name" placeholder="Mother's Full Name"
                       class="form-control" value="{{ $valSr('mother_name') }}" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Father's Occupation: <span class="text-danger">*</span></label>
                <input type="text" name="father_occupation" placeholder="e.g. Farmer, Business"
                       class="form-control" value="{{ $valSr('father_occupation') }}" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Yearly Income (₹): <span class="text-danger">*</span></label>
                <input type="number" name="yearly_income" placeholder="Annual Income in ₹"
                       class="form-control" min="0" step="0.01"
                       value="{{ $valSr('yearly_income') }}" required>
            </div>
        </div>
    </div>

</fieldset>

{{-- ════════════════════════════════════════════════ --}}
{{-- STEP 3 — Documents                              --}}
{{-- ════════════════════════════════════════════════ --}}
<h6>Documents</h6>
<fieldset>
    <div class="row">

        {{-- Aadhaar --}}
        <div class="col-md-4">
            <div class="form-group">
                <label class="d-block">
                    Aadhaar Card:
                    @if(!($isEdit && $sr && $sr->aadhar_card))
                        <span class="text-danger">*</span>
                    @endif
                </label>
                @if($isEdit && $sr && $sr->aadhar_card)
                    <a href="{{ $sr->aadhar_card }}" target="_blank"
                       class="btn btn-sm btn-light border mb-1">
                        <i class="icon-file-check mr-1"></i> View Current
                    </a>
                @endif
                <input type="file" name="aadhar_card" accept=".jpg,.jpeg,.png,.pdf"
                       class="form-input-styled" data-fouc
                       {{ (!$isEdit || !($sr->aadhar_card ?? false)) ? 'required' : '' }}>
                <span class="form-text text-muted">jpeg, png, pdf — Max 2MB</span>
            </div>
        </div>

        {{-- Previous Marksheet --}}
        <div class="col-md-4">
            <div class="form-group">
                <label class="d-block">
                    Previous Marksheet:
                    @if(!($isEdit && $sr && $sr->prev_marksheet))
                        <span class="text-danger">*</span>
                    @endif
                </label>
                @if($isEdit && $sr && $sr->prev_marksheet)
                    <a href="{{ $sr->prev_marksheet }}" target="_blank"
                       class="btn btn-sm btn-light border mb-1">
                        <i class="icon-file-check mr-1"></i> View Current
                    </a>
                @endif
                <input type="file" name="prev_marksheet" accept=".jpg,.jpeg,.png,.pdf"
                       class="form-input-styled" data-fouc
                       {{ (!$isEdit || !($sr->prev_marksheet ?? false)) ? 'required' : '' }}>
                <span class="form-text text-muted">jpeg, png, pdf — Max 5MB</span>
            </div>
        </div>

        {{-- Birth Certificate --}}
        <div class="col-md-4">
            <div class="form-group">
                <label class="d-block">
                    Birth Certificate:
                    @if(!($isEdit && $sr && $sr->birth_certificate))
                        <span class="text-danger">*</span>
                    @endif
                </label>
                @if($isEdit && $sr && $sr->birth_certificate)
                    <a href="{{ $sr->birth_certificate }}" target="_blank"
                       class="btn btn-sm btn-light border mb-1">
                        <i class="icon-file-check mr-1"></i> View Current
                    </a>
                @endif
                <input type="file" name="birth_certificate" accept=".jpg,.jpeg,.png,.pdf"
                       class="form-input-styled" data-fouc
                       {{ (!$isEdit || !($sr->birth_certificate ?? false)) ? 'required' : '' }}>
                <span class="form-text text-muted">jpeg, png, pdf — Max 2MB</span>
            </div>
        </div>

    </div>
</fieldset>
