@extends('layouts.master')
@section('page_title', 'Financial Reports')
@section('content')
<style>
.report-bar-wrap{height:120px;display:flex;align-items:flex-end;gap:4px;}
.report-bar-income{background:linear-gradient(#27ae60,#2ecc71);border-radius:4px 4px 0 0;min-width:18px;transition:height .4s;}
.report-bar-expense{background:linear-gradient(#e74c3c,#c0392b);border-radius:4px 4px 0 0;min-width:18px;transition:height .4s;}
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="font-weight-bold mb-0"><i class="icon-stats-bars2 mr-2 text-primary"></i>Financial Reports</h5>
        <small class="text-muted">Income vs expense analysis</small>
    </div>
    {{-- Year / Month Filter --}}
    <form method="GET" class="form-inline">
        <select name="year" class="form-control form-control-sm mr-2" style="border-radius:8px;" onchange="this.form.submit()">
            @foreach($years as $y)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
        <select name="month" class="form-control form-control-sm" style="border-radius:8px;" onchange="this.form.submit()">
            <option value="">All Months</option>
            @foreach(range(1,12) as $m)
            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
            @endforeach
        </select>
    </form>
</div>

{{-- KPI Row --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 text-center" style="border-radius:14px;background:linear-gradient(135deg,#27ae60,#2ecc71);color:#fff;">
            <h4 class="font-weight-bold mb-0">₹{{ number_format($total_income) }}</h4>
            <small class="opacity-75">Total Income (Fee Collected)</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 text-center" style="border-radius:14px;background:linear-gradient(135deg,#e74c3c,#c0392b);color:#fff;">
            <h4 class="font-weight-bold mb-0">₹{{ number_format($total_expense) }}</h4>
            <small class="opacity-75">Total Expenses</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 text-center" style="border-radius:14px;background:linear-gradient(135deg,{{ $net_balance >= 0 ? '#2980b9,#3498db' : '#f39c12,#e67e22' }});color:#fff;">
            <h4 class="font-weight-bold mb-0">₹{{ number_format(abs($net_balance)) }}</h4>
            <small class="opacity-75">Net {{ $net_balance >= 0 ? 'Surplus' : 'Deficit' }}</small>
        </div>
    </div>
</div>

{{-- Monthly Breakdown Table --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:14px;">
    <div class="card-header bg-white border-0">
        <h6 class="font-weight-bold mb-0">Monthly Breakdown — {{ $year }}</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr><th>Month</th><th>Income (Fees)</th><th>Expenses</th><th>Net</th><th>Balance</th></tr>
                </thead>
                <tbody>
                @php $grandIncome = 0; $grandExp = 0; @endphp
                @foreach($monthly as $row)
                @php
                    $net = $row['income'] - $row['expense'];
                    $grandIncome += $row['income'];
                    $grandExp   += $row['expense'];
                @endphp
                <tr>
                    <td class="font-weight-semibold">{{ $row['month'] }}</td>
                    <td><span class="text-success font-weight-bold">₹{{ number_format($row['income']) }}</span></td>
                    <td><span class="text-danger font-weight-bold">₹{{ number_format($row['expense']) }}</span></td>
                    <td><span class="font-weight-bold {{ $net >= 0 ? 'text-primary' : 'text-warning' }}">₹{{ number_format(abs($net)) }}</span></td>
                    <td>
                        @if($net >= 0)
                            <span class="badge badge-success">Surplus</span>
                        @elseif($net < 0)
                            <span class="badge badge-warning">Deficit</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
                <tfoot class="bg-light font-weight-bold">
                    <tr>
                        <td>Total</td>
                        <td class="text-success">₹{{ number_format($grandIncome) }}</td>
                        <td class="text-danger">₹{{ number_format($grandExp) }}</td>
                        <td class="{{ ($grandIncome-$grandExp) >= 0 ? 'text-primary' : 'text-warning' }}">₹{{ number_format(abs($grandIncome-$grandExp)) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- Fee by Class --}}
<div class="card border-0 shadow-sm" style="border-radius:14px;">
    <div class="card-header bg-white border-0">
        <h6 class="font-weight-bold mb-0">Fee Collection by Class</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light"><tr><th>Class</th><th>Fee Title</th><th>Defined Amount</th><th>Collected</th></tr></thead>
                <tbody>
                @foreach($by_class as $className => $fees)
                @foreach($fees as $f)
                <tr>
                    <td><span class="badge badge-light border">{{ $className }}</span></td>
                    <td>{{ $f->title }}</td>
                    <td>₹{{ number_format($f->amount) }}</td>
                    <td><span class="text-success font-weight-bold">₹{{ number_format($f->collected) }}</span></td>
                </tr>
                @endforeach
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
