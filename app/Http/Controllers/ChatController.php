<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;

class ChatController extends Controller
{

    /**
     * @var Chat $chatModel
     * @var User $userModel
     */
    private $chatModel;
    private $userModel;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        Chat $chatModel,
        User $userModel
    ) {
        $this->middleware('auth');
        $this->chatModel = $chatModel;
        $this->userModel = $userModel;
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $userId = auth()->user()->user_id;
        $toId = (isset($_GET['user'])) ? $_GET['user'] : '';
        $checkUser = $this->userModel->findUser($toId);
        $allUsers = $this->userModel->usersLists($userId);
        $msgLists = $this->chatModel->getChatLists();

        return view('chat', [
            'users' => $allUsers,
            'messages' => $msgLists,
            'toUserData' => $checkUser,
        ]);
    }

    public function message(Request $request)
    {
        $from = auth()->user()->user_id;
        $to = $request->input('to');
        $msg = $request->input('msg');
        $flag = false;

        $checkUser = $this->userModel->findUser($to);

        if ($checkUser) {
            $this->chatModel->storeMessage($from, $to, $msg);
            $flag = true;
        }

        return response()
            ->json($flag);
    }
}
