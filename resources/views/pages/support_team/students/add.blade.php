@extends('layouts.master')
@section('page_title', 'Admit Student')
@section('content')

<div class="card">
    <div class="card-header bg-white header-elements-inline">
        <h6 class="card-title">
            <i class="icon-user-plus mr-2 text-primary"></i>Admit New Student
        </h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <form id="ajax-reg" method="POST" enctype="multipart/form-data"
          class="wizard-form steps-validation"
          action="{{ route('students.store') }}" data-fouc>
        @csrf

        @include('pages.support_team.students._form', [
            'isEdit' => false,
            'sr'     => null,
        ])

    </form>
</div>

@endsection
