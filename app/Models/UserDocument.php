<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'document_id', 'is_opened',
    ];
    
    
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    /**
     * Get the notifications.
     */
    public function notifications()
    {
        return $this->morphOne(Notification::class, 'notificationable');
    }
    
    // /**
    //  * The "booted" method of the model.
    //  *
    //  * @return void
    //  */
    // protected static function booted()
    // {
    //     /**
    //      * Save Assign action notification
    //      */
    //     static::created(function ($document) {
    //         $input['user_id'] = $document->user_id;
    //         $input['from_user_id'] = Auth::id();
    //         $input['notification_type'] = 30;
    //         $input['title'] = "New Document Shared";
    //         $input['message'] = sprintf("%s has shared document_name with you.", Auth::user()->full_name);
    //         $document->notifications()->create($input);
    //     });
    // }
}