@extends('layouts.master')
@section('page_title', 'My Timetables')

@section('content')
<style>
    .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(0,0,0,0.05); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .nav-tabs-highlight .nav-link.active { border-bottom-color: #1e3c72; color: #1e3c72; font-weight: bold; }
    .table-modern th { background-color: #f8fafc; font-weight: bold; text-transform: uppercase; font-size: 0.85rem; color: #475569; border-bottom: 2px solid #e2e8f0; }
    .table-modern td { vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
    .badge-soft-primary { background-color: #e0e7ff; color: #3b82f6; padding: 6px 12px; border-radius: 20px;}
    .badge-soft-success { background-color: #dcfce7; color: #22c55e; padding: 6px 12px; border-radius: 20px;}
    .btn-view { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; border: none; border-radius: 6px; transition: transform 0.2s; }
    .btn-view:hover { transform: translateY(-2px); color: white; box-shadow: 0 4px 10px rgba(30,60,114,0.3); }
</style>

<div class="card glass-card">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h5 class="mb-0 font-weight-bold text-primary"><i class="icon-calendar3 mr-2"></i> Class Timetables</h5>
    </div>

    <div class="card-body">
        @if($my_classes->count() > 0)
        <ul class="nav nav-tabs nav-tabs-highlight nav-justified mb-4">
            @foreach($my_classes as $mc)
                <li class="nav-item">
                    <a href="#ttr{{ $mc->id }}" class="nav-link {{ $loop->first ? 'active' : '' }}" data-toggle="tab">
                        <i class="icon-graduation2 mr-2"></i> {{ $mc->name }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content">
            @foreach($my_classes as $mc)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="ttr{{ $mc->id }}">
                    <div class="table-responsive">
                        <table class="table table-modern table-hover">
                            <thead>
                            <tr>
                                <th width="10%">#</th>
                                <th width="35%">Timetable Name</th>
                                <th width="20%">Type</th>
                                <th width="20%">Academic Year</th>
                                <th width="15%" class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($tt_records->where('my_class_id', $mc->id) as $ttr)
                                <tr>
                                    <td><span class="text-muted font-weight-bold">{{ $loop->iteration }}</span></td>
                                    <td><span class="font-weight-bold text-dark">{{ $ttr->name }}</span></td>
                                    <td>
                                        @if($ttr->exam_id)
                                            <span class="badge badge-soft-success">{{ $ttr->exam->name }}</span>
                                        @else
                                            <span class="badge badge-soft-primary">Class TimeTable</span>
                                        @endif
                                    </td>
                                    <td><span class="text-muted"><i class="icon-calendar mr-1"></i>{{ $ttr->year }}</span></td>
                                    <td class="text-center">
                                        <a href="{{ route('ttr.show', $ttr->id) }}" class="btn btn-sm btn-view"><i class="icon-eye mr-1"></i> View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="icon-folder-open text-light" style="font-size: 3rem;"></i>
                                        <h5 class="mt-2">No Timetables Found</h5>
                                        <p>There are no timetables available for this class.</p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
        @else
            <div class="alert bg-light border-0 d-flex align-items-center">
                <i class="icon-info22 text-primary mr-3" style="font-size: 2rem;"></i>
                <div>
                    <h5 class="mb-1 font-weight-bold">No Classes Assigned</h5>
                    <p class="mb-0 text-muted">You have not been assigned any classes yet. Timetables will appear here once assigned.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
