<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $active = "notification";
        $notifications = Notification::with('notificationable')->select('id', 'user_id', 'message', 'notificationable_id', 'notificationable_type', 'notification_type', 'status', 'created_at','updated_at');  
        if(auth()->user()->user_type == "employee"){
            $notifications=$notifications->where('i_ref_user_role_id',auth()->user()->users_details->i_ref_user_id)
                        ->where('status', 0)->latest()
                        ->limit(15)->get();
            }elseif(auth()->user()->user_type == "company"){
                $notifications=$notifications->where('i_ref_user_role_id',auth()->user()->users_details->i_ref_user_id)
                ->where('status', 0)->latest()
                ->limit(15)->get();
            }else{
                $notifications=$notifications->whereUserId(Auth::id())
                ->where('status', 0)->latest()
                ->limit(15)->get();
            }
        // dd($notifications);
        return view('admin.notifications.index', compact('notifications', 'active'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $row = Notification::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            $row->status = true;
            $row->save();
            return $this->returnResponse(HTTP_STATUS_OK, true, "Done.");
        } catch (\Illuminate\Database\QueryException $ex) {
            return $this->returnResponse(HTTP_STATUS_SERVER_ERROR, false, $ex->getMessage());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return $this->returnResponse(HTTP_STATUS_SERVER_ERROR, false, $ex->getMessage());
        }
    }

    public function get_chat_notification(Request $request){
        $notifications = Notification::with('notificationable')->select('id', 'title','user_id', 'message', 'notificationable_id', 'notificationable_type', 'notification_type', 'status', 'created_at','updated_at')->where('id', $request->notification_id)->get();
       
        foreach ($notifications as $notificationrow){
              
            $html='<li><a class="mark-as-read chatnotification" onclick="return false" data-id="'.$notificationrow->id.'" href="'.$notificationrow->url.'"><span class="time">'.$notificationrow->created_at->diffForHumans().'</span><span class="details"><span class="label label-sm label-icon label-info"><i class="fa fa-bullhorn"></i></span>'. $notificationrow->title.' <br>'. $notificationrow->message .'</span></a></li>';
        }

        return ($html);
    }


}
