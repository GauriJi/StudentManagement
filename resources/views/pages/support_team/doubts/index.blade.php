@extends('layouts.master')
@section('page_title', 'Doubt System')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">My Doubts</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-doubts" class="nav-link active" data-toggle="tab">Doubt Threads</a></li>
                @if(Qs::userIsStudent() || Qs::userIsParent())
                    <li class="nav-item"><a href="#new-doubt" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Ask a Doubt</a></li>
                @endif
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-doubts">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Title</th>
                            <th>Subject</th>
                            @if(Qs::userIsTeacher() || Qs::userIsTeamSA())
                                <th>Asked By</th>
                            @endif
                            @if(Qs::userIsStudent() || Qs::userIsParent() || Qs::userIsTeamSA())
                                <th>Teacher</th>
                            @endif
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($doubts as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $d->title }}</td>
                                <td>{{ $d->subject->name }}</td>
                                @if(Qs::userIsTeacher() || Qs::userIsTeamSA())
                                    <td>{{ $d->student->name }}</td>
                                @endif
                                @if(Qs::userIsStudent() || Qs::userIsParent() || Qs::userIsTeamSA())
                                    <td>{{ $d->teacher->name }}</td>
                                @endif
                                <td>
                                    @if($d->is_resolved)
                                        <span class="badge badge-success">Resolved</span>
                                    @else
                                        <span class="badge badge-warning">Open</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('doubts.show', $d->id) }}" class="btn btn-sm btn-info"><i class="icon-eye"></i> View Discussion</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if(Qs::userIsStudent() || Qs::userIsParent())
                    <div class="tab-pane fade" id="new-doubt">
                        <div class="row">
                            <div class="col-md-6">
                                <form method="post" action="{{ route('doubts.store') }}">
                                    @csrf
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label font-weight-semibold">Subject <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <select required data-placeholder="Select Subject" class="form-control select" name="subject_id" id="subject_id">
                                                <option value=""></option>
                                                @foreach($subjects as $s)
                                                    <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->teacher ? $s->teacher->name : 'No Teacher' }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label font-weight-semibold">Title <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input name="title" value="{{ old('title') }}" required type="text" class="form-control" placeholder="Brief subject of your doubt">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label font-weight-semibold">Message <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <textarea name="message" class="form-control" required placeholder="Describe your doubt..."></textarea>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary">Submit Doubt <i class="icon-paperplane ml-2"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
