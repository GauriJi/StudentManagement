@extends('layouts.master')
@section('page_title', 'Fee Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="font-weight-bold mb-0"><i class="icon-wallet mr-2 text-success"></i>Fee Management</h5>
        <small class="text-muted">View and track all fee payments by class and year</small>
    </div>
    <div>
        <span class="badge badge-success px-3 py-2" style="font-size:14px;border-radius:8px;">
            Total Collected: ₹{{ number_format($grand_total) }}
        </span>
    </div>
</div>

@if(session('flash_success'))
    <div class="alert alert-success alert-dismissible border-0"><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>{{ session('flash_success') }}</div>
@endif

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-3" style="border-radius:12px;">
    <div class="card-body py-2">
        <form method="GET" class="form-inline">
            <div class="mr-2 mb-1">
                <select name="class_id" class="form-control form-control-sm" style="border-radius:8px;">
                    <option value="">All Classes</option>
                    @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ $selected_class == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mr-2 mb-1">
                <select name="year" class="form-control form-control-sm" style="border-radius:8px;">
                    @foreach($years as $y)
                    <option value="{{ $y }}" {{ $selected_year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-sm btn-primary mr-1" style="border-radius:8px;"><i class="icon-search4"></i> Filter</button>
            <a href="{{ route('sa.fees.index') }}" class="btn btn-sm btn-light" style="border-radius:8px;">Clear</a>
        </form>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row mb-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:12px;background:linear-gradient(135deg,#27ae60,#2ecc71);color:white;">
            <h3 class="font-weight-bold mb-0">₹{{ number_format($total_collected) }}</h3>
            <small class="opacity-75">Filtered Period Collections</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:12px;background:linear-gradient(135deg,#2980b9,#3498db);color:white;">
            <h3 class="font-weight-bold mb-0">{{ $payments->count() }}</h3>
            <small class="opacity-75">Payment Categories</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:12px;background:linear-gradient(135deg,#8e44ad,#9b59b6);color:white;">
            <h3 class="font-weight-bold mb-0">{{ $payments->sum('paid_count') }}</h3>
            <small class="opacity-75">Total Payments Recorded</small>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius:14px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th><th>Payment Title</th><th>Class</th><th>Year</th>
                        <th>Fee Amount</th><th>Paid Count</th><th>Total Collected</th>
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
                    <td>
                        <span class="badge badge-info">{{ $p->paid_count }} paid</span>
                    </td>
                    <td><span class="font-weight-bold text-success">₹{{ number_format($p->total_collected) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No payment records found for the selected filters.</td></tr>
                @endforelse
                </tbody>
                @if($payments->count())
                <tfoot class="thead-light">
                    <tr>
                        <td colspan="6" class="text-right font-weight-bold">Total Collected (filtered):</td>
                        <td class="font-weight-bold text-success">₹{{ number_format($total_collected) }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
