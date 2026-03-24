@extends('layouts.master')
@section('page_title', 'Fee Payments')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="font-weight-bold mb-0"><i class="icon-wallet mr-2 text-success"></i>Fee Payments</h5>
        <small class="text-muted">Payment history and receipts for your children</small>
    </div>
    <div>
        <span class="badge badge-success px-3 py-2" style="font-size:13px;border-radius:8px;">
            Total Paid: ₹{{ number_format($total_paid) }}
        </span>
    </div>
</div>

@if(session('flash_success'))
    <div class="alert alert-success alert-dismissible border-0"><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>{{ session('flash_success') }}</div>
@endif

{{-- Summary Cards --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:12px;background:linear-gradient(135deg,#27ae60,#2ecc71);color:white;">
            <h3 class="font-weight-bold mb-0">₹{{ number_format($total_paid) }}</h3>
            <small class="opacity-75">Total Amount Paid</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:12px;background:linear-gradient(135deg,#e74c3c,#c0392b);color:white;">
            <h3 class="font-weight-bold mb-0">₹{{ number_format($total_balance) }}</h3>
            <small class="opacity-75">Outstanding Balance</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:12px;background:linear-gradient(135deg,#2980b9,#3498db);color:white;">
            <h3 class="font-weight-bold mb-0">{{ $records->count() }}</h3>
            <small class="opacity-75">Total Transactions</small>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius:14px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Child</th>
                        <th>Payment</th>
                        <th>Year</th>
                        <th>Amount Paid</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th class="text-center">Receipt</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($records as $i => $r)
                <tr>
                    <td class="text-muted">{{ $i+1 }}</td>
                    <td>
                        @if($r->student)
                        <div class="d-flex align-items-center">
                            <img src="{{ $r->student->photo }}" width="30" height="30" class="rounded-circle mr-2 border">
                            <span class="font-weight-semibold">{{ $r->student->name }}</span>
                        </div>
                        @else <span class="text-muted">—</span> @endif
                    </td>
                    <td class="font-weight-semibold">{{ $r->payment->title ?? '—' }}</td>
                    <td>{{ $r->year }}</td>
                    <td><span class="text-success font-weight-bold">₹{{ number_format($r->amt_paid) }}</span></td>
                    <td>
                        @if($r->balance > 0)
                            <span class="text-danger font-weight-bold">₹{{ number_format($r->balance) }}</span>
                        @else
                            <span class="text-success"><i class="icon-checkmark3"></i> Cleared</span>
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
                           class="btn btn-sm btn-outline-danger" style="border-radius:8px;" title="Download PDF Receipt">
                            <i class="icon-file-pdf"></i> PDF
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-5">No fee payment records found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
