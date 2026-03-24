@extends('layouts.master')
@section('page_title', 'Expenses')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="font-weight-bold mb-0"><i class="icon-shrink5 mr-2 text-danger"></i>Expenses</h5>
        <small class="text-muted">Track and manage all institutional expenses</small>
    </div>
</div>

@if(session('flash_success'))
    <div class="alert alert-success alert-dismissible border-0"><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>{{ session('flash_success') }}</div>
@endif

<div class="row mb-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:12px;background:linear-gradient(135deg,#e74c3c,#c0392b);color:#fff;">
            <h4 class="font-weight-bold mb-0">₹{{ number_format($total_this_month) }}</h4>
            <small class="opacity-75">This Month's Expenses</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:12px;background:linear-gradient(135deg,#8e44ad,#9b59b6);color:#fff;">
            <h4 class="font-weight-bold mb-0">₹{{ number_format($total_this_year) }}</h4>
            <small class="opacity-75">This Year's Total</small>
        </div>
    </div>
    <div class="col-md-4">
        {{-- Add Expense Button --}}
        <button class="btn btn-danger btn-block h-100" style="border-radius:12px;font-weight:600;" data-toggle="modal" data-target="#addExpenseModal">
            <i class="icon-plus3 mr-1"></i> Record New Expense
        </button>
    </div>
</div>

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-3" style="border-radius:12px;">
    <div class="card-body py-2">
        <form method="GET" class="form-inline flex-wrap">
            <div class="mr-2 mb-1">
                <select name="category" class="form-control form-control-sm" style="border-radius:8px;">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mr-2 mb-1">
                <input type="month" name="month" class="form-control form-control-sm" value="{{ request('month') }}" style="border-radius:8px;">
            </div>
            <button type="submit" class="btn btn-sm btn-primary mr-1" style="border-radius:8px;"><i class="icon-search4"></i> Filter</button>
            <a href="{{ route('accountant.expenses') }}" class="btn btn-sm btn-light" style="border-radius:8px;">Clear</a>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius:14px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr><th>#</th><th>Title</th><th>Category</th><th>Date</th><th>Amount</th><th>Ref No.</th><th class="text-center">Action</th></tr>
                </thead>
                <tbody>
                @forelse($expenses as $i => $e)
                <tr>
                    <td class="text-muted">{{ $expenses->firstItem() + $i }}</td>
                    <td class="font-weight-semibold">{{ $e->title }}</td>
                    <td>
                        <span class="badge badge-light border text-capitalize">{{ $e->category }}</span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($e->expense_date)->format('d M Y') }}</td>
                    <td><span class="font-weight-bold text-danger">₹{{ number_format($e->amount) }}</span></td>
                    <td>{{ $e->ref_no ?? '—' }}</td>
                    <td class="text-center">
                        <form action="{{ route('accountant.expenses.destroy', $e->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this expense?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;" title="Delete">
                                <i class="icon-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No expenses recorded yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($expenses->hasPages())
        <div class="p-3 border-top">{{ $expenses->appends(request()->query())->links() }}</div>
        @endif
    </div>
</div>

{{-- Add Expense Modal --}}
<div class="modal fade" id="addExpenseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden;">
            <div class="modal-header border-0" style="background:linear-gradient(135deg,#e74c3c,#c0392b);color:#fff;">
                <h5 class="modal-title font-weight-bold"><i class="icon-shrink5 mr-2"></i>Record New Expense</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('accountant.expenses.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4 py-3">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="font-weight-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}" placeholder="Expense title" required style="border-radius:8px;">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-semibold">Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-control" required style="border-radius:8px;">
                                @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-semibold">Amount (₹) <span class="text-danger">*</span></label>
                            <input type="number" name="amount" step="0.01" class="form-control"
                                   placeholder="0.00" required style="border-radius:8px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-semibold">Date <span class="text-danger">*</span></label>
                            <input type="date" name="expense_date" class="form-control"
                                   value="{{ date('Y-m-d') }}" required style="border-radius:8px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-semibold">Ref No.</label>
                            <input type="text" name="ref_no" class="form-control" placeholder="Reference / Voucher No." style="border-radius:8px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-semibold">Description</label>
                            <textarea name="description" rows="2" class="form-control" placeholder="Optional notes..." style="border-radius:8px;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius:8px;">Cancel</button>
                    <button type="submit" class="btn btn-danger px-4" style="border-radius:8px;"><i class="icon-plus3 mr-1"></i>Save Expense</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
@if($errors->any() || session('open_modal'))
$(document).ready(function(){ $('#addExpenseModal').modal('show'); });
@endif
</script>
@endpush
@endsection
