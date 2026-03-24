@extends('layouts.master')
@section('page_title', 'Student Fees')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="font-weight-bold mb-0"><i class="icon-users4 mr-2 text-primary"></i>Student Fees</h5>
        <small class="text-muted">All student payment records</small>
    </div>
</div>

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-3" style="border-radius:12px;">
    <div class="card-body py-2">
        <form method="GET" class="form-inline flex-wrap">
            <div class="mr-2 mb-1">
                <select name="class_id" class="form-control form-control-sm" style="border-radius:8px;">
                    <option value="">All Classes</option>
                    @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ $selected_class == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mr-2 mb-1">
                <input type="text" name="student_name" class="form-control form-control-sm" placeholder="Student name..." value="{{ request('student_name') }}" style="border-radius:8px;">
            </div>
            <button type="submit" class="btn btn-sm btn-primary mr-1 mb-1" style="border-radius:8px;"><i class="icon-search4"></i> Search</button>
            <a href="{{ route('accountant.student_fees') }}" class="btn btn-sm btn-light mb-1" style="border-radius:8px;">Clear</a>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius:14px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr><th>#</th><th>Student</th><th>Payment</th><th>Class</th><th>Year</th><th>Amount Paid</th><th>Balance</th><th>Status</th><th class="text-center">Receipt</th></tr>
                </thead>
                <tbody>
                @forelse($records as $i => $r)
                <tr>
                    <td class="text-muted">{{ $records->firstItem() + $i }}</td>
                    <td>
                        @if($r->student)
                        <div class="d-flex align-items-center">
                            <img src="{{ $r->student->photo }}" width="30" height="30" class="rounded-circle mr-2 border">
                            <span class="font-weight-semibold">{{ $r->student->name }}</span>
                        </div>
                        @else — @endif
                    </td>
                    <td>{{ $r->payment->title ?? '—' }}</td>
                    <td>{{ $r->payment->my_class->name ?? '—' }}</td>
                    <td>{{ $r->year }}</td>
                    <td><span class="font-weight-bold text-success">₹{{ number_format($r->amt_paid) }}</span></td>
                    <td>
                        @if($r->balance > 0)
                            <span class="text-danger font-weight-bold">₹{{ number_format($r->balance) }}</span>
                        @else
                            <span class="badge badge-success"><i class="icon-checkmark3"></i> Cleared</span>
                        @endif
                    </td>
                    <td>
                        @if($r->paid)
                            <span class="badge badge-success">Paid</span>
                        @else
                            <span class="badge badge-warning">Partial</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($r->student)
                        <a href="{{ route('payments.pdf_receipts', $r->student->id) }}" target="_blank"
                           class="btn btn-sm btn-outline-danger" style="border-radius:8px;" title="PDF Receipt">
                            <i class="icon-file-pdf"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-4">No fee records found.</td></tr>
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
