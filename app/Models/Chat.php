<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class Chat
 * @package App\Models
 */
class Chat extends Model
{
    protected $primaryKey = 'chat_id';

    protected $fillable = [
        'user_id',
        'to',
        'msg',
    ];

    public function getChatLists()
    {
        return $this
            ->leftJoin('users', 'users.user_id', '=', 'chats.user_id')
            ->orderBy('chats.updated_at', 'ASC')
            ->get();
    }

    public function storeMessage(int $from, int $to, string $msg)
    {
        return $this->create([
            'user_id' => $from,
            'to' => $to,
            "msg"  => $msg,
        ]);
    }
}
