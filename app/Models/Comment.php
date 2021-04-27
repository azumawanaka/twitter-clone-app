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

    public function store(int $userId, int $tweetId, Request $request): object
    {
        return $this->create([
            'user_id' => $userId,
            'tweet_id' => $tweetId,
            "comment_text"  => $request->input('comment_text'),
        ]);
    }
}
