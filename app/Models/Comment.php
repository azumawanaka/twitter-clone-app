<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class Comment
 * @package App\Models
 */
class Comment extends Model
{
    protected $primaryKey = 'tweet_id';

    protected $fillable = [
        'user_id',
        'tweet_id',
        'comment_text',
    ];

    public function getAllComments()
    {
        return $this
            ->leftJoin('users', 'comments.user_id', '=', 'users.user_id')
            ->leftJoin('tweets', 'tweets.tweet_id', '=', 'comments.tweet_id')
            ->select(
                'users.user_id as uid',
                'users.name',
                'users.avatar',
                'tweets.tweet_id as tId',
                'comments.tweet_id as cTid',
                'comments.comment_text',
                'comments.comment_id',
                'comments.user_id as comment_uid',
                'comments.updated_at as cUpdatedAt'
            )
            ->orderBy('cUpdatedAt', 'DESC')
            ->get();
    }

    public function store(int $userId, int $tweetId, Request $request): object
    {
        return $this->create([
            'user_id' => $userId,
            'tweet_id' => $tweetId,
            "comment_text"  => $request->input('comment_text'),
        ]);
    }

    public function updateComment(int $userId, int $commentId, Request $request)
    {
        return $this
            ->where([
                'comment_id' => $commentId,
                'user_id' => $userId
            ])
            ->update([
                "comment_text"  => $request->input('comment_text'),
            ]);
    }

    public function removeComment(int $userId, int $commentId)
    {
        $this->where(['user_id' => $userId, 'comment_id' => $commentId])->delete();
    }
}
