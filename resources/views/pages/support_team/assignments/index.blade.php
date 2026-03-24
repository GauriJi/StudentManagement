@extends('layouts.master')
@section('page_title', 'Assignments')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Assignments</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-assignments" class="nav-link active" data-toggle="tab">Assignments List</a></li>
                @if(Qs::userIsTeamSA() || Qs::userIsTeacher())
                    <li class="nav-item"><a href="#new-assignment" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Upload Assignment</a></li>
                @endif
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-assignments">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Title</th>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Due Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($assignments as $a)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $a->title }}</td>
                                <td>{{ $a->my_class->name }}</td>
                                <td>{{ $a->subject ? $a->subject->name : '-' }}</td>
                                <td>{{ $a->teacher->name }}</td>
                                <td>{{ $a->due_date ? date('Y-m-d', strtotime($a->due_date)) : '' }}</td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-left">
                                                <a href="{{ route('assignments.download', $a->id) }}" class="dropdown-item"><i class="icon-download"></i> Download</a>

                                                @if(Qs::userIsSuperAdmin() || Auth::user()->id == $a->teacher_id)
                                                    <a id="{{ $a->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                    <form method="post" id="item-delete-{{ $a->id }}" action="{{ route('assignments.destroy', $a->id) }}" class="hidden">@csrf @method('delete')</form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if(Qs::userIsTeamSA() || Qs::userIsTeacher())
                    <div class="tab-pane fade" id="new-assignment">
                        <div class="row">
                            <div class="col-md-6">
                                <form method="post" action="{{ route('assignments.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label font-weight-semibold">Title <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input name="title" value="{{ old('title') }}" required type="text" class="form-control" placeholder="Assignment Title">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label font-weight-semibold">Description</label>
                                        <div class="col-lg-9">
                                            <textarea name="description" class="form-control" placeholder="Optional details..."></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="my_class_id" class="col-lg-3 col-form-label font-weight-semibold">Class <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <select required data-placeholder="Select Class" class="form-control select" name="my_class_id" id="my_class_id">
                                                <option value=""></option>
                                                @foreach($my_classes as $c)
                                                    <option {{ old('my_class_id') == $c->id ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="subject_id" class="col-lg-3 col-form-label font-weight-semibold">Subject</label>
                                        <div class="col-lg-9">
                                            <select data-placeholder="Select Subject" class="form-control select" name="subject_id" id="subject_id">
                                                <option value=""></option>
                                                @foreach($subjects as $s)
                                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label font-weight-semibold">Due Date</label>
                                        <div class="col-lg-9">
                                            <input name="due_date" type="date" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label font-weight-semibold">File <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input name="file" required type="file" class="file-input" data-show-caption="false" data-show-upload="false" data-fouc>
                                            <span class="form-text text-muted">Accepted formats: pdf, doc, docx, jpeg, png, jpg. Max file size 5Mb</span>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary">Submit <i class="icon-paperplane ml-2"></i></button>
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
