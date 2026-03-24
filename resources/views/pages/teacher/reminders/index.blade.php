@extends('layouts.master')
@section('page_title', 'My Reminders')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">My Reminders & Notifications</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            @if($notifications->count() > 0)
                <ul class="media-list">
                    @foreach($notifications as $n)
                        <li class="media">
                            <div class="mr-3">
                                <a href="#" class="btn bg-transparent border-primary text-primary rounded-round border-2 btn-icon"><i class="icon-bell3"></i></a>
                            </div>

                            <div class="media-body">
                                <div class="media-title font-weight-semibold">
                                    {{ isset($n->data['title']) ? $n->data['title'] : 'Notification' }}
                                    <span class="text-muted font-size-sm ml-2">{{ $n->created_at->diffForHumans() }}</span>
                                </div>
                                
                                <span class="text-muted">{{ isset($n->data['message']) ? $n->data['message'] : 'You have a new notification.' }}</span>
                            </div>
                        </li>
                        <hr>
                    @endforeach
                </ul>

                <div class="d-flex justify-content-center mt-3">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="alert alert-info border-0 alert-dismissible">
                    <span class="font-weight-semibold">You do not have any reminders or notifications at this time.</span>
                </div>
            @endif
        </div>
    </div>

@endsection
