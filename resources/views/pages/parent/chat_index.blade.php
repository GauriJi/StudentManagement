@extends('layouts.master')
@section('page_title', 'Chat with Teacher')
@section('content')
<style>
.teacher-card{border-radius:14px;border:none;transition:all .2s;}
.teacher-card:hover{transform:translateY(-3px);box-shadow:0 8px 20px rgba(0,0,0,.1)!important;}
.teacher-avatar{width:55px;height:55px;border-radius:14px;object-fit:cover;}
</style>

<div class="mb-3">
    <h5 class="font-weight-bold mb-0"><i class="icon-bubbles4 mr-2 text-primary"></i>Chat with Teachers</h5>
    <small class="text-muted">Message the teachers of your children's classes</small>
</div>

@if($teachers->isEmpty())
<div class="text-center text-muted py-5">
    <i class="icon-bubbles4 d-block mb-3" style="font-size:3rem;opacity:.3;"></i>
    No teachers found for your children's classes yet.
</div>
@else
<div class="row">
@foreach($teachers as $teacher)
<div class="col-md-4 col-lg-3 mb-3">
    <div class="card teacher-card shadow-sm h-100">
        <div class="card-body text-center py-4">
            <img src="{{ $teacher->photo }}" class="teacher-avatar mb-3 border" alt="photo">
            <h6 class="font-weight-bold mb-1">{{ $teacher->name }}</h6>
            <small class="text-muted d-block mb-1">{{ $teacher->email }}</small>
            @if(isset($teacher->child_class))
            <span class="badge badge-light border mb-3">
                <i class="icon-graduation2 mr-1"></i>{{ $teacher->child_class }}
            </span>
            @endif
            <br>
            <a href="{{ route('parent.chat', $teacher->id) }}"
               class="btn btn-primary btn-sm px-4" style="border-radius:10px;">
                <i class="icon-bubbles3 mr-1"></i> Open Chat
            </a>
        </div>
    </div>
</div>
@endforeach
</div>
@endif
@endsection
