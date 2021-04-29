<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class Follower
 * @package App\Models
 */
class Follower extends Model
{
    protected $primaryKey = 'follower_id';

    protected $fillable = [
        'following',
        'follower',
    ];

    public function follow(int $userId, int $follower): object
    {
        return $this->create([
            'following' => $follower,
            'follower' => $userId,
        ]);
    }

    public function checkFollowedUser(int $userId)
    {
        return $this->where('following', $userId)->get();
    }

    public function unFollow(int $followerId)
    {
        $this->where('follower_id', $followerId)->delete();
    }
}
