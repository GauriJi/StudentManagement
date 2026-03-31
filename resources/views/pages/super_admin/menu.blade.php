{{-- Dashboard --}}
<li class="nav-item">
    <a href="{{ route('super_admin.dashboard') }}" class="nav-link {{ Route::is('super_admin.dashboard') ? 'active' : '' }}">
        <i class="icon-home4"></i><span>Dashboard</span>
    </a>
</li>

{{-- Student Information --}}
<li class="nav-item nav-item-submenu {{ Route::is('students.*') ? 'nav-item-expanded nav-item-open' : '' }}">
    <a href="#" class="nav-link"><i class="icon-users"></i><span> Student Information</span></a>
    <ul class="nav nav-group-sub" data-submenu-title="Student Information">
        @php
            $sortedClasses = \App\Models\MyClass::all()->sortBy(function ($c) {
                $name  = strtolower(trim($c->name));
                $order = ['nur' => 0, 'nursery' => 0, 'lkg' => 1, 'ukg' => 2];
                foreach ($order as $key => $pos) {
                    if (str_contains($name, $key)) return $pos;
                }
                preg_match('/(\d+)/', $c->name, $m);
                return isset($m[1]) ? 3 + (int)$m[1] : 99;
            });
        @endphp
        @foreach($sortedClasses as $c)
            <li class="nav-item"><a href="{{ route('students.list', $c->id) }}" class="nav-link {{ (Route::is('students.list') && request()->route('class_id') == $c->id) ? 'active' : '' }}">{{ $c->name }}</a></li>
        @endforeach
    </ul>
</li>

{{-- User Management --}}
<li class="nav-item">
    <a href="{{ route('sa.users.index') }}" class="nav-link {{ Route::is('sa.users.*') ? 'active' : '' }}">
        <i class="icon-users4"></i><span>User Management</span>
    </a>
</li>

{{-- Fee Management --}}
<li class="nav-item">
    <a href="{{ route('sa.fees.index') }}" class="nav-link {{ Route::is('sa.fees.*') ? 'active' : '' }}">
        <i class="icon-wallet"></i><span>Fee Management</span>
    </a>
</li>

{{-- Staff Management --}}
<li class="nav-item nav-item-submenu {{ Route::is('sa.staff.*') ? 'nav-item-expanded nav-item-open' : '' }}">
    <a href="#" class="nav-link"><i class="icon-office"></i><span>Staff Management</span></a>
    <ul class="nav nav-group-sub" data-submenu-title="Staff">
        <li class="nav-item"><a href="{{ route('sa.staff.index') }}" class="nav-link {{ Route::is('sa.staff.index') ? 'active' : '' }}">All Staff</a></li>
        <li class="nav-item"><a href="{{ route('sa.staff.create') }}" class="nav-link {{ Route::is('sa.staff.create') ? 'active' : '' }}">Add Staff</a></li>
        <li class="nav-item"><a href="{{ route('sa.staff.attendance') }}" class="nav-link {{ Route::is('sa.staff.attendance') ? 'active' : '' }}">Staff Attendance</a></li>
    </ul>
</li>

{{-- Notifications --}}
<li class="nav-item">
    <a href="{{ route('sa.notifications.index') }}" class="nav-link {{ Route::is('sa.notifications.*') ? 'active' : '' }}">
        <i class="icon-bell2"></i><span>Notifications</span>
    </a>
</li>

{{-- Settings --}}
<li class="nav-item">
    <a href="{{ route('settings') }}" class="nav-link {{ Route::is('settings') ? 'active' : '' }}">
        <i class="icon-gear"></i><span>Settings</span>
    </a>
</li>