<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\Comment;
use App\Models\Reply;
use App\Models\Follower;
use App\Models\User;
use Redirect;

class HomeController extends Controller
{

    /**
     * @var Tweet $tweetModel
     * @var Comment $commentModel
     * @var Reply $replyModel
     * @var Follower $followerModel
     */
    private $tweetModel;
    private $commentModel;
    private $replyMode;
    private $followerModel;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        Tweet $tweetModel,
        Comment $commentModel,
        Reply $replyModel,
        Follower $followerModel
    ) {
        $this->middleware('auth');
        $this->tweetModel = $tweetModel;
        $this->commentModel = $commentModel;
        $this->replyModel = $replyModel;
        $this->followerModel = $followerModel;
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $userId = auth()->user()->user_id;
        $freeWord = $request->input('free_word') ?? '';
        $tweetData = $this->tweetModel->getAllTweets($freeWord);
        $tweetCommentData = $this->commentModel->getAllComments();
        $tweetReplyData = $this->replyModel->getAllReply();
        $checkFollower = $this->followerModel->checkFollowedUser($userId);

        return view('home', [
            'tweets' => $tweetData,
            'comments' => $tweetCommentData,
            'replies' => $tweetReplyData,
            'follower' => $checkFollower,
        ]);
    }

    public function store(Request $request)
    {
        $userId = auth()->user()->user_id;
        $storeTweet = $this->tweetModel->store($userId, $request);
        if($storeTweet) {
            $msg = array("type" => "success", "title" => "Success!", "msg" => "Tweet was successfully posted!");
        }else{
            $msg = array("type" => "danger", "title" => "Error!", "msg" => "Something went wrong. Please try again later.");
        }

        return Redirect::back()->with('message', $msg);
    }

    public function updateTweet(int $tweetId, Request $request)
    {
        $userId = auth()->user()->user_id;
        $updateTweet = $this->tweetModel->updateTweet($userId, $tweetId, $request);
        if($updateTweet) {
            $msg = array("type" => "success", "title" => "Success!", "msg" => "Tweet was successfully updated!");
        }else{
            $msg = array("type" => "danger", "title" => "Error!", "msg" => "Something went wrong. Please try again later.");
        }

        return Redirect::back()->with('message', $msg);
    }

    public function removeTweet(int $tweetId)
    {
        $userId = auth()->user()->user_id;
        $removeTweet = $this->tweetModel->removeTweet($userId, $tweetId);
        if($removeTweet) {
            $msg = array("type" => "success", "title" => "Success!", "msg" => "Tweet was successfully removed!");
        }else{
            $msg = array("type" => "danger", "title" => "Error!", "msg" => "Something went wrong. Please try again later.");
        }

        return Redirect::back()->with('message', $msg);
    }

    public function comment(int $tweetId, Request $request)
    {
        $userId = auth()->user()->user_id;
        $storeComment = $this->commentModel->store($userId, $tweetId, $request);

        if($storeComment) {
            $msg = array("type" => "success", "title" => "Success!", "msg" => "Comment was successfull!");
        }else{
            $msg = array("type" => "danger", "title" => "Error!", "msg" => "Something went wrong. Please try again later.");
        }

        return Redirect::back()->with('message', $msg);
    }

    public function updateComment(int $commentId, Request $request)
    {
        $userId = auth()->user()->user_id;
        $updateComment = $this->commentModel->updateComment($userId, $commentId, $request);
        if($updateComment) {
            $msg = array("type" => "success", "title" => "Success!", "msg" => "Tweet was successfully updated!");
        }else{
            $msg = array("type" => "danger", "title" => "Error!", "msg" => "Something went wrong. Please try again later.");
        }

        return Redirect::back()->with('message', $msg);
    }

    public function removeComment(int $commentId)
    {
        $userId = auth()->user()->user_id;
        $removeComment = $this->commentModel->removeComment($userId, $commentId);
        if($removeComment) {
            $msg = array("type" => "success", "title" => "Success!", "msg" => "Comment was successfully removed!");
        }else{
            $msg = array("type" => "danger", "title" => "Error!", "msg" => "Something went wrong. Please try again later.");
        }

        return Redirect::back()->with('message', $msg);
    }

    public function reply(int $commentId, Request $request)
    {
        $userId = auth()->user()->user_id;
        $storeReply = $this->replyModel->reply($userId, $commentId, $request);
        if($storeReply) {
            $msg = array("type" => "success", "title" => "Success!", "msg" => "Reply was successfull!");
        }else{
            $msg = array("type" => "danger", "title" => "Error!", "msg" => "Something went wrong. Please try again later.");
        }

        return Redirect::back()->with('message', $msg);
    }

    public function updateReply(int $replyId, Request $request)
    {
        $updateReply = $this->replyModel->updateReply($replyId, $request);
        if($updateReply) {
            $msg = array("type" => "success", "title" => "Success!", "msg" => "Reply was successfully updated!");
        }else{
            $msg = array("type" => "danger", "title" => "Error!", "msg" => "Something went wrong. Please try again later.");
        }

        return Redirect::back()->with('message', $msg);
    }

    public function removeReply(int $replyId)
    {
        $userId = auth()->user()->user_id;
        $removeReply = $this->replyModel->removeReply($userId, $replyId);
        if($removeReply) {
            $msg = array("type" => "success", "title" => "Success!", "msg" => "Reply was successfully removed!");
        }else{
            $msg = array("type" => "danger", "title" => "Error!", "msg" => "Something went wrong. Please try again later.");
        }

        return Redirect::back()->with('message', $msg);
    }

    public function follow(int $follower)
    {
        $userId = auth()->user()->user_id;
        $userName = User::where('user_id', $follower)->first()->name;
        $followToUser = $this->followerModel->follow($userId, $follower);
        if($followToUser) {
            $msg = array("type" => "success", "title" => "Success!", "msg" => "You successfully followed ".$userName."!");
        }else{
            $msg = array("type" => "danger", "title" => "Error!", "msg" => "Something went wrong. Please try again later.");
        }

        return Redirect::back()->with('message', $msg);
    }

    public function unFollow(int $userId, int $followerId)
    {
        $unFollowToUser = $this->followerModel->unFollow($followerId);
        $userName = User::where('user_id', $userId)->first()->name;
        if(!$unFollowToUser) {
            $msg = array("type" => "success", "title" => "Success!", "msg" => "You successfully unfollowed ".$userName."!");
        }else{
            $msg = array("type" => "danger", "title" => "Error!", "msg" => "Something went wrong. Please try again later.");
        }

        return Redirect::back()->with('message', $msg);
    }
}
