<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Models\Notification as ModelNotification;
class CustomDbChannel 
{

  public function send($notifiable, Notification $notification)
  {
    $data = $notification->toArray($notifiable);
    
    $id= $notifiable->routeNotificationFor('database')->create([
        //customize here
        'id' => $notification->id,
        'user_id'=> $data['chat']['user_id'],
        "from_user_id"=>$data['chat']['from_user_id'],
        'title'=> $data['chat']['title'],
        'message'=> $data['chat']['message'],
        "notification_type"=>37,
        'i_ref_user_role_id'=>$data['chat']['i_ref_user_role_id'],
        'i_ref_from_user_role_id'=>$data['chat']['i_ref_from_user_role_id']
    ]);
    $update= ModelNotification::find($id->id);
    $update->notificationable_id=$data['chat']['action_id'];

    $update->notificationable_type="App\Models\Action";
    $update->save();
    return $id;
  }

}