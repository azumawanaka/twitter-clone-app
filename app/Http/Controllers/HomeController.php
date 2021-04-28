<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\Comment;
use App\Models\Reply;
use Redirect;

class HomeController extends Controller
{

    /**
     * @var Tweet $tweetModel
     * @var Comment $commentModel
     * @var Reply $replyModel
     */
    private $tweetModel;
    private $commentModel;
    private $replyMode;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        Tweet $tweetModel,
        Comment $commentModel,
        Reply $replyModel
    ) {
        $this->middleware('auth');
        $this->tweetModel = $tweetModel;
        $this->commentModel = $commentModel;
        $this->replyModel = $replyModel;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $tweetData = $this->tweetModel->getAllTweets();
        $tweetCommentData = $this->commentModel->getAllComments();
        $tweetReplyData = $this->replyModel->getAllReply();
        return view('home', [
            'tweets' => $tweetData,
            'comments' => $tweetCommentData,
            'replies' => $tweetReplyData,
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
}
