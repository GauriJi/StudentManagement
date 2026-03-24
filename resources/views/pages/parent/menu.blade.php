{{-- Dashboard --}}
<li class="nav-item">
    <a href="{{ route('parent.dashboard') }}" class="nav-link {{ Route::is('parent.dashboard') ? 'active' : '' }}">
        <i class="icon-home4"></i><span>Dashboard</span>
    </a>
</li>


{{-- Child Performance --}}
<li class="nav-item">
    @php $firstChild = Qs::findMyChildren(Auth::id())->first(); @endphp
    @if($firstChild)
    <a href="{{ route('parent.performance', $firstChild->user_id) }}" class="nav-link {{ Route::is('parent.performance') ? 'active' : '' }}">
        <i class="icon-stats-bars2"></i><span>Child Performance</span>
    </a>
    @else
    <a href="#" class="nav-link text-muted">
        <i class="icon-stats-bars2"></i><span>Child Performance</span>
    </a>
    @endif
</li>

{{-- Fee Payment --}}
<li class="nav-item">
    <a href="{{ route('parent.fees') }}" class="nav-link {{ Route::is('parent.fees') ? 'active' : '' }}">
        <i class="icon-wallet"></i><span>Fee Payments</span>
    </a>
</li>

{{-- Notifications --}}
<li class="nav-item">
    <a href="{{ route('parent.notifications') }}" class="nav-link {{ Route::is('parent.notifications') ? 'active' : '' }}">
        <i class="icon-bell2"></i><span>Notifications</span>
    </a>
</li>

{{-- Chat with Teacher --}}
<li class="nav-item mb-5">
    <a href="{{ route('parent.chat.index') }}" class="nav-link {{ Route::is('parent.chat.*') ? 'active' : '' }}">
        <i class="icon-bubbles4"></i><span>Chat with Teacher</span>
    </a>
</li>