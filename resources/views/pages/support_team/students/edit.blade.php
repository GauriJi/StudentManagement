@extends('layouts.master')
@section('page_title', 'Edit Student')
@section('content')

<div class="card">
    <div class="card-header bg-white header-elements-inline">
        <h6 id="ajax-title" class="card-title">
            <i class="icon-pencil7 mr-2 text-warning"></i>Edit Student — {{ $sr->user->name }}
        </h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <form method="POST" enctype="multipart/form-data"
          class="wizard-form steps-validation ajax-update"
          data-reload="#ajax-title"
          action="{{ route('students.update', Qs::hash($sr->id)) }}" data-fouc>
        @csrf @method('PUT')

        @include('pages.support_team.students._form', [
            'isEdit' => true,
            'sr'     => $sr,
        ])

    </form>
</div>

@endsection
