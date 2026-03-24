@extends('layouts.master')
@section('page_title', 'Receipts')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="font-weight-bold mb-0"><i class="icon-file-pdf mr-2 text-danger"></i>Receipts</h5>
        <small class="text-muted">Download payment receipts for any student</small>
    </div>
</div>

{{-- Search –}}
<div class="card border-0 shadow-sm mb-3" style="border-radius:12px;">
    <div class="card-body py-2">
        <form method="GET" class="form-inline">
            <input type="text" name="student_name" class="form-control form-control-sm mr-2"
                   placeholder="Search student name..." value="{{ request('student_name') }}" style="border-radius:8px;width:260px;">
            <button type="submit" class="btn btn-primary btn-sm mr-1" style="border-radius:8px;"><i class="icon-search4"></i> Search</button>
            <a href="{{ route('accountant.receipts') }}" class="btn btn-light btn-sm" style="border-radius:8px;">Clear</a>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius:14px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr><th>#</th><th>Student</th><th>Payment Fee</th><th>Amount Paid</th><th>Balance</th><th>Date</th><th class="text-center">Receipt</th></tr>
                </thead>
                <tbody>
                @forelse($records as $i => $r)
                <tr>
                    <td class="text-muted">{{ $records->firstItem() + $i }}</td>
                    <td>
                        @if($r->student)
                        <div class="d-flex align-items-center">
                            <img src="{{ $r->student->photo }}" width="32" height="32" class="rounded-circle mr-2 border">
                            <div>
                                <div class="font-weight-semibold">{{ $r->student->name }}</div>
                                <small class="text-muted">{{ $r->student->email }}</small>
                            </div>
                        </div>
                        @else — @endif
                    </td>
                    <td class="font-weight-semibold">{{ $r->payment->title ?? '—' }}</td>
                    <td><span class="font-weight-bold text-success">₹{{ number_format($r->amt_paid) }}</span></td>
                    <td>
                        @if($r->balance > 0)
                            <span class="text-danger">₹{{ number_format($r->balance) }}</span>
                        @else
                            <span class="badge badge-success">Paid</span>
                        @endif
                    </td>
                    <td><small class="text-muted">{{ \Carbon\Carbon::parse($r->created_at)->format('d M Y') }}</small></td>
                    <td class="text-center">
                        @if($r->student)
                        <a href="{{ route('payments.pdf_receipts', $r->student->id) }}" target="_blank"
                           class="btn btn-danger btn-sm px-3" style="border-radius:8px;">
                            <i class="icon-file-pdf mr-1"></i>PDF
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No receipts found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($records->hasPages())
        <div class="p-3 border-top">{{ $records->appends(request()->query())->links() }}</div>
        @endif
    </div>
</div>
@endsection
