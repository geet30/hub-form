<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_admin_id', 'type_id', 'group_title', 'members_count', 'image', 'image_path', 'type', 'chat_status', 'is_deleted', 'type_model', 'question_id',
    ];
    /**
     * Get the chat room members for the chat room.
     */
    public function chat_room_members()
    {
        return $this->hasMany(ChatRoomMember::class, 'chat_room_id', 'id');
    }
}
