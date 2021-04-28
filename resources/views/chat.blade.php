@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    {{ __('Chatbox') }}
                </div>
                <div class="card-body">
                    <div id="frame">
                        <div id="sidepanel">
                            <div id="profile">
                                <div class="wrap">
                                    <img id="profile-img" src="{{ Auth::user()->avatar }}" class="online" alt="" />
                                    <p>{{ Auth::user()->name }}</p>
                                </div>
                            </div>
                            <div id="search">
                                <label for=""><i class="fa fa-search" aria-hidden="true"></i></label>
                                <input type="text" placeholder="Search contacts..." />
                            </div>
                            <div id="contacts">
                                <ul class="list-group">
                                    @foreach ($users as $user)
                                        <li class="contact" data-attr="{{ $user->user_id }}">
                                            <div class="wrap">
                                                <span class="contact-status online"></span>
                                                <img src="{{ $user->avatar }}" alt="">
                                                <div class="meta">
                                                    <p class="name">{{ $user->name }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="content">
                            @php
                                $toUser = (isset($_GET['user'])) ? $_GET['user'] : '';
                            @endphp
                            @if (isset($_GET['user']))
                            <div class="contact-profile">
                                @if ($toUser == $toUserData->user_id)
                                <img src="{{ $toUserData->avatar }}" alt="" />
                                <p>
                                    {{ $toUserData->name }}
                                </p>
                                @endif
                            </div>
                            <div class="messages">
                                <ul>
                                    @foreach ($messages as $msg)
                                        @if ($msg->to === Auth::user()->user_id && $msg->user_id == $toUser)
                                            <li class="replies" title="{{ $msg->updated_at->diffForHumans() }}">
                                                <img src="{{ Auth::user()->avatar }}" alt="" />
                                                <p>{{ $msg->msg }}</p>
                                            </li>
                                        @elseIf ($msg->to !== Auth::user()->user_id && $msg->user_id == Auth::user()->user_id && $msg->to == $toUser)
                                            <li class="sent" title="{{ $msg->updated_at->diffForHumans() }}">
                                                <img src="{{ $msg->avatar }}" alt="" />
                                                <p class="mb-0">{{ $msg->msg }}</p>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            <div class="message-input">
                                <div class="wrap">
                                    <input type="text" placeholder="Write your message..." />
                                    <input type="hidden" name="to" id="to" value="{{ $toUser }}">
                                    <button class="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                                </div>
                            </div>
                            @else
                                <h3 class="text-center text-muted mt-5">Please choose from your contact first!</h3>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@include('scripts.chat')
