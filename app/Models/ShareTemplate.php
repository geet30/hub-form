<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class ShareTemplate extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'template_id', 'group_id',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id', 'id');
    }

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
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {

        static::creating(function ($shareTemplate) {
            // $shareTemplate->company_id = auth()->user()->users_details->i_ref_company_id;
            
            $assined_role_id=CheckUserTypeAndGetRoleID($shareTemplate->user_id);
           
            if(!empty($assined_role_id->role_id) || !empty($assined_role_id['role_id'])){
                $shareTemplate->i_ref_user_role_id = $assined_role_id['role_id']; 
            }
        });

        /**
         * Save Assign action notification
         */
        static::created(function ($shareTemplate) {
            $input['user_id'] = $shareTemplate->user_id;
            $assigne=CheckUserTypeAndGetRoleID($shareTemplate->user_id);
            $assineRoleId=$assigne['role_id'];
            $input['i_ref_user_role_id'] = $assineRoleId;
            $input['i_ref_from_user_role_id'] = auth()->user()->users_details->i_ref_role_id;
            $input['from_user_id'] = Auth::id();
            $input['notification_type'] = Notification::TEMPLATE_SHARED;
            $input['notificationable_id'] = $shareTemplate->template_id;
            $input['notificationable_type'] = Notification::TYPE_TEMPLATE_SHARED;
            $input['title'] = "New Template Shared";
            $input['message'] = trans("notifications.template_shared", [
                "name" => Auth::user()->full_name,
                "template" => $shareTemplate->template->template_name,
            ]);
            Notification::create($input);
        });
    }
}