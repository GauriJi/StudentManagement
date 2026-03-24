@extends('layouts.master')
@section('page_title', 'Fee Collection')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="font-weight-bold mb-0"><i class="icon-wallet mr-2 text-success"></i>Fee Collection</h5>
        <small class="text-muted">Overview of all fee categories and collection status</small>
    </div>
    <div>
        <span class="badge badge-success px-3 py-2" style="font-size:13px;border-radius:8px;">
            Total Collected: ₹{{ number_format($grand_collected) }}
        </span>
    </div>
</div>

{{-- Summary --}}
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:14px;background:linear-gradient(135deg,#27ae60,#2ecc71);color:#fff;">
            <h3 class="font-weight-bold mb-0">₹{{ number_format($grand_collected) }}</h3>
            <small class="opacity-75">Grand Total Collected</small>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:14px;background:linear-gradient(135deg,#e74c3c,#c0392b);color:#fff;">
            <h3 class="font-weight-bold mb-0">₹{{ number_format($grand_balance) }}</h3>
            <small class="opacity-75">Grand Total Balance Due</small>
        </div>
    </div>
</div>

{{-- Fee Categories Table --}}
<div class="card border-0 shadow-sm" style="border-radius:14px;">
    <div class="card-header bg-white border-0">
        <h6 class="font-weight-bold mb-0">All Fee Categories</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th><th>Title</th><th>Class</th><th>Year</th>
                        <th>Fee Amount</th><th>Paid Count</th><th>Collected</th><th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($payments as $i => $p)
                <tr>
                    <td class="text-muted">{{ $i+1 }}</td>
                    <td class="font-weight-semibold">{{ $p->title }}</td>
                    <td>{{ $p->my_class->name ?? '—' }}</td>
                    <td>{{ $p->year }}</td>
                    <td>₹{{ number_format($p->amount) }}</td>
                    <td><span class="badge badge-info">{{ $p->paid_count }}</span></td>
                    <td><span class="font-weight-bold text-success">₹{{ number_format($p->total_collected) }}</span></td>
                    <td>
                        @if($p->total_balance > 0)
                            <span class="text-danger font-weight-bold">₹{{ number_format($p->total_balance) }}</span>
                        @else
                            <span class="badge badge-success">Cleared</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No fee categories found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
