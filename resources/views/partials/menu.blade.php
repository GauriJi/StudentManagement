<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">
                    <div class="mr-3">
                        <a href="{{ route('my_account') }}"><img src="{{ Auth::user()->photo }}" width="38" height="38" class="rounded-circle" alt="photo"></a>
                    </div>

                    <div class="media-body">
                        <div class="media-title font-weight-semibold">{{ Auth::user()->name }}</div>
                        <div class="font-size-xs opacity-50">
                            <i class="icon-user font-size-sm"></i> &nbsp;{{ ucwords(str_replace('_', ' ', Auth::user()->user_type)) }}
                        </div>
                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="{{ route('my_account') }}" class="text-white"><i class="icon-cog3"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->

        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                @include('pages.'.Qs::getUserType().'.menu')

                {{--Manage Account--}}
                <li class="nav-item">
                    <a href="{{ route('my_account') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['my_account']) ? 'active' : '' }}"><i class="icon-user"></i> <span>{{ __('msg.my_account') }}</span></a>
                </li>

                </ul>
            </div>
        </div>
</div>
