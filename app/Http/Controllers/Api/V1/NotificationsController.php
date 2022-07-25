<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Users;
use App\Services\Action as ActionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->actionService = new ActionService();
    }

    /**
     * user's notification list
     */
    public function index(Request $request)
    {
        try {
            if (empty($token = $request->header('Token'))) {
                $user_id = Auth::id();
            } else {
                $token = $request->header('Token');
                $tokenParts = explode(".", $token);
                $tokenHeader = base64_decode($tokenParts[0]);
                $tokenPayload = base64_decode($tokenParts[1]);
                $jwtHeader = json_decode($tokenHeader);
                $jwtPayload = json_decode($tokenPayload);
                $user_id = $jwtPayload->id;
            }
            $rows = Notification::whereUserId($user_id)->latest()->paginate();
            return $this->returnResponse(HTTP_STATUS_OK, true, trans('response.success'), ['notifications' => $rows]);
        } catch (Exception $ex) {
            return $this->returnResponse(HTTP_STATUS_SERVER_ERROR, false, $ex->getMessage());
        }
    }
    /**
     * user's notification list
     */
    public function update(Request $request, $id = null)
    {
        try {
            if ($request->has('mark_all_read') && $request->mark_all_read == true) {
                if (empty($token = $request->header('Token'))) {
                    $user_id = Auth::id();
                } else {
                    $token = $request->header('Token');
                    $tokenParts = explode(".", $token);
                    $tokenHeader = base64_decode($tokenParts[0]);
                    $tokenPayload = base64_decode($tokenParts[1]);
                    $jwtHeader = json_decode($tokenHeader);
                    $jwtPayload = json_decode($tokenPayload);
                    $user_id = $jwtPayload->id;
                }
                Notification::whereUserId($user_id)->where('status', 0)->update(['status' => true]);
            } else {
                $row = Notification::findOrFail($id);
                $row->fill(['status' => true]);
                $row->save();
            }
            return $this->returnResponse(HTTP_STATUS_OK, true, trans('response.notification_success_updated'));
        } catch (\Illuminate\Database\QueryException $ex) {
            return $this->returnResponse(HTTP_STATUS_SERVER_ERROR, false, $ex->getMessage());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return $this->returnResponse(HTTP_STATUS_SERVER_ERROR, false, $ex->getMessage());
        } catch (Exception $ex) {
            return $this->returnResponse(HTTP_STATUS_SERVER_ERROR, false, $ex->getMessage());
        }
    }

    /**
     * Send Push notification
     * @param Request $request (user_id | title | message)
     */
    public function sendPushNotification(Request $request)
    {
        try {
            $input = $request->only(['user_id', 'title', 'message', 'data']);
            if ($request->has('user_id') && $request->user_id) {
                $data = $input['data'];
                $getAssineInfo = Users::where('id', '=', $input['user_id'])->with('user_device_token')->firstOrFail();
                if (isset($getAssineInfo) && $getAssineInfo->user_device_token != null) {
                    $tokens = $getAssineInfo->user_device_token->device_token;
                    $response = $this->actionService->sendPushNotification($tokens, $data, $input['title'], $input['message'], config('fcm.FCM_SERVER_KEY'));
                    return $this->returnResponse(HTTP_STATUS_OK, true, trans('response.success'));
                }
            }
            return $this->returnResponse(HTTP_STATUS_OK, true, trans('response.user_fcm_token_not_found'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return $this->returnResponse(HTTP_STATUS_SERVER_ERROR, false, $ex->getMessage());
        } catch (Exception $ex) {
            return $this->returnResponse(HTTP_STATUS_SERVER_ERROR, false, $ex->getMessage());
        }
    }
    /**
     * Apk dashboard notification count
     */
    public function countUnread(Request $request)
    {
        try {
            $retData = [];
            if (empty($token = $request->header('Token'))) {
                $user_id = Auth::id();
            } else {
                $token = $request->header('Token');
                $tokenParts = explode(".", $token);
                $tokenHeader = base64_decode($tokenParts[0]);
                $tokenPayload = base64_decode($tokenParts[1]);
                $jwtHeader = json_decode($tokenHeader);
                $jwtPayload = json_decode($tokenPayload);
                $user_id = $jwtPayload->id;
            }

            $retData['forms'] = Notification::whereUserId($user_id)->whereIn('notificationable_type', [Notification::TYPE_COMPLETEDFORM, Notification::TYPE_TEMPLATE_SHARED])->whereStatus(false)->count();
            $retData['documents'] = Notification::whereUserId($user_id)->where('notificationable_type', Notification::TYPE_DOCUMENT)->whereStatus(false)->count();
            $retData['actions'] = Notification::whereUserId($user_id)->whereIn('notificationable_type', [Notification::TYPE_ACTION, Notification::TYPE_RECURRINGACTION])->whereStatus(false)->count();
            return $this->returnResponse(Response::HTTP_OK, true, trans('response.notification_count_unread'), $retData);
        } catch (Exception $ex) {
            return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, $ex->getMessage());
        }
    }




    public function makeread(Request $request)
    {
        try {
            if (empty($token = $request->header('Token'))) {
                $user_id = Auth::id();
            } else {
                $token = $request->header('Token');
                $tokenParts = explode(".", $token);
                $tokenHeader = base64_decode($tokenParts[0]);
                $tokenPayload = base64_decode($tokenParts[1]);
                $jwtHeader = json_decode($tokenHeader);
                $jwtPayload = json_decode($tokenPayload);
                $user_id = $jwtPayload->id;
            }
            if ($request->has('name') && $request->name == "action") {
               Notification::whereUserId($user_id)->where('status', 0)->where('notificationable_type', "App\Models\Action")->update(['status' => true]);
            }else if($request->has('name') && $request->name == "Template"){
                Notification::whereUserId($user_id)->where('status', 0)->where('notificationable_type', "App\Models\Template")->update(['status' => true]);
            }else if($request->has('name') && $request->name == "Users"){
                Notification::whereUserId($user_id)->where('status', 0)->where('notificationable_type', "App\Models\Users")->update(['status' => true]);
            }else if($request->has('name') && $request->name == "CompletedForm"){
                Notification::whereUserId($user_id)->where('status', 0)->where('notificationable_type', "App\Models\CompletedForm")->update(['status' => true]); 
            }else if($request->has('name') && $request->name == "document"){
                Notification::whereUserId($user_id)->where('status', 0)->whereIn('notificationable_type', [Notification::TYPE_COMPLETEDFORM, Notification::TYPE_TEMPLATE_SHARED])->update(['status' => true]); 
            }else{
                
            }



            return $this->returnResponse(HTTP_STATUS_OK, true, trans('response.notification_success_updated'));
        } catch (\Illuminate\Database\QueryException $ex) {
            return $this->returnResponse(HTTP_STATUS_SERVER_ERROR, false, $ex->getMessage());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return $this->returnResponse(HTTP_STATUS_SERVER_ERROR, false, $ex->getMessage());
        } catch (Exception $ex) {
            return $this->returnResponse(HTTP_STATUS_SERVER_ERROR, false, $ex->getMessage());
        }
    }
}
