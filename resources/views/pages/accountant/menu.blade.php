{{-- Dashboard --}}
<li class="nav-item">
    <a href="{{ route('accountant.dashboard') }}" class="nav-link {{ Route::is('accountant.dashboard') ? 'active' : '' }}">
        <i class="icon-home4"></i><span>Dashboard</span>
    </a>
</li>
{{-- Fee Collection --}}
<li class="nav-item">
    <a href="{{ route('accountant.fees') }}" class="nav-link {{ Route::is('accountant.fees') ? 'active' : '' }}">
        <i class="icon-wallet"></i><span>Fee Collection</span>
    </a>
</li>
{{-- Student Fees --}}
<li class="nav-item">
    <a href="{{ route('accountant.student_fees') }}" class="nav-link {{ Route::is('accountant.student_fees') ? 'active' : '' }}">
        <i class="icon-users4"></i><span>Student Fees</span>
    </a>
</li>
{{-- Expenses --}}
<li class="nav-item">
    <a href="{{ route('accountant.expenses') }}" class="nav-link {{ Route::is('accountant.expenses') ? 'active' : '' }}">
        <i class="icon-shrink5"></i><span>Expenses</span>
    </a>
</li>
{{-- Reports --}}
<li class="nav-item">
    <a href="{{ route('accountant.reports') }}" class="nav-link {{ Route::is('accountant.reports') ? 'active' : '' }}">
        <i class="icon-stats-bars2"></i><span>Reports</span>
    </a>
</li>
{{-- Receipts --}}
<li class="nav-item">
    <a href="{{ route('accountant.receipts') }}" class="nav-link {{ Route::is('accountant.receipts') ? 'active' : '' }}">
        <i class="icon-file-pdf"></i><span>Receipts</span>
    </a>
</li>
