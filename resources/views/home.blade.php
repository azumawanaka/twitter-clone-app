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
                                <div class="text-muted text-sm d-flex justify-content-between">
                                    <div>
                                        <img src="{{ Auth::user()->avatar }}" alt="" class="rounded-circle img-xs">
                                        <strong class="text-info">
                                            @if ($tweet->uid === Auth::user()->user_id)
                                                Me
                                            @else
                                                {{ $tweet->name }}
                                            @endif
                                        </strong><br/><small>{{ $tweet->tweet_updated_at }}</small>
                                    </div>
                                    @if (Auth::user()->user_id === $tweet->uid)
                                        <a href="{{ route('tweet.remove', ['tweetId' => $tweet->tweet_id]) }}"
                                            class="btn btn-link"
                                            onclick="return confirm('Are you sure you want to delete this tweet?')">
                                            <i class="fa fa-times text-danger"></i>
                                        </a>
                                    @endif
                                </div>
                                <div>{{ $tweet->tweet_text }}</div>
                                <div>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-default btn-hover-primary"><i class="fa fa-comment"></i> Comment</a>
                                </div>

                                {{-- comments --}}
                                @foreach ($comments as $comment => $com)
                                    @if ($com->cTid === $tweet->tweet_id)
                                        <hr/>
                                        <div class="comments ml-5">
                                            <div class="text-muted text-sm d-flex justify-content-between">
                                                <div>
                                                    <img src="{{ Auth::user()->avatar }}" alt="" class="rounded-circle img-xs">
                                                    <strong class="text-info">
                                                    @if ($com->comment_uid === Auth::user()->user_id)
                                                        Me
                                                    @else
                                                        {{ $com->name }}
                                                    @endif
                                                    </strong><br/><small>{{ $com->cUpdatedAt }}</small>
                                                </div>
                                                @if (Auth::user()->user_id === $com->comment_uid)
                                                    <a href="{{ route('tweet.comment.remove', ['commentId' => $com->comment_id]) }}"
                                                        class="btn btn-link"
                                                        onclick="return confirm('Are you sure you want to delete this comment?')">
                                                        <i class="fa fa-times text-danger"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="mb-0">{{ $com->comment_text }}</div>
                                            <a class="btn btn-sm btn-default btn-hover-primary mb-2" href="javascript:void(0)"><i class="fa fa-reply"></i> reply</a>
                                            <div class="reply-section">
                                                <form
                                                    action="{{ route('tweet.reply', ['commentId' => $com->comment_id]) }}"
                                                    method="POST"
                                                    class="form">
                                                    @csrf
                                                    <div class="form-group">
                                                        <textarea class="form-control" id="reply_text" name="reply_text" rows="1" placeholder="Write your reply" required></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-sm btn-dark text-white">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <hr/>
                                            @foreach ($replies as $replyItem => $reply)
                                                @if ($reply->rCid === $com->comment_id)
                                                    <div class="comments ml-5">
                                                        <div class="text-muted text-sm d-flex justify-content-between">
                                                            <div>
                                                                <img src="{{ Auth::user()->avatar }}" alt="" class="rounded-circle img-xs">
                                                                <strong class="text-info">
                                                                @if ($reply->rUid === Auth::user()->user_id)
                                                                    Me
                                                                @else
                                                                    {{ $reply->name }}
                                                                @endif
                                                                </strong><br/><small>{{ $reply->reply_updated_at }}</small>
                                                            </div>
                                                            @if (Auth::user()->user_id === $reply->rUid)
                                                                <a href="{{ route('comment.reply.remove', ['replyId' => $reply->rId]) }}"
                                                                    class="btn btn-link"
                                                                    onclick="return confirm('Are you sure you want to delete this reply?')">
                                                                    <i class="fa fa-times text-danger"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                        <div class="mb-4">
                                                            <span class="text-muted">Replied to </span> <strong>{{ $com->name }}</strong><br/>
                                                            {{ $reply->reply_text }}
                                                        </div>
                                                        <hr/>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                                <form action="{{ route('tweet.comment', ['tweetId' => $tweet->tweet_id]) }}" method="POST" class="form">
                                    @csrf
                                    <div class="form-group">
                                        <textarea class="form-control" id="comment_text" name="comment_text" rows="2" placeholder="Write a comment to {{ $tweet->name }}" required></textarea>
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
