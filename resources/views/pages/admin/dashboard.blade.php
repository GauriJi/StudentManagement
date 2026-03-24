@extends('layouts.master')
@section('page_title', 'Admin Dashboard')
@section('content')
<style>
.adm-hero{background:linear-gradient(135deg,#1a1a2e 0%,#16213e 60%,#0f3460 100%);border-radius:20px;padding:30px;color:#fff;margin-bottom:24px;position:relative;overflow:hidden;}
.adm-hero::before{content:'';position:absolute;top:-60px;right:-60px;width:250px;height:250px;border-radius:50%;background:rgba(255,255,255,.04);}
.adm-hero::after{content:'';position:absolute;bottom:-40px;left:200px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,.03);}
.stat-card{border:none;border-radius:16px;transition:transform .25s,box-shadow .25s;cursor:pointer;}
.stat-card:hover{transform:translateY(-4px);box-shadow:0 12px 28px rgba(0,0,0,.12)!important;}
.stat-icon{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin-bottom:10px;}
.module-btn{border-radius:12px;border:1.5px solid #e9ecef;transition:all .2s;text-align:left;}
.module-btn:hover{border-color:#3498db;background:#f0f7ff;transform:translateX(3px);}
.tag{display:inline-block;padding:2px 9px;border-radius:999px;font-size:11px;font-weight:700;}
</style>

{{-- Hero --}}
<div class="adm-hero mb-4">
    <div class="row align-items-center">
        <div class="col-auto">
            <img src="{{ Auth::user()->photo }}" width="68" height="68" class="rounded-circle border-2 border-white" style="border:3px solid rgba(255,255,255,.4);">
        </div>
        <div class="col">
            <h3 class="font-weight-bold mb-1">Welcome, {{ Auth::user()->name }}</h3>
            <p class="mb-0 opacity-75">{{ date('l, d F Y') }} &nbsp;|&nbsp; <i class="icon-graduation2"></i> Admin Control Panel</p>
        </div>
        <div class="col-auto d-none d-lg-flex gap-2 flex-column text-right">
            <span class="badge px-3 py-2 mb-1" style="background:rgba(39,174,96,.3);font-size:13px;border-radius:8px;">{{ $total_students }} Students</span>
            <span class="badge px-3 py-2" style="background:rgba(52,152,219,.3);font-size:13px;border-radius:8px;">{{ $total_teachers }} Teachers</span>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row mb-4">
    <div class="col-6 col-md-3 mb-3">
        <div class="card stat-card shadow-sm p-3 h-100" style="background:linear-gradient(135deg,#27ae60,#2ecc71);color:#fff;">
            <div class="stat-icon bg-white" style="color:#27ae60;"><i class="icon-users4"></i></div>
            <h3 class="font-weight-bold mb-0">{{ number_format($total_students) }}</h3>
            <small class="opacity-75 text-uppercase font-weight-600">Total Students</small>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="card stat-card shadow-sm p-3 h-100" style="background:linear-gradient(135deg,#2980b9,#3498db);color:#fff;">
            <div class="stat-icon bg-white" style="color:#2980b9;"><i class="icon-user-tie"></i></div>
            <h3 class="font-weight-bold mb-0">{{ number_format($total_teachers) }}</h3>
            <small class="opacity-75 text-uppercase">Teachers</small>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="card stat-card shadow-sm p-3 h-100" style="background:linear-gradient(135deg,#8e44ad,#9b59b6);color:#fff;">
            <div class="stat-icon bg-white" style="color:#8e44ad;"><i class="icon-calendar-check"></i></div>
            <h3 class="font-weight-bold mb-0">{{ $att_pct }}%</h3>
            <small class="opacity-75 text-uppercase">Today's Attendance</small>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="card stat-card shadow-sm p-3 h-100" style="background:linear-gradient(135deg,#e74c3c,#c0392b);color:#fff;">
            <div class="stat-icon bg-white" style="color:#e74c3c;"><i class="icon-coin-dollar"></i></div>
            <h3 class="font-weight-bold mb-0">₹{{ number_format($fees_pending) }}</h3>
            <small class="opacity-75 text-uppercase">Fees Pending</small>
        </div>
    </div>
</div>

<div class="row mb-4">
    {{-- Secondary stats --}}
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius:16px;">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 border-right pb-3 pt-1">
                        <h4 class="font-weight-bold text-primary mb-0">{{ $total_classes }}</h4>
                        <small class="text-muted text-uppercase">Classes</small>
                    </div>
                    <div class="col-6 pb-3 pt-1">
                        <h4 class="font-weight-bold text-info mb-0">{{ $total_subjects }}</h4>
                        <small class="text-muted text-uppercase">Subjects</small>
                    </div>
                    <div class="col-6 border-right border-top pt-3">
                        <h4 class="font-weight-bold text-success mb-0">₹{{ number_format($total_fees_paid) }}</h4>
                        <small class="text-muted text-uppercase">Fees Paid</small>
                    </div>
                    <div class="col-6 border-top pt-3">
                        <h4 class="font-weight-bold text-warning mb-0">{{ $today_present }}/{{ $today_total }}</h4>
                        <small class="text-muted text-uppercase">Present Today</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Attendance Chart --}}
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius:16px;">
            <div class="card-header bg-white border-0 pb-0">
                <h6 class="font-weight-bold mb-0"><i class="icon-stats-bars2 mr-2 text-primary"></i>Attendance (6 Months)</h6>
            </div>
            <div class="card-body pt-2">
                <canvas id="attChart" height="120"></canvas>
            </div>
        </div>
    </div>

    {{-- Fee Doughnut --}}
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius:16px;">
            <div class="card-header bg-white border-0 pb-0">
                <h6 class="font-weight-bold mb-0"><i class="icon-wallet mr-2 text-success"></i>Fee Collection</h6>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <canvas id="feeChart" width="160" height="160"></canvas>
                <div class="mt-2 text-center">
                    <span class="tag mr-1" style="background:#d1fae5;color:#065f46;">Paid ₹{{ number_format($total_fees_paid) }}</span>
                    <span class="tag" style="background:#fee2e2;color:#991b1b;">Pending ₹{{ number_format($fees_pending) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Recent Students --}}
    <div class="col-lg-7 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius:16px;">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold mb-0"><i class="icon-users4 mr-2 text-success"></i>Recent Admissions</h6>
                <a href="{{ route('students.index') }}" class="btn btn-sm btn-outline-success" style="border-radius:8px;">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light"><tr><th>Student</th><th>Class</th><th>Status</th></tr></thead>
                        <tbody>
                        @forelse($recent_students as $sr)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($sr->user)
                                    <img src="{{ $sr->user->photo }}" width="32" height="32" class="rounded-circle mr-2 border">
                                    <span class="font-weight-semibold">{{ $sr->user->name }}</span>
                                    @else <span class="text-muted">—</span> @endif
                                </div>
                            </td>
                            <td><span class="badge badge-light border">{{ $sr->my_class->name ?? '—' }}</span></td>
                            <td><span class="tag" style="background:#d1fae5;color:#065f46;">Active</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">No students yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Right column: Quick Links + Upcoming --}}
    <div class="col-lg-5 mb-4">
        {{-- Quick Module Links --}}
        <div class="card border-0 shadow-sm mb-3" style="border-radius:16px;">
            <div class="card-header bg-white border-0"><h6 class="font-weight-bold mb-0"><i class="icon-lightning mr-2 text-warning"></i>Quick Access</h6></div>
            <div class="card-body pt-2">
                <div class="row">
                    @php
                    $modules = [
                        ['icon'=>'icon-calendar3','label'=>'Calendar','route'=>route('admin.calendar'),'color'=>'#e74c3c'],
                        ['icon'=>'icon-table2','label'=>'Timetable','route'=>route('tt.index'),'color'=>'#3498db'],
                        ['icon'=>'icon-shuffle','label'=>'Substitution','route'=>route('admin.substitution'),'color'=>'#8e44ad'],
                        ['icon'=>'icon-book2','label'=>'Assignments','route'=>route('assignments.index'),'color'=>'#27ae60'],
                        ['icon'=>'icon-pencil5','label'=>'Exams','route'=>route('exams.index'),'color'=>'#f39c12'],
                        ['icon'=>'icon-wallet','label'=>'Fees','route'=>route('payments.manage'),'color'=>'#e67e22'],
                        ['icon'=>'icon-users4','label'=>'Teachers','route'=>route('teachers.index'),'color'=>'#1abc9c'],
                        ['icon'=>'icon-stack2','label'=>'Classes','route'=>route('classes.index'),'color'=>'#2c3e50'],
                    ];
                    @endphp
                    @foreach($modules as $m)
                    <div class="col-6 mb-2">
                        <a href="{{ $m['route'] }}" class="btn btn-light module-btn btn-block py-2">
                            <i class="{{ $m['icon'] }} mr-2" style="color:{{ $m['color'] }}"></i>
                            <span style="font-size:13px;">{{ $m['label'] }}</span>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Upcoming Exams --}}
        <div class="card border-0 shadow-sm" style="border-radius:16px;">
            <div class="card-header bg-white border-0"><h6 class="font-weight-bold mb-0"><i class="icon-pencil5 mr-2 text-warning"></i>Upcoming Exams</h6></div>
            <div class="card-body p-0">
                @forelse($upcoming_exams as $ex)
                <div class="d-flex align-items-center px-3 py-2 border-bottom">
                    <div class="mr-3 text-center" style="min-width:40px;">
                        <div class="font-weight-bold text-warning" style="font-size:18px;line-height:1;">{{ \Carbon\Carbon::parse($ex->start_date)->format('d') }}</div>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($ex->start_date)->format('M') }}</small>
                    </div>
                    <div>
                        <div class="font-weight-semibold" style="font-size:13px;">{{ $ex->name }}</div>
                        <small class="text-muted">{{ $ex->year }}</small>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-3"><small>No upcoming exams.</small></div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Attendance Bar Chart
const attCtx = document.getElementById('attChart').getContext('2d');
new Chart(attCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($att_chart->pluck('month')) !!},
        datasets: [{
            label: 'Attendance %',
            data: {!! json_encode($att_chart->pluck('pct')) !!},
            backgroundColor: 'rgba(52,152,219,.7)',
            borderRadius: 6,
        }]
    },
    options: {
        responsive:true, maintainAspectRatio:true,
        plugins:{legend:{display:false}},
        scales:{y:{beginAtZero:true,max:100,grid:{color:'rgba(0,0,0,.04)'},ticks:{callback:v=>v+'%'}},x:{grid:{display:false}}}
    }
});

// Fee Doughnut Chart
const feeCtx = document.getElementById('feeChart').getContext('2d');
new Chart(feeCtx, {
    type: 'doughnut',
    data: {
        labels: ['Paid','Pending'],
        datasets: [{
            data: [{{ $total_fees_paid }}, {{ $fees_pending }}],
            backgroundColor: ['#27ae60','#e74c3c'],
            borderWidth: 0
        }]
    },
    options: {
        responsive:false, cutout:'72%',
        plugins:{legend:{display:false}}
    }
});
</script>
@endpush
@endsection