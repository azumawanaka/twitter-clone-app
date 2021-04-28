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

    public function getAllTweets(): object
    {
        return $this
            ->leftJoin('users', 'tweets.user_id', '=', 'users.user_id')
            ->leftJoin('comments', 'comments.tweet_id', '=', 'tweets.tweet_id')
            ->select(
                'users.user_id as uid',
                'users.name',
                'users.avatar',
                'tweets.tweet_id',
                'tweets.tweet_text',
                'tweets.updated_at as tweet_updated_at'
            )
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

    public function removeTweet(int $userId, int $tweetId)
    {
        $this->where(['user_id' => $userId, 'tweet_id' => $tweetId])->delete();
    }
}
