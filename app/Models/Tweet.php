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
                'tweets.updated_at as tweet_updated_at',
                'comments.comment_text',
                'comments.comment_id',
                'comments.user_id as comment_uid',
                'comments.updated_at as comments_updated_at',
            )
            ->get()
            ->groupBy('tweet_id');
    }

    public function store(int $userId, Request $request): object
    {
        return $this->create([
            'user_id' => $userId,
            "tweet_text"  => $request->input('tweet_text'),
        ]);
    }
}
