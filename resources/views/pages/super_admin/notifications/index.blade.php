@extends('layouts.master')
@section('page_title', 'Notifications')

@section('content')
<div class="mb-3">
    <h5 class="font-weight-bold mb-0"><i class="icon-bell2 mr-2 text-warning"></i>Notifications</h5>
    <small class="text-muted">Send announcements to students and teachers</small>
</div>

@if(session('flash_success'))
    <div class="alert alert-success alert-dismissible border-0"><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>{{ session('flash_success') }}</div>
@endif

<div class="row">
    {{-- Send Notification Form --}}
    <div class="col-md-5 mb-3">
        <div class="card border-0 shadow-sm" style="border-radius:14px;">
            <div class="card-header bg-white border-0">
                <h6 class="font-weight-bold mb-0"><i class="icon-paperplane mr-2 text-primary"></i>Send New Notification</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('sa.notifications.send') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="font-weight-semibold">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" placeholder="Notification title..." required style="border-radius:8px;">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="font-weight-semibold">Message <span class="text-danger">*</span></label>
                        <textarea name="message" rows="4" class="form-control @error('message') is-invalid @enderror"
                                  placeholder="Write your message here..." required style="border-radius:8px;">{{ old('message') }}</textarea>
                        @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="font-weight-semibold">Send To <span class="text-danger">*</span></label>
                        <select name="target" id="targetSelect" class="form-control" required onchange="toggleClassSelect(this)" style="border-radius:8px;">
                            <option value="">— Select Target —</option>
                            <option value="all_students" {{ old('target') == 'all_students' ? 'selected' : '' }}>All Students</option>
                            <option value="all_teachers" {{ old('target') == 'all_teachers' ? 'selected' : '' }}>All Teachers</option>
                            <option value="class_students" {{ old('target') == 'class_students' ? 'selected' : '' }}>Specific Class Students</option>
                        </select>
                    </div>
                    <div class="mb-3" id="classSelectWrap" style="{{ old('target') == 'class_students' ? '' : 'display:none;' }}">
                        <label class="font-weight-semibold">Select Class</label>
                        <select name="class_id" class="form-control" style="border-radius:8px;">
                            <option value="">— Select Class —</option>
                            @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" style="border-radius:8px;"><i class="icon-paperplane mr-1"></i> Send Notification</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Recent Notifications History --}}
    <div class="col-md-7 mb-3">
        <div class="card border-0 shadow-sm" style="border-radius:14px;">
            <div class="card-header bg-white border-0">
                <h6 class="font-weight-bold mb-0"><i class="icon-history mr-2 text-secondary"></i>Recent Notifications Sent</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light"><tr><th>Title</th><th>Sent To</th><th>Date</th></tr></thead>
                        <tbody>
                        @forelse($recent_notifications as $n)
                        <tr>
                            <td>
                                <div class="font-weight-semibold">{{ \Str::limit($n->title, 40) }}</div>
                                <small class="text-muted">{{ \Str::limit($n->message, 60) }}</small>
                            </td>
                            <td>
                                @if($n->student)
                                <small class="badge badge-info">{{ $n->student->name }}</small>
                                @else <span class="text-muted">—</span> @endif
                            </td>
                            <td><small class="text-muted">{{ \Carbon\Carbon::parse($n->created_at)->format('d M Y') }}</small></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">No notifications sent yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleClassSelect(sel) {
    document.getElementById('classSelectWrap').style.display = sel.value === 'class_students' ? '' : 'none';
}
</script>
@endpush
@endsection
