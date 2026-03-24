@extends('layouts.master')
@section('page_title', 'My Assignments')
@section('content')

<style>
    .glass-card { background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(0,0,0,0.05); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .nav-tabs-highlight .nav-link.active { border-bottom-color: #2a5298; color: #2a5298; font-weight: bold; }
    .assignment-card { border-radius: 10px; border: 1px solid #f1f5f9; transition: all 0.2s; border-left: 4px solid #1e3c72; }
    .assignment-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); }
    .badge-soft-danger { background-color: #fee2e2; color: #ef4444; padding: 5px 10px; border-radius: 20px;}
    .badge-soft-info { background-color: #e0f2fe; color: #0ea5e9; padding: 5px 10px; border-radius: 20px;}
    .btn-action { color: #475569; background: #f8fafc; border-radius: 6px; padding: 6px 12px; transition: all 0.2s; font-weight: bold; font-size: 0.85rem;}
    .btn-action:hover { background: #2a5298; color: white; }
    .btn-delete:hover { background: #ef4444; color: white; }
</style>

<div class="card glass-card">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h5 class="mb-0 font-weight-bold text-primary"><i class="icon-book2 mr-2"></i> My Assignments</h5>
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight nav-justified mb-4">
            <li class="nav-item"><a href="#all-assignments" class="nav-link active" data-toggle="tab"><i class="icon-list3 mr-2"></i> Assignments List</a></li>
            <li class="nav-item"><a href="#new-assignment" class="nav-link" data-toggle="tab"><i class="icon-plus-circle2 mr-2"></i> Upload Assignment</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="all-assignments">
                <div class="row">
                    @forelse($assignments as $a)
                    <div class="col-md-6 mb-3">
                        <div class="card assignment-card h-100 p-3 mb-0">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="font-weight-bold text-dark mb-0"><i class="icon-file-text2 mr-2 text-muted"></i>{{ $a->title }}</h6>
                                <span class="badge badge-soft-info">{{ $a->my_class->name }}</span>
                            </div>
                            <p class="text-muted font-size-sm mb-2"><i class="icon-books mr-1"></i> Subject: {{ $a->subject ? $a->subject->name : 'General' }}</p>
                            
                            @if($a->due_date)
                            <p class="mb-3"><span class="badge badge-soft-danger"><i class="icon-alarm mr-1"></i> Due: {{ date('F j, Y', strtotime($a->due_date)) }}</span></p>
                            @endif

                            <div class="mt-auto d-flex justify-content-between align-items-center border-top pt-3">
                                <a href="{{ route('assignments.download', $a->id) }}" class="btn btn-sm btn-action"><i class="icon-download mr-1"></i> Download</a>
                                <div>
                                    <a id="{{ $a->id }}" onclick="confirmDelete(this.id)" href="#" class="btn btn-sm btn-action btn-delete"><i class="icon-trash mr-1"></i> Delete</a>
                                    <form method="post" id="item-delete-{{ $a->id }}" action="{{ route('assignments.destroy', $a->id) }}" class="hidden">@csrf @method('delete')</form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <i class="icon-folder-open text-light" style="font-size: 3rem;"></i>
                        <h5 class="mt-2 text-muted">No Assignments Found</h5>
                        <p>Upload an assignment using the tab above.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="tab-pane fade" id="new-assignment">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card border-0 shadow-none mb-0 p-3" style="background: #f8fafc; border-radius: 12px;">
                            <form method="post" action="{{ route('assignments.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Title <span class="text-danger">*</span></label>
                                    <input name="title" value="{{ old('title') }}" required type="text" class="form-control" placeholder="Assignment Title">
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold text-dark">Class <span class="text-danger">*</span></label>
                                        <select required data-placeholder="Select Class" class="form-control select" name="my_class_id" id="my_class_id">
                                            <option value=""></option>
                                            @foreach($my_classes as $c)
                                                <option {{ old('my_class_id') == $c->id ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold text-dark">Subject</label>
                                        <select data-placeholder="Select Subject" class="form-control select" name="subject_id" id="subject_id">
                                            <option value=""></option>
                                            @foreach($subjects as $s)
                                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Due Date</label>
                                    <input name="due_date" type="date" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Description</label>
                                    <textarea name="description" class="form-control" placeholder="Optional instructions or details..." rows="3"></textarea>
                                </div>

                                <div class="form-group border border-dashed border-primary p-3 rounded text-center" style="background: white;">
                                    <label class="font-weight-bold text-dark d-block">Upload File <span class="text-danger">*</span></label>
                                    <input name="file" required type="file" class="file-input" data-show-caption="false" data-show-upload="false" data-fouc>
                                    <span class="form-text text-muted mt-2">Accepted formats: pdf, doc, docx, jpeg, png, jpg. Max size: 5MB</span>
                                </div>

                                <div class="text-right mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg" style="border-radius: 30px; padding: 10px 30px;"><i class="icon-paperplane mr-2"></i> Create Assignment</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
