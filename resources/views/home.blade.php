@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    {{ __('Tweets') }}
                    <button type="button" class="btn btn-info btn-sm text-white" data-toggle="modal" data-target="#tweetModal"><i class="fa fa-plus"></i> New Tweets</button>
                </div>

                <div class="card-body">
                    <div class="media-block">
                        @foreach ($tweets as $key => $tweet)
                        <div class="media-body">
                            <div class="text-muted text-sm">
                                @if (Auth::user()->avatar === 'avatar.png')
                                    <img src="{{ URL::asset('img/profile/avatar.png') }}" alt="" class="rounded-circle img-xs">
                                @else
                                    <img src="{{ Auth::user()->avatar }}" alt="" class="rounded-circle img-xs">
                                @endif
                                <strong class="text-info">
                                    @if ($tweet[0]->user_id === Auth::user()->user_id)
                                        Me
                                    @else
                                        {{ $tweet[0]->name }}
                                    @endif
                                </strong><br/><small>{{ $tweet[0]->tweet_updated_at }}</small>
                            </div>
                            <p>{{ $tweet[0]->tweet_text }}</p>
                            <div>
                                <a href="javascript:void(0)" class="btn btn-sm btn-default btn-hover-primary"><i class="fa fa-comment"></i> Comment</a>
                            </div>

                            {{-- comments --}}
                            @foreach ($tweet as $item => $comments)
                            <hr/>
                                @if (isset($comments->comment_uid))
                                    <div class="comments ml-5">
                                        <div class="text-muted text-sm">
                                            @if (Auth::user()->avatar === 'avatar.png')
                                                <img src="{{ URL::asset('img/profile/avatar.png') }}" alt="" class="rounded-circle img-xs">
                                            @else
                                                <img src="{{ Auth::user()->avatar }}" alt="" class="rounded-circle img-xs">
                                            @endif
                                            <strong class="text-info">
                                                @if ($comments->comment_uid === Auth::user()->user_id)
                                                    Me
                                                @else
                                                    {{ $comments->name }}
                                                @endif
                                            </strong><br/><small>{{ $comments->comment_updated_at }}</small>
                                        </div>
                                        <p>{{ $comments->comment_text }}</p>
                                        <div>
                                            <a class="btn btn-sm btn-default btn-hover-primary" href="javascript:void(0)"><i class="fa fa-reply"></i> reply</a>
                                        </div>
                                        <hr/>
                                    </div>
                                @endif
                            @endforeach
                            <form action="{{ route('tweet.comment', ['tweetId' => $tweet[0]->tweet_id]) }}" method="POST" class="form">
                                @csrf
                                <div class="form-group">
                                    <textarea class="form-control" id="comment_text" name="comment_text" rows="2" placeholder="Write a comment" required></textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-info text-white">Submit</button>
                                </div>
                            </form>
                        </div>
                        <hr>
                        @endforeach
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- The Modal -->
<div class="modal" id="tweetModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('tweet.store') }}" method="post">
                @csrf
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <textarea class="form-control" id="tweet_text" name="tweet_text" rows="3" placeholder="What's on your mind {{ Auth::user()->name }}?" required></textarea>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Post</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
