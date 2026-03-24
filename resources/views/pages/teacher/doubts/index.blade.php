@extends('layouts.master')
@section('page_title', 'Student Doubts')
@section('content')

<style>
    .glass-card { background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(0,0,0,0.05); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .table-modern th { background-color: #f8fafc; font-weight: bold; text-transform: uppercase; font-size: 0.85rem; color: #475569; border-bottom: 2px solid #e2e8f0; }
    .table-modern td { vertical-align: middle; border-bottom: 1px solid #f1f5f9; padding: 15px 12px; }
    .badge-soft-success { background-color: #dcfce7; color: #16a34a; padding: 6px 12px; border-radius: 20px;}
    .badge-soft-warning { background-color: #fef08a; color: #ca8a04; padding: 6px 12px; border-radius: 20px;}
    .badge-soft-info { background-color: #e0f2fe; color: #0ea5e9; padding: 6px 12px; border-radius: 20px;}
    .st-photo-small { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0; margin-right: 10px; }
    .btn-action { color: white; border-radius: 6px; padding: 6px 12px; transition: all 0.2s; font-weight: bold; font-size: 0.85rem; border: none; }
    .btn-primary-gradient { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); }
    .btn-primary-gradient:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(30,60,114,0.3); color: white; }
    .btn-success-gradient { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .btn-success-gradient:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(17,153,142,0.3); color: white; }
</style>

<div class="card glass-card">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 font-weight-bold text-primary"><i class="icon-bubbles4 mr-2"></i> My Students' Doubts</h5>
        <span class="badge badge-soft-warning font-size-base">{{ $doubts->where('is_resolved', false)->count() }} Pending</span>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-modern table-hover datatable-button-html5-columns">
                <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="25%">Student</th>
                    <th width="20%">Subject</th>
                    <th width="20%">Title</th>
                    <th width="10%">Status</th>
                    <th width="10%">Date</th>
                    <th width="10%" class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($doubts as $d)
                    <tr>
                        <td><span class="text-muted font-weight-bold">{{ $loop->iteration }}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $d->student->photo }}" class="st-photo-small" alt="photo">
                                <div>
                                    <h6 class="mb-0 font-weight-bold text-dark">{{ $d->student->name }}</h6>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge badge-soft-info">{{ $d->subject->name }}</span></td>
                        <td><span class="font-weight-bold text-dark">{{ $d->title }}</span></td>
                        <td>
                            @if($d->is_resolved)
                                <span class="badge badge-soft-success"><i class="icon-checkmark-circle mr-1"></i> Resolved</span>
                            @else
                                <span class="badge badge-soft-warning"><i class="icon-spinner2 spinner mr-1"></i> Pending</span>
                            @endif
                        </td>
                        <td><span class="text-muted">{{ $d->created_at->format('M d, Y') }}</span></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('doubts.show', $d->id) }}" class="btn btn-sm btn-action btn-primary-gradient mr-1" title="View/Reply"><i class="icon-eye"></i></a>
                                @if(!$d->is_resolved)
                                    <form method="post" action="{{ route('doubts.resolve', $d->id) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-action btn-success-gradient" title="Mark as Resolved"><i class="icon-checkmark3"></i></button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
