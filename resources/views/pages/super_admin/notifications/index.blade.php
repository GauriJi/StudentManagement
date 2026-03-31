@extends('layouts.master')
@section('page_title', 'Notifications')

@section('content')
<style>
.notif-hero{background:linear-gradient(135deg,#1e3a5f 0%,#0f172a 100%);border-radius:18px;padding:28px 32px;color:#fff;margin-bottom:28px;box-shadow:0 8px 32px rgba(0,0,0,.22);}
.glass-card{background:rgba(255,255,255,.97);border-radius:16px;border:none;box-shadow:0 4px 24px rgba(0,0,0,.07);}
.target-checkbox{display:none;}
.target-label{display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:10px;border:2px solid #e2e8f0;cursor:pointer;transition:all .25s;margin-bottom:8px;background:#f8fafc;}
.target-label:hover{border-color:#3b82f6;background:#eff6ff;}
.target-checkbox:checked + .target-label{border-color:#3b82f6;background:linear-gradient(135deg,#eff6ff,#dbeafe);box-shadow:0 2px 8px rgba(59,130,246,.15);}
.target-checkbox:checked + .target-label .target-check{background:#3b82f6;border-color:#3b82f6;color:#fff;}
.target-check{width:22px;height:22px;border-radius:6px;border:2px solid #cbd5e1;display:flex;align-items:center;justify-content:center;font-size:12px;color:transparent;transition:all .2s;flex-shrink:0;}
.target-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;}
.icon-students{background:linear-gradient(135deg,#3b82f6,#1d4ed8);color:#fff;}
.icon-teachers{background:linear-gradient(135deg,#10b981,#059669);color:#fff;}
.icon-parents{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;}
.icon-class{background:linear-gradient(135deg,#8b5cf6,#6d28d9);color:#fff;}
.history-badge{font-size:.7rem;padding:3px 10px;border-radius:20px;font-weight:600;letter-spacing:.5px;text-transform:uppercase;}
.badge-student{background:#dbeafe;color:#1d4ed8;}
.badge-teacher{background:#d1fae5;color:#059669;}
.badge-parent{background:#fef3c7;color:#d97706;}
.notif-row{border-bottom:1px solid #f1f5f9;padding:14px 0;transition:background .2s;}
.notif-row:hover{background:#f8fafc;}
.notif-row:last-child{border-bottom:none;}
.counter-pill{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.15);padding:6px 16px;border-radius:30px;font-size:.85rem;font-weight:600;}
</style>

{{-- Hero Header --}}
<div class="notif-hero d-flex align-items-center justify-content-between flex-wrap" style="gap:16px">
    <div class="d-flex align-items-center" style="gap:16px">
        <i class="icon-bell2" style="font-size:2.2rem;color:#fcd34d"></i>
        <div>
            <h4 class="mb-0 font-weight-bold">Notification Center</h4>
            <small style="opacity:.7">Send announcements to students, teachers & parents</small>
        </div>
    </div>
    <div class="d-flex" style="gap:10px">
        <span class="counter-pill"><i class="icon-users4"></i> Students</span>
        <span class="counter-pill"><i class="icon-user-tie"></i> Teachers</span>
        <span class="counter-pill"><i class="icon-users2"></i> Parents</span>
    </div>
</div>

@if(session('flash_success'))
    <div class="alert alert-success alert-dismissible border-0" style="border-radius:12px;">
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        <i class="icon-checkmark-circle2 mr-2"></i>{{ session('flash_success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible border-0" style="border-radius:12px;">
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="row">
    {{-- Send Notification Form --}}
    <div class="col-lg-5 mb-4">
        <div class="card glass-card">
            <div class="card-header bg-white border-0 pt-4 pb-2">
                <h6 class="font-weight-bold mb-0"><i class="icon-paperplane mr-2 text-primary"></i>Compose Notification</h6>
            </div>
            <div class="card-body pt-2">
                <form action="{{ route('sa.notifications.send') }}" method="POST" id="notifForm">
                    @csrf

                    {{-- Title --}}
                    <div class="mb-3">
                        <label class="font-weight-semibold">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" placeholder="e.g. School Holiday Notice" required style="border-radius:10px;padding:10px 14px;">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Message --}}
                    <div class="mb-3">
                        <label class="font-weight-semibold">Message <span class="text-danger">*</span></label>
                        <textarea name="message" rows="4" class="form-control @error('message') is-invalid @enderror"
                                  placeholder="Write your notification message here..." required style="border-radius:10px;padding:10px 14px;">{{ old('message') }}</textarea>
                        @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Target Recipients (Checkboxes) --}}
                    <div class="mb-3">
                        <label class="font-weight-semibold mb-2">Send To <span class="text-danger">*</span></label>
                        <small class="text-muted d-block mb-2">Select one or more recipient groups</small>

                        {{-- All Students --}}
                        <input type="checkbox" name="targets[]" value="all_students" id="tgt_students" class="target-checkbox"
                            {{ is_array(old('targets')) && in_array('all_students', old('targets')) ? 'checked' : '' }}>
                        <label for="tgt_students" class="target-label">
                            <span class="target-check"><i class="icon-checkmark3"></i></span>
                            <span class="target-icon icon-students"><i class="icon-users4"></i></span>
                            <div>
                                <div class="font-weight-semibold" style="font-size:.95rem;">All Students</div>
                                <small class="text-muted">Send to every student in the school</small>
                            </div>
                        </label>

                        {{-- All Teachers --}}
                        <input type="checkbox" name="targets[]" value="all_teachers" id="tgt_teachers" class="target-checkbox"
                            {{ is_array(old('targets')) && in_array('all_teachers', old('targets')) ? 'checked' : '' }}>
                        <label for="tgt_teachers" class="target-label">
                            <span class="target-check"><i class="icon-checkmark3"></i></span>
                            <span class="target-icon icon-teachers"><i class="icon-user-tie"></i></span>
                            <div>
                                <div class="font-weight-semibold" style="font-size:.95rem;">All Teachers</div>
                                <small class="text-muted">Send to every teacher in the school</small>
                            </div>
                        </label>

                        {{-- All Parents --}}
                        <input type="checkbox" name="targets[]" value="all_parents" id="tgt_parents" class="target-checkbox"
                            {{ is_array(old('targets')) && in_array('all_parents', old('targets')) ? 'checked' : '' }}>
                        <label for="tgt_parents" class="target-label">
                            <span class="target-check"><i class="icon-checkmark3"></i></span>
                            <span class="target-icon icon-parents"><i class="icon-users2"></i></span>
                            <div>
                                <div class="font-weight-semibold" style="font-size:.95rem;">All Parents</div>
                                <small class="text-muted">Send to every parent in the school</small>
                            </div>
                        </label>

                        {{-- Specific Class Students --}}
                        <input type="checkbox" name="targets[]" value="class_students" id="tgt_class" class="target-checkbox"
                            {{ is_array(old('targets')) && in_array('class_students', old('targets')) ? 'checked' : '' }}>
                        <label for="tgt_class" class="target-label">
                            <span class="target-check"><i class="icon-checkmark3"></i></span>
                            <span class="target-icon icon-class"><i class="icon-library"></i></span>
                            <div>
                                <div class="font-weight-semibold" style="font-size:.95rem;">Specific Class</div>
                                <small class="text-muted">Send only to students in a specific class</small>
                            </div>
                        </label>
                    </div>

                    {{-- Class Selector (only visible when "Specific Class" is checked) --}}
                    <div class="mb-3" id="classSelectWrap" style="{{ is_array(old('targets')) && in_array('class_students', old('targets')) ? '' : 'display:none;' }}">
                        <label class="font-weight-semibold">Select Class</label>
                        <select name="class_id" class="form-control" style="border-radius:10px;padding:10px 14px;">
                            <option value="">— Select Class —</option>
                            @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="border-radius:10px;padding:12px;font-weight:600;font-size:1rem;">
                        <i class="icon-paperplane mr-1"></i> Send Notification
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Recent Notifications History --}}
    <div class="col-lg-7 mb-4">
        <div class="card glass-card">
            <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold mb-0"><i class="icon-history mr-2 text-secondary"></i>Recent Notifications</h6>
                <small class="text-muted">Last 30 sent</small>
            </div>
            <div class="card-body p-0">
                <div class="px-4">
                    @forelse($recent_notifications as $n)
                    <div class="notif-row d-flex align-items-start" style="gap:14px">
                        <div style="margin-top:3px;">
                            @if($n->target_type === 'student')
                                <span class="history-badge badge-student"><i class="icon-users4 mr-1"></i>Student</span>
                            @elseif($n->target_type === 'teacher')
                                <span class="history-badge badge-teacher"><i class="icon-user-tie mr-1"></i>Teacher</span>
                            @elseif($n->target_type === 'parent')
                                <span class="history-badge badge-parent"><i class="icon-users2 mr-1"></i>Parent</span>
                            @endif
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="font-weight-semibold text-truncate" style="font-size:.95rem;">{{ \Illuminate\Support\Str::limit($n->title, 50) }}</div>
                            <small class="text-muted">{{ \Illuminate\Support\Str::limit($n->message, 80) }}</small>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <small class="text-muted text-nowrap"><i class="icon-history mr-1"></i>{{ \Carbon\Carbon::parse($n->created_at)->format('d M Y') }}</small>
                            <br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($n->created_at)->format('h:i A') }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5">
                        <i class="icon-bell2 d-block mb-3" style="font-size:3rem;opacity:.3;"></i>
                        <p class="font-weight-semibold">No notifications sent yet</p>
                        <small>Compose your first notification using the form</small>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var classCheck = document.getElementById('tgt_class');
    var classWrap = document.getElementById('classSelectWrap');
    var studentsCheck = document.getElementById('tgt_students');

    function toggleClassSelect() {
        if (classCheck.checked && !studentsCheck.checked) {
            classWrap.style.display = '';
        } else {
            classWrap.style.display = 'none';
        }
    }

    classCheck.addEventListener('change', toggleClassSelect);
    studentsCheck.addEventListener('change', function() {
        // If "All Students" is checked, uncheck and disable "Specific Class"
        if (studentsCheck.checked) {
            classCheck.checked = false;
        }
        toggleClassSelect();
    });

    // Form validation: at least one target must be selected
    document.getElementById('notifForm').addEventListener('submit', function(e) {
        var checked = document.querySelectorAll('.target-checkbox:checked');
        if (checked.length === 0) {
            e.preventDefault();
            alert('Please select at least one recipient group.');
        }
    });
});
</script>
@endpush
@endsection
