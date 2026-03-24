@extends('layouts.master')
@section('page_title', 'Accountant Dashboard')
@section('content')
<style>
.ac-hero{background:linear-gradient(135deg,#1a1a2e,#16213e);border-radius:20px;padding:30px;color:#fff;margin-bottom:24px;box-shadow:0 10px 30px rgba(0,0,0,.15);position:relative;overflow:hidden;}
.ac-hero::after{content:'';position:absolute;top:-50px;right:-50px;width:200px;height:200px;background:rgba(255,255,255,.04);border-radius:50%;}
.ac-stat{border-radius:16px;border:none;transition:all .3s;}
.ac-stat:hover{transform:translateY(-4px);box-shadow:0 10px 24px rgba(0,0,0,.12)!important;}
.ac-stat .stat-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;margin-bottom:12px;}
.chip{display:inline-block;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:700;}
</style>

{{-- Hero --}}
<div class="ac-hero mb-4">
    <div class="row align-items-center">
        <div class="col-auto">
            <img src="{{ Auth::user()->photo }}" width="64" height="64" class="rounded-circle border" style="border-color:rgba(255,255,255,.3)!important;">
        </div>
        <div class="col">
            <h3 class="font-weight-bold mb-1">Welcome, {{ Auth::user()->name }}</h3>
            <p class="mb-0 opacity-75">{{ date('l, d F Y') }} &nbsp;|&nbsp; <i class="icon-wallet"></i> Accountant Portal</p>
        </div>
        <div class="col-auto d-none d-md-flex flex-column text-right">
            <span class="badge badge-success px-3 py-2 mb-1" style="font-size:13px;border-radius:8px;">Today: ₹{{ number_format($today_collected) }}</span>
            <span class="badge badge-info px-3 py-2" style="font-size:13px;border-radius:8px;">Month: ₹{{ number_format($month_collected) }}</span>
        </div>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row mb-4">
    <div class="col-6 col-md-3 mb-3">
        <div class="card ac-stat shadow-sm h-100 p-3" style="background:linear-gradient(135deg,#27ae60,#2ecc71);color:#fff;">
            <div class="stat-icon bg-white" style="color:#27ae60;"><i class="icon-wallet"></i></div>
            <h4 class="font-weight-bold mb-0">₹{{ number_format($total_collected) }}</h4>
            <small class="opacity-75 text-uppercase">Total Collected</small>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="card ac-stat shadow-sm h-100 p-3" style="background:linear-gradient(135deg,#e74c3c,#c0392b);color:#fff;">
            <div class="stat-icon bg-white" style="color:#e74c3c;"><i class="icon-coin-dollar"></i></div>
            <h4 class="font-weight-bold mb-0">₹{{ number_format($pending_fees) }}</h4>
            <small class="opacity-75 text-uppercase">Pending Fees</small>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="card ac-stat shadow-sm h-100 p-3" style="background:linear-gradient(135deg,#2980b9,#3498db);color:#fff;">
            <div class="stat-icon bg-white" style="color:#2980b9;"><i class="icon-calendar3"></i></div>
            <h4 class="font-weight-bold mb-0">₹{{ number_format($today_collected) }}</h4>
            <small class="opacity-75 text-uppercase">Today's Collection</small>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="card ac-stat shadow-sm h-100 p-3" style="background:linear-gradient(135deg,#8e44ad,#9b59b6);color:#fff;">
            <div class="stat-icon bg-white" style="color:#8e44ad;"><i class="icon-shrink5"></i></div>
            <h4 class="font-weight-bold mb-0">₹{{ number_format($total_expenses) }}</h4>
            <small class="opacity-75 text-uppercase">This Year Expenses</small>
        </div>
    </div>
</div>

<div class="row">
    {{-- Recent Transactions --}}
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:16px;">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold mb-0"><i class="icon-list-unordered mr-2 text-success"></i>Recent Transactions</h6>
                <a href="{{ route('accountant.fees') }}" class="btn btn-sm btn-outline-success" style="border-radius:8px;">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr><th>Student</th><th>Payment</th><th>Amount</th><th>Balance</th><th>Date</th></tr>
                        </thead>
                        <tbody>
                        @forelse($recent_payments as $r)
                        <tr>
                            <td>
                                @if($r->student)
                                <div class="d-flex align-items-center">
                                    <img src="{{ $r->student->photo }}" width="30" height="30" class="rounded-circle mr-2">
                                    <span class="font-weight-semibold">{{ $r->student->name }}</span>
                                </div>
                                @else — @endif
                            </td>
                            <td>{{ $r->payment->title ?? '—' }}</td>
                            <td><span class="chip" style="background:#d1fae5;color:#065f46;">₹{{ number_format($r->amt_paid) }}</span></td>
                            <td>
                                @if($r->balance > 0)
                                    <span class="chip" style="background:#fee2e2;color:#991b1b;">₹{{ number_format($r->balance) }}</span>
                                @else
                                    <span class="chip" style="background:#d1fae5;color:#065f46;"><i class="icon-checkmark3"></i> Paid</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ \Carbon\Carbon::parse($r->created_at)->format('d M Y') }}</small></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No transactions yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions + Recent Expenses --}}
    <div class="col-lg-4 mb-4">
        {{-- Quick Actions --}}
        <div class="card border-0 shadow-sm mb-3" style="border-radius:16px;">
            <div class="card-header bg-white border-0">
                <h6 class="font-weight-bold mb-0"><i class="icon-lightning mr-2 text-warning"></i>Quick Actions</h6>
            </div>
            <div class="card-body pt-2">
                <a href="{{ route('accountant.fees') }}" class="btn btn-outline-success btn-block text-left mb-2" style="border-radius:10px;"><i class="icon-wallet mr-2"></i>Fee Collection</a>
                <a href="{{ route('accountant.student_fees') }}" class="btn btn-outline-primary btn-block text-left mb-2" style="border-radius:10px;"><i class="icon-users4 mr-2"></i>Student Fees</a>
                <a href="{{ route('accountant.expenses') }}" class="btn btn-outline-danger btn-block text-left mb-2" style="border-radius:10px;"><i class="icon-shrink5 mr-2"></i>Record Expense</a>
                <a href="{{ route('accountant.reports') }}" class="btn btn-outline-secondary btn-block text-left mb-2" style="border-radius:10px;"><i class="icon-stats-bars2 mr-2"></i>View Reports</a>
                <a href="{{ route('accountant.receipts') }}" class="btn btn-outline-dark btn-block text-left" style="border-radius:10px;"><i class="icon-file-pdf mr-2"></i>Receipts</a>
            </div>
        </div>

        {{-- Recent Expenses --}}
        <div class="card border-0 shadow-sm" style="border-radius:16px;">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold mb-0"><i class="icon-shrink5 mr-2 text-danger"></i>Recent Expenses</h6>
                <a href="{{ route('accountant.expenses') }}" class="btn btn-sm btn-outline-danger" style="border-radius:8px;">View All</a>
            </div>
            <div class="card-body p-0">
                @forelse($recent_expenses as $e)
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                    <div>
                        <div class="font-weight-semibold" style="font-size:13px;">{{ $e->title }}</div>
                        <small class="text-muted text-capitalize">{{ $e->category }} &bull; {{ \Carbon\Carbon::parse($e->expense_date)->format('d M') }}</small>
                    </div>
                    <span class="font-weight-bold text-danger">₹{{ number_format($e->amount) }}</span>
                </div>
                @empty
                <div class="text-center text-muted py-3"><small>No expenses recorded.</small></div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
