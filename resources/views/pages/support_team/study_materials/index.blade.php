@extends('layouts.master')
@section('page_title', 'Study Materials')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Study Materials</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-materials" class="nav-link active" data-toggle="tab">Study Materials List</a></li>
                @if(Qs::userIsTeamSA() || Qs::userIsTeacher())
                    <li class="nav-item"><a href="#new-material" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Upload Material</a></li>
                @endif
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-materials">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Title</th>
                            <th>Class (Optional)</th>
                            <th>Subject (Optional)</th>
                            <th>Uploaded By</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($materials as $m)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $m->title }}</td>
                                <td>{{ $m->my_class ? $m->my_class->name : 'All Classes' }}</td>
                                <td>{{ $m->subject ? $m->subject->name : '-' }}</td>
                                <td>{{ $m->teacher->name }}</td>
                                <td>{{ $m->created_at->format('Y-m-d') }}</td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-left">
                                                <a href="{{ route('study_materials.download', $m->id) }}" class="dropdown-item"><i class="icon-download"></i> Download</a>

                                                @if(Qs::userIsSuperAdmin() || Auth::user()->id == $m->teacher_id)
                                                    <a id="{{ $m->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                    <form method="post" id="item-delete-{{ $m->id }}" action="{{ route('study_materials.destroy', $m->id) }}" class="hidden">@csrf @method('delete')</form>
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
                    <div class="tab-pane fade" id="new-material">
                        <div class="row">
                            <div class="col-md-6">
                                <form method="post" action="{{ route('study_materials.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label font-weight-semibold">Title <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input name="title" value="{{ old('title') }}" required type="text" class="form-control" placeholder="Material Title">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label font-weight-semibold">Description</label>
                                        <div class="col-lg-9">
                                            <textarea name="description" class="form-control" placeholder="Optional details..."></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="my_class_id" class="col-lg-3 col-form-label font-weight-semibold">Target Class</label>
                                        <div class="col-lg-9">
                                            <select data-placeholder="Select Class (Leave blank for all)" class="form-control select" name="my_class_id" id="my_class_id">
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
                                        <label class="col-lg-3 col-form-label font-weight-semibold">File <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input name="file" required type="file" class="file-input" data-show-caption="false" data-show-upload="false" data-fouc>
                                            <span class="form-text text-muted">Accepted formats: pdf, jpeg, png, jpg. Max file size 5Mb</span>
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
