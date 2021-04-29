<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class Tweet
 * @package App\Models
 */
class Tweet extends Model
{
    protected $primaryKey = 'tweet_id';

    protected $fillable = [
        'user_id',
        'tweet_text',
    ];

    public function getAllTweets($freeWord = ""): object
    {
        return $this
            ->leftJoin('users', 'tweets.user_id', '=', 'users.user_id')
            ->leftJoin('comments', 'comments.tweet_id', '=', 'tweets.tweet_id')
            ->leftJoin('followers', 'followers.following', '=', 'tweets.user_id')
            ->select(
                'users.user_id as uid',
                'users.name',
                'users.avatar',
                'tweets.tweet_id',
                'tweets.tweet_text',
                'tweets.updated_at as tweet_updated_at',
                'followers.following',
                'followers.follower',
                'followers.follower_id'
            )
            ->where('tweet_text', 'like', '%' . $freeWord . '%')
            ->orderBy('tweet_updated_at', 'DESC')
            ->get();
    }

    public function store(int $userId, Request $request): object
    {
        return $this->create([
            'user_id' => $userId,
            "tweet_text"  => $request->input('tweet_text'),
        ]);
    }

    public function updateTweet(int $userId, int $tweetId, Request $request)
    {
        return $this
            ->where([
                'tweet_id' => $tweetId,
                'user_id' => $userId
            ])
            ->update([
                "tweet_text"  => $request->input('tweet_text'),
            ]);
    }

    public function removeTweet(int $userId, int $tweetId)
    {
        $this->where([
            'user_id' => $userId,
            'tweet_id' => $tweetId
        ])
        ->delete();
    }
}
