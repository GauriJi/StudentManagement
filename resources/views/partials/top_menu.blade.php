<div class="navbar navbar-expand-md navbar-dark">
    <div class="mt-2 mr-5">
        <a href="{{ route('dashboard') }}" class="d-inline-block">
        <h4 class="text-bold text-white">{{ Qs::getSystemName() }}</h4>
        </a>
    </div>
  {{--  <div class="navbar-brand">
        <a href="index.html" class="d-inline-block">
            <img src="{{ asset('global_assets/images/logo_light.png') }}" alt="">
        </a>
    </div>--}}

    <div class="d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="icon-paragraph-justify3"></i>
                </a>
            </li>


        </ul>

			<span class="navbar-text ml-md-3 mr-md-auto"></span>

        <ul class="navbar-nav">
            @if(Qs::userIsStudent())
                @php 
                    $unreadNotifs = \App\Models\StudentNotification::where('student_id', Auth::user()->id)->where('is_read', false)->count(); 
                    $recentNotifs = \App\Models\StudentNotification::where('student_id', Auth::user()->id)->orderByDesc('created_at')->take(5)->get();
                @endphp
                <li class="nav-item dropdown dropdown-user mr-md-2" id="stNotifBell">
                    <a href="#" class="navbar-nav-link dropdown-toggle caret-0" data-toggle="dropdown">
                        <i class="icon-bell2"></i>
                        @if($unreadNotifs > 0)
                            <span class="badge badge-pill bg-warning-400 ml-auto ml-md-0" style="position:absolute;top:5px;right:2px;padding:2px 5px;font-size:10px;">{{ $unreadNotifs }}</span>
                        @endif
                    </a>

                    <div class="dropdown-menu dropdown-menu-right dropdown-content wmin-md-300">
                        <div class="dropdown-content-header">
                            <span class="font-weight-semibold">Notifications</span>
                            <a href="{{ route('student.notifications') }}" class="text-default"><i class="icon-list3"></i></a>
                        </div>

                        <div class="dropdown-content-body dropdown-scrollable">
                            <ul class="media-list">
                                @forelse($recentNotifs as $rn)
                                    <li class="media">
                                        <div class="media-body">
                                            <div class="media-title">
                                                <a href="{{ route('student.notifications') }}">
                                                    <span class="font-weight-semibold">{{ $rn->title }}</span>
                                                    <span class="text-muted float-right font-size-sm">{{ $rn->created_at->diffForHumans() }}</span>
                                                </a>
                                            </div>
                                            <span class="text-muted">{{ \Illuminate\Support\Str::limit($rn->message, 50) }}</span>
                                        </div>
                                    </li>
                                @empty
                                    <li class="media text-center p-3 text-muted">No new notifications</li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="dropdown-content-footer justify-content-center p-0">
                            <a href="{{ route('student.notifications') }}" class="bg-light text-grey w-100 py-2" data-popup="tooltip" title="View All"><i class="icon-menu7 d-block top-0"></i></a>
                        </div>
                    </div>
                </li>
            @endif

            @if(Qs::userIsTeacher())
                @php 
                    $unreadNotifs = \App\Models\TeacherNotification::where('teacher_id', Auth::user()->id)->where('is_read', false)->count(); 
                    $recentNotifs = \App\Models\TeacherNotification::where('teacher_id', Auth::user()->id)->orderByDesc('created_at')->take(5)->get();
                @endphp
                <li class="nav-item dropdown dropdown-user mr-md-2" id="teacherNotifBell">
                    <a href="#" class="navbar-nav-link dropdown-toggle caret-0" data-toggle="dropdown">
                        <i class="icon-bell2"></i>
                        @if($unreadNotifs > 0)
                            <span class="badge badge-pill bg-warning-400 ml-auto ml-md-0" style="position:absolute;top:5px;right:2px;padding:2px 5px;font-size:10px;">{{ $unreadNotifs }}</span>
                        @endif
                    </a>

                    <div class="dropdown-menu dropdown-menu-right dropdown-content wmin-md-300">
                        <div class="dropdown-content-header">
                            <span class="font-weight-semibold">Notifications</span>
                            <a href="{{ route('teacher.notifications') }}" class="text-default"><i class="icon-list3"></i></a>
                        </div>

                        <div class="dropdown-content-body dropdown-scrollable">
                            <ul class="media-list">
                                @forelse($recentNotifs as $rn)
                                    <li class="media">
                                        <div class="media-body">
                                            <div class="media-title">
                                                <a href="{{ route('teacher.notifications') }}">
                                                    <span class="font-weight-semibold">{{ $rn->title }}</span>
                                                    <span class="text-muted float-right font-size-sm">{{ $rn->created_at->diffForHumans() }}</span>
                                                </a>
                                            </div>
                                            <span class="text-muted">{{ \Illuminate\Support\Str::limit($rn->message, 50) }}</span>
                                        </div>
                                    </li>
                                @empty
                                    <li class="media text-center p-3 text-muted">No new notifications</li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="dropdown-content-footer justify-content-center p-0">
                            <a href="{{ route('teacher.notifications') }}" class="bg-light text-grey w-100 py-2" data-popup="tooltip" title="View All"><i class="icon-menu7 d-block top-0"></i></a>
                        </div>
                    </div>
                </li>
            @endif

            @if(Qs::userIsParent())
                @php
                    $children = \App\Models\StudentRecord::where('my_parent_id', Auth::user()->id)->pluck('user_id');
                    $childUnread = \App\Models\StudentNotification::whereIn('student_id', $children)->where('is_read', false)->count();
                    $directUnread = \App\Models\ParentNotification::where('parent_id', Auth::user()->id)->where('is_read', false)->count();
                    $unreadNotifs = $childUnread + $directUnread;
                    $recentChildNotifs = \App\Models\StudentNotification::whereIn('student_id', $children)->orderByDesc('created_at')->take(4)->get();
                    $recentDirectNotifs = \App\Models\ParentNotification::where('parent_id', Auth::user()->id)->orderByDesc('created_at')->take(4)->get();
                    $recentNotifs = $recentChildNotifs->merge($recentDirectNotifs)->sortByDesc('created_at')->take(5);
                @endphp
                <li class="nav-item dropdown dropdown-user mr-md-2" id="parentNotifBell">
                    <a href="#" class="navbar-nav-link dropdown-toggle caret-0" data-toggle="dropdown">
                        <i class="icon-bell2"></i>
                        @if($unreadNotifs > 0)
                            <span class="badge badge-pill bg-warning-400 ml-auto ml-md-0" style="position:absolute;top:5px;right:2px;padding:2px 5px;font-size:10px;">{{ $unreadNotifs }}</span>
                        @endif
                    </a>

                    <div class="dropdown-menu dropdown-menu-right dropdown-content wmin-md-300">
                        <div class="dropdown-content-header">
                            <span class="font-weight-semibold">Notifications</span>
                            <a href="{{ route('parent.notifications') }}" class="text-default"><i class="icon-list3"></i></a>
                        </div>

                        <div class="dropdown-content-body dropdown-scrollable">
                            <ul class="media-list">
                                @forelse($recentNotifs as $rn)
                                    <li class="media">
                                        <div class="media-body">
                                            <div class="media-title">
                                                <a href="{{ route('parent.notifications') }}">
                                                    <span class="font-weight-semibold">{{ $rn->title }}</span>
                                                    <span class="text-muted float-right font-size-sm">{{ $rn->created_at->diffForHumans() }}</span>
                                                </a>
                                            </div>
                                            <span class="text-muted">{{ \Illuminate\Support\Str::limit($rn->message, 50) }}</span>
                                        </div>
                                    </li>
                                @empty
                                    <li class="media text-center p-3 text-muted">No new notifications</li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="dropdown-content-footer justify-content-center p-0">
                            <a href="{{ route('parent.notifications') }}" class="bg-light text-grey w-100 py-2" data-popup="tooltip" title="View All"><i class="icon-menu7 d-block top-0"></i></a>
                        </div>
                    </div>
                </li>
            @endif

            <li class="nav-item dropdown">
                <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-lan"></i>
                    Language
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="javascript:void(0);" onclick="changeLanguage('en')" class="dropdown-item">English</a>
                    <a href="javascript:void(0);" onclick="changeLanguage('hi')" class="dropdown-item">Hindi (हिंदी)</a>
                </div>
            </li>

            <li class="nav-item dropdown dropdown-user">
                <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
                    <img style="width: 38px; height:38px;" src="{{ Auth::user()->photo }}" class="rounded-circle" alt="photo">
                    <span>{{ Auth::user()->name }}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ Qs::userIsStudent() ? route('students.show', Qs::hash(Qs::findStudentRecord(Auth::user()->id)->id)) : route('users.show', Qs::hash(Auth::user()->id)) }}" class="dropdown-item"><i class="icon-user-plus"></i> {{ __('my_profile') }}</a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('my_account') }}" class="dropdown-item"><i class="icon-cog5"></i> {{ __('account_settings') }}</a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
          document.getElementById('logout-form').submit();" class="dropdown-item"><i class="icon-switch2"></i> {{ __('logout') }}</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>
