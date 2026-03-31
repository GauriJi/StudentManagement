@extends('layouts.master')
@section('page_title', 'System Settings')
@section('content')
<style>
.settings-card { border: none; border-radius: 16px; box-shadow: 0 2px 16px rgba(0,0,0,.08); }
.settings-section { border-radius: 12px; background: #f8fafc; border: 1px solid #edf2f7; padding: 20px 24px; margin-bottom: 20px; }
.settings-section h6 { font-weight: 700; color: #2d3748; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
.field-label { font-weight: 600; color: #4a5568; font-size: 13px; margin-bottom: 5px; }
.form-control { border-radius: 8px; border: 1.5px solid #e2e8f0; transition: border-color .2s; }
.form-control:focus { border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,.1); }
select.form-control { appearance: auto; }
.logo-preview { width: 80px; height: 80px; object-fit: contain; border-radius: 10px; border: 2px solid #e2e8f0; background: #fff; padding: 6px; }
.save-btn { background: linear-gradient(135deg, #667eea, #764ba2); border: none; border-radius: 10px; padding: 10px 32px; font-weight: 700; font-size: 14px; color: #fff; transition: opacity .2s; }
.save-btn:hover { opacity: .88; color: #fff; }
.badge-required { background: #fed7d7; color: #c53030; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 700; vertical-align: middle; }
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="font-weight-bold mb-0"><i class="icon-gear mr-2 text-primary"></i>System Settings</h5>
        <small class="text-muted">Manage school configuration</small>
    </div>
</div>

@if(session('flash_success'))
    <div class="alert alert-success border-0 alert-dismissible" style="border-radius:10px;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="icon-checkmark3 mr-2"></i>{{ session('flash_success') }}
    </div>
@endif

<div class="card settings-card">
    <div class="card-body p-4">
        <form enctype="multipart/form-data" method="POST" action="{{ route('settings.update') }}">
            @csrf @method('PUT')

            {{-- ── Section 1: School Identity ── --}}
            <div class="settings-section">
                <h6><i class="icon-school text-primary"></i> School Identity</h6>
                <div class="row">

                    {{-- School Name --}}
                    <div class="col-md-8 mb-3">
                        <label class="field-label">School Name <span class="badge-required">Required</span></label>
                        <input name="system_name"
                               value="{{ old('system_name', $s['system_name'] ?? '') }}"
                               type="text" class="form-control @error('system_name') is-invalid @enderror"
                               placeholder="e.g. Sunshine Public School" required>
                        @error('system_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Logo --}}
                    <div class="col-md-4 mb-3">
                        <label class="field-label">School Logo</label>
                        <div class="d-flex align-items-center gap-3">
                            <img class="logo-preview mr-3" id="logoPreview"
                                 src="{{ $s['logo'] ?? asset('images/no_image.jpg') }}" alt="Logo">
                            <div>
                                <input name="logo" type="file" accept="image/*" class="form-control-file"
                                       id="logoInput" onchange="previewLogo(this)">
                                <small class="text-muted d-block mt-1">JPG, PNG, GIF · Max 2MB</small>
                            </div>
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-4 mb-3">
                        <label class="field-label">Phone</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="border-radius:8px 0 0 8px;border:1.5px solid #e2e8f0;">
                                    <i class="icon-phone2"></i>
                                </span>
                            </div>
                            <input name="phone"
                                   value="{{ old('phone', $s['phone'] ?? '') }}"
                                   type="text" class="form-control" placeholder="+91 98765 43210"
                                   style="border-radius:0 8px 8px 0;">
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-4 mb-3">
                        <label class="field-label">Email <span class="badge-required">Required</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="border-radius:8px 0 0 8px;border:1.5px solid #e2e8f0;">
                                    <i class="icon-envelop5"></i>
                                </span>
                            </div>
                            <input name="system_email"
                                   value="{{ old('system_email', $s['system_email'] ?? '') }}"
                                   type="email" class="form-control @error('system_email') is-invalid @enderror"
                                   placeholder="school@example.com" required
                                   style="border-radius:0 8px 8px 0;">
                        </div>
                        @error('system_email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    {{-- Address --}}
                    <div class="col-md-4 mb-0">
                        <label class="field-label">Address <span class="badge-required">Required</span></label>
                        <input name="address"
                               value="{{ old('address', $s['address'] ?? '') }}"
                               type="text" class="form-control @error('address') is-invalid @enderror"
                               placeholder="123 School Road, City" required>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                </div>
            </div>

            {{-- ── Section 2: Academic Settings ── --}}
            <div class="settings-section">
                <h6><i class="icon-calendar3 text-warning"></i> Academic Settings</h6>
                <div class="row">

                    {{-- Academic Session --}}
                    <div class="col-md-3 mb-3">
                        <label class="field-label">Academic Session <span class="badge-required">Required</span></label>
                        <select name="current_session" class="form-control @error('current_session') is-invalid @enderror" required>
                            <option value="">— Select —</option>
                            @for($y = date('Y', strtotime('-3 years')); $y <= date('Y', strtotime('+2 years')); $y++)
                                @php $session = ($y) . '-' . ($y + 1); @endphp
                                <option value="{{ $session }}" {{ ($s['current_session'] ?? '') == $session ? 'selected' : '' }}>
                                    {{ $session }}
                                </option>
                            @endfor
                        </select>
                        @error('current_session')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Term Start --}}
                    <div class="col-md-3 mb-3">
                        <label class="field-label">Term Start Date</label>
                        <input name="term_begins"
                               value="{{ old('term_begins', $s['term_begins'] ?? '') }}"
                               type="date" class="form-control"
                               placeholder="YYYY-MM-DD">
                    </div>

                    {{-- Term End --}}
                    <div class="col-md-3 mb-3">
                        <label class="field-label">Term End Date</label>
                        <input name="term_ends"
                               value="{{ old('term_ends', $s['term_ends'] ?? '') }}"
                               type="date" class="form-control"
                               placeholder="YYYY-MM-DD">
                    </div>

                    {{-- Result Lock --}}
                    <div class="col-md-3 mb-3">
                        <label class="field-label">Result Lock <span class="badge-required">Required</span></label>
                        <select name="lock_exam" class="form-control" required>
                            <option value="1" {{ ($s['lock_exam'] ?? 0) == 1 ? 'selected' : '' }}>
                                🔒 Locked (Results hidden)
                            </option>
                            <option value="0" {{ ($s['lock_exam'] ?? 0) == 0 ? 'selected' : '' }}>
                                🔓 Unlocked (Results visible)
                            </option>
                        </select>
                        <small class="text-muted">When locked, students cannot view exam results.</small>
                    </div>

                </div>
            </div>

            {{-- Save Button --}}
            <div class="text-right">
                <button type="submit" class="save-btn">
                    <i class="icon-checkmark3 mr-2"></i>Save Settings
                </button>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('logoPreview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
