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
                                        <img src="{{ Auth::user()->avatar }}" alt="" class="rounded-circle img-sm">
                                        <strong>
                                            @if ($tweet->uid === Auth::user()->user_id)
                                                Me
                                            @else
                                                <a href="javascript:void(0)" class="btn-link text-info" data-toggle="modal" data-target="#followModal-{{ $tweet->uid }}">{{ $tweet->name }}</a>
                                            <!-- Follow Modal -->
                                            <div class="modal" id="followModal-{{ $tweet->uid }}">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <!-- Modal footer -->
                                                        <div class="modal-footer">
                                                            <div class="col-12">
                                                                @if ($tweet->follower === null)
                                                                    <p>Want to follow {{ $tweet->name }}?</p>
                                                                    <a href="{{ route('tweet.user.follow', ['userId' => $tweet->uid]) }}" class="btn btn-block btn-info text-white">Follow</a>
                                                                @else
                                                                    @if ($tweet->follower === Auth::user()->user_id || $tweet->following === $tweet->uid)
                                                                        <p>You followed {{ $tweet->name }}.</p>
                                                                        <a href="{{ route('tweet.user.unFollow', ['followerId' => $tweet->follower_id, 'userId' => $tweet->uid]) }}" class="btn btn-block btn-warning">Unfollow</a>
                                                                    @else
                                                                        <p>Want to follow {{ $tweet->name }}?</p>
                                                                        <a href="{{ route('tweet.user.follow', ['userId' => $tweet->uid]) }}" class="btn btn-block btn-info text-white">Follow</a>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </strong><br/><small>{{ \Carbon\Carbon::parse($tweet->tweet_updated_at)->diffForHumans() }}</small>
                                    </div>
                                    @if (Auth::user()->user_id === $tweet->uid)
                                        <div>
                                            <a href="javascript:void(0)"
                                                class="btn btn-sm btn-link"
                                                data-toggle="modal"
                                                data-target="#editModal-{{ $tweet->tweet_id }}">
                                                <small><i class="fa fa-edit"></i> edit</small>
                                            </a>
                                            <a href="{{ route('tweet.remove', ['tweetId' => $tweet->tweet_id]) }}"
                                                class="btn btn-link text-danger"
                                                onclick="return confirm('Are you sure you want to delete this tweet?')">
                                                <small><i class="fa fa-times"></i> remove</small>
                                            </a>
                                        </div>

                                        <!-- Edit Modal -->
                                        <div class="modal" id="editModal-{{ $tweet->tweet_id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                        
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <form action="{{ route('tweet.update', ['tweetId' => $tweet->tweet_id]) }}" method="post">
                                                        @csrf
                                                        <!-- Modal body -->
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <textarea class="form-control" name="tweet_text" rows="3" placeholder="What's on your mind {{ Auth::user()->name }}?" required>{{ $tweet->tweet_text }}</textarea>
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
                                                    <strong>
                                                    @if ($com->comment_uid === Auth::user()->user_id)
                                                        Me
                                                    @else
                                                        <span class="text-info">{{ $com->name }}</span>
                                                    @endif
                                                    </strong><br/><small>{{ \Carbon\Carbon::parse($com->cUpdatedAt)->diffForHumans() }}</small>
                                                </div>
                                                @if (Auth::user()->user_id === $com->comment_uid)
                                                    <div>
                                                        <a href="javascript:void(0)"
                                                            class="btn btn-sm btn-link"
                                                            data-toggle="modal"
                                                            data-target="#editCommentModal-{{ $com->comment_id }}">
                                                            <small><i class="fa fa-edit"></i> edit</small>
                                                        </a>
                                                        <a href="{{ route('tweet.comment.remove', ['commentId' => $com->comment_id]) }}"
                                                            class="btn btn-link"
                                                            onclick="return confirm('Are you sure you want to delete this comment?')">
                                                            <i class="fa fa-times text-danger"></i>
                                                        </a>
                                                    </div>

                                                    <!-- Edit Comment Modal -->
                                                    <div class="modal" id="editCommentModal-{{ $com->comment_id }}">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                    
                                                                <!-- Modal Header -->
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>
                                                                <form action="{{ route('tweet.comment.update', ['commentId' => $com->comment_id]) }}" method="post">
                                                                    @csrf
                                                                    <!-- Modal body -->
                                                                    <div class="modal-body">
                                                                        <div class="form-group">
                                                                            <textarea class="form-control" name="comment_text" rows="3" placeholder="Write your reply here.." required>{{ $com->comment_text }}</textarea>
                                                                        </div>
                                                                    </div>
                                                    
                                                                    <!-- Modal footer -->
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-success">Submit</button>
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
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
                                                        <textarea class="form-control" id="reply_text" name="reply_text" rows="1" placeholder="Write your reply here.." required></textarea>
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
                                                                <strong>
                                                                @if ($reply->rUid === Auth::user()->user_id)
                                                                    Me
                                                                @else
                                                                    <span class="text-info">{{ $reply->name }}</span>
                                                                @endif
                                                                </strong><br/><small>{{ \Carbon\Carbon::parse($reply->reply_updated_at)->diffForHumans() }}</small>
                                                            </div>
                                                            @if (Auth::user()->user_id === $reply->rUid)
                                                                <div>
                                                                    <a href="javascript:void(0)"
                                                                        class="btn btn-sm btn-link"
                                                                        data-toggle="modal"
                                                                        data-target="#editReplyModal-{{ $reply->rId }}">
                                                                        <small><i class="fa fa-edit"></i> edit</small>
                                                                    </a>
                                                                    <a href="{{ route('comment.reply.remove', ['replyId' => $reply->rId]) }}"
                                                                        class="btn btn-link"
                                                                        onclick="return confirm('Are you sure you want to delete this reply?')">
                                                                        <i class="fa fa-times text-danger"></i>
                                                                    </a>
                                                                </div>

                                                                <!-- Edit Reply Modal -->
                                                                <div class="modal" id="editReplyModal-{{ $reply->rId }}">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                
                                                                            <!-- Modal Header -->
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            </div>
                                                                            <form action="{{ route('tweet.reply.update', ['replyId' => $reply->rId]) }}" method="post">
                                                                                @csrf
                                                                                <!-- Modal body -->
                                                                                <div class="modal-body">
                                                                                    <div class="form-group">
                                                                                        <textarea class="form-control" name="reply_text" rows="3" placeholder="Write your reply here.." required>{{ $reply->reply_text }}</textarea>
                                                                                    </div>
                                                                                </div>
                                                                
                                                                                <!-- Modal footer -->
                                                                                <div class="modal-footer">
                                                                                    <button type="submit" class="btn btn-success">Submit</button>
                                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
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

<!-- Create Modal -->
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
                        <textarea class="form-control" name="tweet_text" rows="3" placeholder="What's on your mind {{ Auth::user()->name }}?" required></textarea>
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
