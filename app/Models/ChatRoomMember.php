<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRoomMember extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'chat_room_id', 'action_id', 'member_id', 'joined_at', 'status', 'is_active', 'is_deleted',
    ];
}