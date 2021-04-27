<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\Comment;
use Redirect;

class HomeController extends Controller
{

    /**
     * @var Tweet $tweetModel
     * @var Comment $commentModel
     */
    private $tweetModel;
    private $commentModel;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        Tweet $tweetModel,
        Comment $commentModel
    ) {
        $this->middleware('auth');
        $this->tweetModel = $tweetModel;
        $this->commentModel = $commentModel;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $tweetData = $this->tweetModel->getAllTweets();
        return view('home', [
            'tweets' => $tweetData,
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
}
