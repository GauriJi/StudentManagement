{{-- Dashboard --}}
<li class="nav-item">
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
        <i class="icon-home4"></i><span>Dashboard</span>
    </a>
</li>

{{-- Student Information --}}
<li class="nav-item nav-item-submenu {{ Route::is('students.*') ? 'nav-item-expanded nav-item-open' : '' }}">
    <a href="#" class="nav-link"><i class="icon-users"></i><span> Student Information</span></a>
    <ul class="nav nav-group-sub" data-submenu-title="Student Information">
        @php
            $adminSortedClasses = \App\Models\MyClass::all()->sortBy(function ($c) {
                $name = strtolower(trim($c->name));
                if (str_contains($name,'nur') || str_contains($name,'nursery')) return 0;
                if (str_contains($name,'lkg')) return 1;
                if (str_contains($name,'ukg')) return 2;
                preg_match('/(\d+)/', $c->name, $m);
                return isset($m[1]) ? 3 + (int)$m[1] : 99;
            });
        @endphp
        @foreach($adminSortedClasses as $c)
            <li class="nav-item"><a href="{{ route('students.list', $c->id) }}" class="nav-link {{ (Route::is('students.list') && request()->route('class_id') == $c->id) ? 'active' : '' }}">{{ $c->name }}</a></li>
        @endforeach

    </ul>
</li>

{{-- Academic Calendar --}}
<li class="nav-item">
    <a href="{{ route('admin.calendar') }}" class="nav-link {{ Route::is('admin.calendar') ? 'active' : '' }}">
        <i class="icon-calendar3"></i><span>Academic Calendar</span>
    </a>
</li>

{{-- Timetable --}}
<li class="nav-item">
    <a href="{{ route('admin.timetable') }}" class="nav-link {{ Route::is('admin.timetable*') ? 'active' : '' }}">
        <i class="icon-table2"></i><span>Timetable</span>
    </a>
</li>

{{-- Substitution --}}
<li class="nav-item">
    <a href="{{ route('admin.substitution') }}" class="nav-link {{ Route::is('admin.substitution') ? 'active' : '' }}">
        <i class="icon-shuffle"></i><span>Substitution</span>
    </a>
</li>

{{-- Assignments --}}
<li class="nav-item">
    <a href="{{ route('assignments.index') }}" class="nav-link {{ Route::is('assignments.*') ? 'active' : '' }}">
        <i class="icon-book2"></i><span>Assignments</span>
    </a>
</li>

{{-- Attendance --}}
<li class="nav-item">
    <a href="{{ route('attendance.index') }}" class="nav-link {{ Route::is('attendance.*') ? 'active' : '' }}">
        <i class="icon-calendar-check"></i><span>Attendance</span>
    </a>
</li>

{{-- Study Materials --}}
<li class="nav-item">
    <a href="{{ route('study_materials.index') }}" class="nav-link {{ Route::is('study_materials.*') ? 'active' : '' }}">
        <i class="icon-file-pdf"></i><span>Study Materials</span>
    </a>
</li>

{{-- Fees & Dues --}}
<li class="nav-item">
    <a href="{{ route('payments.manage') }}" class="nav-link {{ Route::is('payments.*') ? 'active' : '' }}">
        <i class="icon-wallet"></i><span>Fees & Dues</span>
    </a>
</li>

{{-- Dormitory --}}
<li class="nav-item">
    <a href="{{ route('dorms.index') }}" class="nav-link {{ Route::is('dorms.*') ? 'active' : '' }}">
        <i class="icon-office"></i><span>Dormitory</span>
    </a>
</li>

{{-- Classes & Sections --}}
<li class="nav-item">
    <a href="{{ route('classes.index') }}" class="nav-link {{ Route::is('classes.*') || Route::is('sections.*') ? 'active' : '' }}">
        <i class="icon-stack2"></i><span>Classes & Sections</span>
    </a>
</li>

{{-- Staff --}}
<li class="nav-item">
    <a href="{{ route('admin.staff.index') }}" class="nav-link {{ Route::is('admin.staff.*') ? 'active' : '' }}">
        <i class="icon-user-tie"></i><span>Staff</span>
    </a>
</li>

{{-- Subjects --}}
<li class="nav-item">
    <a href="{{ route('subjects.index') }}" class="nav-link {{ Route::is('subjects.*') ? 'active' : '' }}">
        <i class="icon-books2"></i><span>Subjects</span>
    </a>
</li>


{{-- Exams & Results --}}
<li class="nav-item">
    <a href="{{ route('exams.index') }}" class="nav-link {{ Route::is('exams.*') || Route::is('marks.*') ? 'active' : '' }}">
        <i class="icon-pencil5"></i><span>Exams & Results</span>
    </a>
</li>
