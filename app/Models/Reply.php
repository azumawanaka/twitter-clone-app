<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class Reply
 * @package App\Models
 */
class Reply extends Model
{
    protected $primaryKey = 'reply_id';

    protected $fillable = [
        'user_id',
        'comment_id',
        'reply_text',
    ];

    public function getAllReply(): object
    {
        return $this
            ->leftJoin('users', 'replies.user_id', '=', 'users.user_id')
            ->leftJoin('comments', 'comments.comment_id', '=', 'replies.comment_id')
            ->select(
                'users.name',
                'users.avatar',
                'comments.user_id as cUid',
                'replies.reply_id as rId',
                'replies.user_id as rUid',
                'replies.reply_text',
                'replies.comment_id as rCid',
                'replies.updated_at as reply_updated_at'
            )
            ->get();
    }

    public function reply(int $userId, int $commentId, Request $request)
    {
        return $this->create([
            'user_id' => $userId,
            'comment_id' => $commentId,
            "reply_text"  => $request->input('reply_text'),
        ]);
    }

    public function updateReply(int $replyId, Request $request)
    {
        return $this
            ->where('reply_id', $replyId)
            ->update([
                "reply_text"  => $request->input('reply_text'),
            ]);
    }

    public function removeReply(int $userId, int $replyId)
    {
        $this->where(['user_id' => $userId, 'reply_id' => $replyId])->delete();
    }
}
