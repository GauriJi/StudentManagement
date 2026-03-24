{{--Dashboard--}}
<li class="nav-item">
    <a href="{{ route('teacher.dashboard') }}" class="nav-link {{ Route::is('teacher.dashboard') ? 'active' : '' }}">
        <i class="icon-home4"></i>
        <span>{{ __('msg.dashboard') }}</span>
    </a>
</li>

{{--Timetable--}}
<li class="nav-item">
    <a href="{{ route('teacher.timetables') }}" class="nav-link {{ Route::is('teacher.timetables') ? 'active' : '' }}">
        <i class="icon-graduation2"></i>
        <span>Timetables</span>
    </a>
</li>

{{--Students--}}
<li class="nav-item">
    <a href="{{ route('teacher.students') }}" class="nav-link {{ Route::is('teacher.students') ? 'active' : '' }}">
        <i class="icon-users4"></i>
        <span>My Students</span>
    </a>
</li>

{{--Attendance--}}
<li class="nav-item">
    <a href="{{ route('teacher.attendance') }}" class="nav-link {{ Route::is('teacher.attendance') ? 'active' : '' }}">
        <i class="icon-calendar"></i>
        <span>Attendance</span>
    </a>
</li>

{{--Assignments--}}
<li class="nav-item">
    <a href="{{ route('teacher.assignments') }}" class="nav-link {{ Route::is('teacher.assignments') ? 'active' : '' }}">
        <i class="icon-book2"></i>
        <span>Assignments</span>
    </a>
</li>

{{--Study Materials--}}
<li class="nav-item">
    <a href="{{ route('teacher.study_materials') }}" class="nav-link {{ Route::is('teacher.study_materials') ? 'active' : '' }}">
        <i class="icon-file-pdf"></i>
        <span>Study Materials</span>
    </a>
</li>

{{--Exams & Marks--}}
<li class="nav-item">
    <a href="{{ route('teacher.exams') }}" class="nav-link {{ Route::is('teacher.exams') ? 'active' : '' }}">
        <i class="icon-books"></i>
        <span>Exams & Marks</span>
    </a>
</li>

{{--Doubts--}}
<li class="nav-item">
    <a href="{{ route('teacher.doubts') }}" class="nav-link {{ Route::is('teacher.doubts') ? 'active' : '' }}">
        <i class="icon-bubbles4"></i>
        <span>Student Doubts</span>
    </a>
</li>

{{--Chat--}}
<li class="nav-item mb-5">
    <a href="{{ route('teacher.chat.index') }}" class="nav-link {{ Route::is('teacher.chat.*') ? 'active' : '' }}">
        <i class="icon-bubbles3"></i>
        <span>Chat with Students</span>
    </a>
</li>
