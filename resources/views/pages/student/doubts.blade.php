@extends('layouts.master')
@section('page_title', 'Doubt Query')
@section('content')
<style>
.doubt-hero{background:linear-gradient(135deg,#1e3a5f,#0f172a);border-radius:16px;padding:24px 28px;color:#fff;margin-bottom:24px;box-shadow:0 8px 32px rgba(0,0,0,.2);}
.doubt-form-card{border-radius:14px;border:none;box-shadow:0 4px 20px rgba(0,0,0,.08);margin-bottom:24px;overflow:hidden;}
.doubt-form-card .form-header{background:linear-gradient(90deg,#3b82f6,#1d4ed8);color:#fff;padding:14px 20px;font-weight:700;font-size:.95rem;}
.doubt-item{border-radius:14px;border:none;box-shadow:0 4px 14px rgba(0,0,0,.07);margin-bottom:14px;overflow:hidden;}
.doubt-item .d-question{border-left:4px solid #3b82f6;padding:14px 18px;background:#f8fafc;}
.doubt-item .d-answer{border-left:4px solid #10b981;padding:14px 18px;background:#f0fdf4;}
.doubt-item .d-unanswered{border-left:4px solid #e5e7eb;padding:14px 18px;background:#fff;color:#94a3b8;font-style:italic;font-size:.85rem;}
.badge-open    {background:#fef9c3;color:#854d0e;font-weight:700;}
.badge-answered{background:#dcfce7;color:#166534;font-weight:700;}
</style>

<div class="doubt-hero d-flex align-items-center" style="gap:16px">
    <i class="icon-question7" style="font-size:2rem;color:#a78bfa"></i>
    <div>
        <h4 class="mb-0 font-weight-bold">Doubt Query</h4>
        <small style="opacity:.7">Ask your teacher a question &bull; {{ optional(optional($sr)->my_class)->name }}</small>
    </div>
    <div class="ml-auto d-flex" style="gap:10px">
        <span class="badge" style="background:rgba(255,255,255,.2);color:#fff;border-radius:30px;padding:6px 14px;font-size:.82rem">
            {{ $doubts->count() }} Total &bull; {{ $doubts->where('status','open')->count() }} Open
        </span>
    </div>
</div>

{{-- Post New Doubt --}}
<div class="doubt-form-card">
    <div class="form-header"><i class="icon-plus2"></i> Ask a New Question</div>
    <div class="card-body p-4">
        <form action="{{ route('student.doubts.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="font-weight-semibold">Subject</label>
                    <select name="subject_id" class="form-control">
                        <option value="">— Any Subject —</option>
                        @foreach($subjects as $sub)
                        <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label class="font-weight-semibold">Teacher (optional)</label>
                    <select name="teacher_id" class="form-control">
                        <option value="">— Any Teacher —</option>
                        @foreach($teachers as $t)
                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="font-weight-semibold">Your Question</label>
                <textarea name="question" class="form-control" rows="3" placeholder="Type your doubt or question here…" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-rounded">
                <i class="icon-paperplane"></i> Submit Doubt
            </button>
        </form>
    </div>
</div>

{{-- Existing doubts --}}
<h5 class="font-weight-bold mb-3" style="color:#1e3a5f"><i class="icon-list3 text-primary"></i> Previous Queries</h5>
@forelse($doubts as $d)
<div class="doubt-item">
    <div class="d-question">
        <div class="d-flex align-items-start justify-content-between mb-1">
            <strong style="color:#1e3a5f;font-size:.9rem">Q: {{ $d->question }}</strong>
            <span class="badge badge-{{ $d->status }} ml-2 px-2 py-1" style="border-radius:30px;font-size:.72rem">{{ ucfirst($d->status) }}</span>
        </div>
        <div style="font-size:.75rem;color:#94a3b8">
            {{ optional($d->subject)->name ?? 'General' }} &bull;
            To: {{ optional($d->teacher)->name ?? 'Any Teacher' }} &bull;
            {{ $d->created_at->format('d M Y') }}
        </div>
    </div>
    @if($d->answer)
    <div class="d-answer">
        <div style="font-size:.78rem;font-weight:700;color:#15803d;margin-bottom:4px"><i class="icon-checkmark3"></i> Teacher's Answer</div>
        <p style="margin:0;color:#166534;font-size:.88rem">{{ $d->answer }}</p>
    </div>
    @else
    <div class="d-unanswered"><i class="icon-clock3"></i> Awaiting teacher's reply…</div>
    @endif
</div>
@empty
<div class="text-center py-5">
    <i class="icon-question7" style="font-size:3.5rem;color:#cbd5e1"></i>
    <p class="mt-3 text-muted">No doubts submitted yet. Ask your first question above!</p>
</div>
@endforelse
@endsection
