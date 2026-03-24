<li class="nav-item">
    <a href="{{ route('student.dashboard') }}" class="nav-link {{ Route::is('student.dashboard') ? 'active' : '' }}">
        <i class="icon-home4"></i>
        <span>Dashboard</span>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('student.timetable') }}" class="nav-link {{ Route::is('student.timetable') ? 'active' : '' }}">
        <i class="icon-calendar3"></i>
        <span>Timetable</span>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('student.attendance') }}" class="nav-link {{ Route::is('student.attendance') ? 'active' : '' }}">
        <i class="icon-alarm"></i>
        <span>Attendance</span>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('student.exams') }}" class="nav-link {{ Route::is('student.exams') ? 'active' : '' }}">
        <i class="icon-books"></i>
        <span>Exams & Marks</span>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('student.notes') }}" class="nav-link {{ Route::is('student.notes') ? 'active' : '' }}">
        <i class="icon-file-text2"></i>
        <span>Notes</span>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('student.assignments') }}" class="nav-link {{ Route::is('student.assignments') ? 'active' : '' }}">
        <i class="icon-pencil7"></i>
        <span>Assignments</span>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('student.chat.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['student.chat.index', 'student.chat']) ? 'active' : '' }}">
        <i class="icon-bubbles4"></i>
        <span>Chat with Teacher</span>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('student.doubts') }}" class="nav-link {{ Route::is('student.doubts') ? 'active' : '' }}">
        <i class="icon-question7"></i>
        <span>Doubt Query</span>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('student.progress') }}" class="nav-link {{ Route::is('student.progress') ? 'active' : '' }}">
        <i class="icon-stats-bars2"></i>
        <span>My Progress</span>
    </a>
</li>
