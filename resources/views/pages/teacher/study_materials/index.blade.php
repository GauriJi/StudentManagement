@extends('layouts.master')
@section('page_title', 'My Study Materials')
@section('content')

<style>
    .glass-card { background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(0,0,0,0.05); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .nav-tabs-highlight .nav-link.active { border-bottom-color: #00b09b; color: #00b09b; font-weight: bold; }
    .material-card { border-radius: 10px; border: 1px solid #f1f5f9; transition: all 0.2s; border-left: 4px solid #00b09b; }
    .material-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); }
    .badge-soft-success { background-color: #dcfce7; color: #16a34a; padding: 5px 10px; border-radius: 20px;}
    .badge-soft-dark { background-color: #f1f5f9; color: #475569; padding: 5px 10px; border-radius: 20px;}
    .btn-action { color: #475569; background: #f8fafc; border-radius: 6px; padding: 6px 12px; transition: all 0.2s; font-weight: bold; font-size: 0.85rem;}
    .btn-action:hover { background: #00b09b; color: white; }
    .btn-delete:hover { background: #ef4444; color: white; }
    .btn-submit { background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%); color: white; border-radius: 30px; padding: 10px 30px; border: none;}
    .btn-submit:hover { color: white; box-shadow: 0 5px 15px rgba(0, 176, 155, 0.4); }
</style>

<div class="card glass-card">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h5 class="mb-0 font-weight-bold" style="color: #00b09b;"><i class="icon-file-pdf mr-2"></i> My Study Materials</h5>
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight nav-justified mb-4">
            <li class="nav-item"><a href="#all-materials" class="nav-link active" data-toggle="tab"><i class="icon-stack mr-2"></i> Materials List</a></li>
            <li class="nav-item"><a href="#new-material" class="nav-link" data-toggle="tab"><i class="icon-file-plus mr-2"></i> Upload Material</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="all-materials">
                <div class="row">
                    @forelse($materials as $m)
                    <div class="col-md-6 mb-3">
                        <div class="card material-card h-100 p-3 mb-0">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="font-weight-bold text-dark mb-0"><i class="icon-file-pdf mr-2 text-danger"></i>{{ $m->title }}</h6>
                                <span class="badge badge-soft-success">{{ $m->my_class ? $m->my_class->name : 'All Classes' }}</span>
                            </div>
                            <p class="text-muted font-size-sm mb-2"><i class="icon-bookmark mr-1"></i> Subject: {{ $m->subject ? $m->subject->name : 'All Subjects' }}</p>
                            
                            <p class="mb-3"><span class="badge badge-soft-dark"><i class="icon-calendar3 mr-1"></i> Uploaded: {{ $m->created_at->format('M d, Y') }}</span></p>

                            <div class="mt-auto d-flex justify-content-between align-items-center border-top pt-3">
                                <a href="{{ route('study_materials.download', $m->id) }}" class="btn btn-sm btn-action"><i class="icon-download mr-1"></i> Download File</a>
                                <div>
                                    <a id="{{ $m->id }}" onclick="confirmDelete(this.id)" href="#" class="btn btn-sm btn-action btn-delete"><i class="icon-trash mr-1"></i> Delete</a>
                                    <form method="post" id="item-delete-{{ $m->id }}" action="{{ route('study_materials.destroy', $m->id) }}" class="hidden">@csrf @method('delete')</form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <i class="icon-file-empty text-light" style="font-size: 3rem;"></i>
                        <h5 class="mt-2 text-muted">No Study Materials Found</h5>
                        <p>Upload new material for your students using the tab above.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="tab-pane fade" id="new-material">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card border-0 shadow-none mb-0 p-3" style="background: #f8fafc; border-radius: 12px;">
                            <form method="post" action="{{ route('study_materials.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Title <span class="text-danger">*</span></label>
                                    <input name="title" value="{{ old('title') }}" required type="text" class="form-control" placeholder="Material Title">
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold text-dark">Class (Optional)</label>
                                        <select data-placeholder="Select Class" class="form-control select" name="my_class_id" id="my_class_id">
                                            <option value=""></option>
                                            @foreach($my_classes as $c)
                                                <option {{ old('my_class_id') == $c->id ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold text-dark">Subject (Optional)</label>
                                        <select data-placeholder="Select Subject" class="form-control select" name="subject_id" id="subject_id">
                                            <option value=""></option>
                                            @foreach($subjects as $s)
                                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Description</label>
                                    <textarea name="description" class="form-control" placeholder="Optional details..." rows="3"></textarea>
                                </div>

                                <div class="form-group border border-dashed p-3 rounded text-center" style="background: white; border-color: #00b09b;">
                                    <label class="font-weight-bold text-dark d-block">Upload File <span class="text-danger">*</span></label>
                                    <input name="file" required type="file" class="file-input" data-show-caption="false" data-show-upload="false" data-fouc>
                                    <span class="form-text text-muted mt-2">Accepted formats: pdf, jpeg, png, jpg. Max size: 5MB</span>
                                </div>

                                <div class="text-right mt-4">
                                    <button type="submit" class="btn btn-submit"><i class="icon-paperplane mr-2"></i> Upload Material</button>
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
