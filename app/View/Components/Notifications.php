<?php

namespace App\View\Components;

use App\Models\Notification;
use Auth;
use Illuminate\View\Component;

class Notifications extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        // dd(auth()->user()->user_type);

        $notifications = Notification::with('notificationable')->select('id', 'title','user_id', 'message', 'notificationable_id', 'notificationable_type', 'notification_type', 'status', 'created_at','updated_at');
        if(auth()->user()->user_type == "employee"){
        $notifications=$notifications->
                    where('i_ref_user_role_id',auth()->user()->users_details->i_ref_role_id)
                    ->where('status', 0)->latest()
                    ->limit(15)->get();
        }else if(auth()->user()->user_type == "company"){
            $notifications=$notifications->where('i_ref_user_role_id',
            auth()->user()->users_details->i_ref_role_id)
            ->where('i_ref_user_role_id',
            "!=",null)
            ->where('status', 0)->latest()
            ->limit(15)->get();
        }else{
            $notifications=$notifications->whereUserId(Auth::id())
            ->where('status', 0)->latest()
            ->limit(15)->get();
        }
        // dd($notifications);
        // dd($notifications);
        // dd($notifications[0]->notificationable);
        // die;

        return view('components.notifications', compact('notifications'));
    }
}