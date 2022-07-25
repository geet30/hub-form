<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use App\Events\AppNotification;
use Illuminate\Database\Eloquent\Model;


class Notification extends Model
{
    protected $connection = 'mysql';

    /**
     * Notification Type constant
     */
    const CREATE_ACTION = 30;
    const CLOSE_ACTION = 31;
    const TEMPLATE_SHARED = 32;
    const COMPLETED_FORM = 33;
    const DOCUMENT = 34;
    const SUPPLIER_APPROVED = 35;
    const UPDATE_ACTION = 36;
    // const MESSAAGE_SEND = 37;
    // const MESSAAGE_SEND_file = 38;
    // const MESSAAGE_SEND_with_file = 39;
    const NEW_MESSAGE_ARRIVED=37;
    /**
     * Notification Type Model constant
     */
    const TYPE_ACTION = "App\Models\Action";
    const TYPE_RECURRINGACTION = "App\Models\RecurringAction";
    const TYPE_COMPLETEDFORM = "App\Models\CompletedForm";
    const TYPE_DOCUMENT = "App\Models\Document";
    const TYPE_TEMPLATE_SHARED = "App\Models\Template";
    const TYPE_SUPPLIER_APPROVED = "App\Models\Users";
    const TYPE_NEW_MESSAGE_ARRIVED = "App\Models\Action";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'from_user_id', 'title', 'message', 'notification_type', 'status', 'sent_at', 'notificationable_id', 'notificationable_type','i_ref_user_role_id','i_ref_from_user_role_id','company_id'
    ];
    /**
     * Get the message.
     *
     * @param  string  $value
     * @return string
     */
    public function setMessageAttribute($value)
    {
        $this->attributes['message'] = ucfirst($value);
    }
    /**
     * Get the parent notificationable model.
     */
    public function notificationable()
    {
        return $this->morphTo();
    }
    /**
     * Get the parent notificationable model.
     */
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'i_ref_user_role_id', 'id');
    }

    /**
     * get notification url
     */
    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function getUrlAttribute($value)
    {
        $url = "#";
        switch ($this->notificationable_type) {
            case (Notification::TYPE_ACTION):
                // $url = route("actions.view", encrypt_decrypt('encrypt', $this->notificationable_id));
                $url = route("edit_action", encrypt_decrypt('encrypt', $this->notificationable_id));
                break;
            case (Notification::TYPE_RECURRINGACTION):
                $url = route("edit_action", encrypt_decrypt('encrypt', $this->notificationable->action_id));
                break;
            case (Notification::TYPE_TEMPLATE_SHARED):
                $url = route("edit-template", encrypt_decrypt('encrypt', $this->notificationable_id));
                break;
            case (Notification::TYPE_COMPLETEDFORM):
                $url = route("edit_form", encrypt_decrypt('encrypt', $this->notificationable_id));
                break;
            case (Notification::TYPE_SUPPLIER_APPROVED):
                $url = route("suppliers.edit", encrypt_decrypt('encrypt', $this->notificationable_id));
                break;
            case (Notification::TYPE_NEW_MESSAGE_ARRIVED):
                $url = route("edit_action", encrypt_decrypt('encrypt', $this->notificationable_id));
                break;
            default:
                $url = $url;
                break;
        }
        return $url;
    }

    /**
     * Get the created at.
     *
     * @param  string  $value
     * @return string
     */
    public function getCreatedAttribute()
    {
        $timeZone = $_COOKIE['user_timezone'];
        return $this->created_at->setTimezone($timeZone);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        /**
         * Save Assign action notification
         */
        $i_ref_company_id=auth()->user()->users_details->i_ref_company_id;
        
        static::addGlobalScope('ancient', function (Builder  $builder) use ($i_ref_company_id){
            $builder->where('notifications.company_id', $i_ref_company_id);
        });

        static::creating(function ($notification) {
            $notification->company_id=auth()->user()->users_details->i_ref_company_id;
        });

        static::created(function ($notification) {
            if($notification->notification_type!=37){
                event(new AppNotification($notification));
            }
        });
    }
}
