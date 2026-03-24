@extends('layouts.master')
@section('page_title', 'Doubt Discussion')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">{{ $doubt->title }} ({{ $doubt->subject->name }})</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            @if($doubt->is_resolved)
                <div class="alert alert-success alert-styled-left alert-arrow-left alert-dismissible">
                    <span class="font-weight-semibold">Resolved!</span> This doubt has been marked as resolved.
                </div>
            @else
                @if(Qs::userIsTeamSA() || Auth::user()->id == $doubt->teacher_id)
                    <form method="post" action="{{ route('doubts.resolve', $doubt->id) }}" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-success"><i class="icon-checkmark"></i> Mark as Resolved</button>
                    </form>
                @endif
            @endif

            <ul class="media-list media-chat mb-3">
                @foreach($doubt->messages as $msg)
                    @php
                        $isMe = $msg->user_id == Auth::user()->id;
                    @endphp
                    <li class="media {{ $isMe ? 'media-chat-item-reverse' : '' }}">
                        <div class="media-body">
                            <div class="media-chat-item">{{ $msg->message }}</div>
                            <div class="font-size-sm text-muted mt-2">
                                {{ $msg->user->name }} ({{ ucwords(str_replace('_', ' ', $msg->user->user_type)) }}) - {{ $msg->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            @if(!$doubt->is_resolved)
                <form method="post" action="{{ route('doubts.reply', $doubt->id) }}">
                    @csrf
                    <textarea name="message" class="form-control mb-3" rows="3" cols="1" placeholder="Enter your message..." required></textarea>
                    
                    <div class="d-flex align-items-center">
                        <button type="submit" class="btn bg-teal-400 btn-labeled btn-labeled-right ml-auto"><b><i class="icon-paperplane"></i></b> Send</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection
