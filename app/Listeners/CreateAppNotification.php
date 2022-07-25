<?php

namespace App\Listeners;

use App\Events\AppNotification;
use App\Models\DeviceToken;
use App\Services\Action as ActionService;

class CreateAppNotification
{
    public $actionService;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->actionService = new ActionService();
    }

    /**
     * Handle the event.
     *
     * @param  AppNotification  $event
     * @return void
     */
    public function handle(AppNotification $event)
    {
        $row = $event->data;
        /**
         * Check User has device token
         */
        $DeviceToken = DeviceToken::whereUserId($row->user_id)->latest();
        if ($DeviceToken->exists()) {
            $DeviceTokenRow = $DeviceToken->select('device_token')->first();
            $token = $DeviceTokenRow->device_token;
            $data = [
                'pr_inv_id' => $row->notificationable_id,
                'notify_type' => $row->notification_type,
            ];
            $title = $row->title;
            $body = $row->message; 
            $firebaseKey = config('fcm.FCM_SERVER_KEY');
            $this->actionService->sendPushNotification($token, $data, $title, $body, $firebaseKey);
        }
        return true;
    }
}
