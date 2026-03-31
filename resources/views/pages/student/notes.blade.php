@extends('layouts.master')
@section('page_title', 'Class Notes')
@section('content')
<style>
.note-hero{background:linear-gradient(135deg,#1e3a5f,#0f172a);border-radius:16px;padding:24px 28px;color:#fff;margin-bottom:24px;box-shadow:0 8px 32px rgba(0,0,0,.2);}
.note-card{border-radius:14px;border:none;box-shadow:0 4px 16px rgba(0,0,0,.08);margin-bottom:20px;transition:all .3s ease;}
.note-card:hover{transform:translateY(-5px);}
.note-subject{font-size:.7rem;font-weight:800;text-transform:uppercase;color:#3b82f6;letter-spacing:1px;margin-bottom:8px;}
.note-title{font-weight:800;color:#1e293b;margin-bottom:10px;font-size:1.1rem;}
.note-meta{font-size:.8rem;color:#94a3b8;display:flex;align-items:center;gap:15px;}
.btn-download{background:#eff6ff;color:#3b82f6;border-radius:10px;font-weight:700;padding:8px 16px;border:none;transition:all .2s;}
.btn-download:hover{background:#3b82f6;color:#fff;}
</style>

<div class="note-hero d-flex align-items-center" style="gap:16px">
    <i class="icon-file-text2" style="font-size:2rem;color:#38bdf8"></i>
    <div>
        <h4 class="mb-0 font-weight-bold">Class Notes</h4>
        <small style="opacity:.7">Shared by your teachers for this session</small>
    </div>
</div>

@if($notes->count() > 0)
<div class="row">
    @foreach($notes as $n)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card note-card shadow-sm h-100">
            <div class="card-body">
                <div class="note-subject">{{ optional($n->subject)->name }}</div>
                <div class="note-title">{{ $n->title }}</div>
                <p class="text-muted" style="font-size:.88rem;line-height:1.6;margin-bottom:20px">
                    {{ \Illuminate\Support\Str::limit($n->description, 100) }}
                </p>
                <div class="note-meta mb-3">
                    <span><i class="icon-user"></i> {{ optional($n->teacher)->name }}</span>
                    <span><i class="icon-calendar3"></i> {{ $n->created_at->format('d M') }}</span>
                </div>
                @if($n->file_path)
                <a href="{{ asset($n->file_path) }}" class="btn-download btn-block text-center" target="_blank">
                   <i class="icon-download4 mr-1"></i> Download Note
                </a>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="text-center py-5">
    <i class="icon-file-text2" style="font-size:4rem;color:#cbd5e1"></i>
    <h5 class="mt-3 text-muted">No notes shared for your class yet.</h5>
</div>
@endif
@endsection
