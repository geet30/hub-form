<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use App\User;
use App\Models\Role;
use App\Models\Users;
use App\Models\Action;
use App\Models\UserDetail;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Services\P2B as P2BService;
use App\Http\Controllers\Controller;
use App\Notifications\ChatNotification;
use App\Services\Action as ActionService;
use App\Http\Controllers\ActionsController as ControllerActions;
use Illuminate\Support\Facades\Notification as FacadeNotification;

class ActionsController extends Controller
{
    public function __construct()
    {
        $this->actionService = new ActionService();
        $this->p2bService = new P2BService();
        // $this->ControllerActions = new ControllerActions();
    }

    public function index(Request $request, $status = null)
    {
        try {
            // $user = auth()->user();
            // dd($user);
            $data = [];

            if ($request->sender_id != null) {

                $user = Users::with(['users_details'])->find($request->sender_id);
                $company_id = $user->users_details->i_ref_company_id;
                if ($user->user_type != "supplier") {
                    // by role id
                    $data = $this->actionService->getListingByRole($user->users_details->i_ref_role_id, $status, $request);
                } else {
                    // by user id
                    $data = $this->actionService->getListing($request->sender_id, $status, $request);
                }
                foreach ($data as $key => $uservalue) {
                    // dd($key);

                    $uservalue->assignee_user = CheckUserType($uservalue->i_ref_assined_role_id, $uservalue->assined_user_id);
                    $uservalue->user = $user;
                }

                // action that current users created
                // action assiggned to another
            } else {
                // dd($request->reciver_id);
                $user = Users::with(['users_details'])->find($request->reciver_id);
                // dd($user);
                $company_id = $user->users_details->i_ref_company_id;
                if ($user->user_type != "supplier") {
                    // by role id
                    $data = $this->actionService->actionsAssignedToCurrntUserByRole($user->users_details->i_ref_role_id, $status, $request);
                } else {
                    // by user id
                    $data = $this->actionService->actionsAssignedToCurrntUser($request->reciver_id, $status, $request);
                }

                foreach ($data as $key => $uservalue) {
                    // dd($key);
                    $uservalue->assignee_user = $user;
                    $uservalue->user = CheckUserType($uservalue->i_ref_user_role_id, $uservalue->user_id);
                }
                // action that are assigned to current user
                // my action that assign to us      
            }

            $status = HTTP_STATUS_OK;
            $message = HTTP_SUCCESS;
            if (empty($data)) {
                $status = HTTP_STATUS_NOT_FOUND;
                $data = 'Actions not found';
            }
            return response()->json([
                'status' => $status,
                'data' => $data,
            ], $status);
        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
            ], HTTP_STATUS_SERVER_ERROR);
        }
    }


    public function show(Request $request, $id = null)
    {
        try {
            // $user = auth()->user();
            // dd($user);
            // $userId=$this->getUserIdFromToken($request);
            // $user=Users::with(['users_details'])->find($userId);
            // $company_id=$user->users_details->i_ref_company_id;
            // dd("asd");
            $data = [];
            $data = $this->actionService->getDetail($id);

            // by role id or user id
            $assined_user = CheckUserType($data->i_ref_assined_role_id, $data->assined_user_id);

            $uservalue = CheckUserType($data->i_ref_user_role_id, $data->user_id);
            if (!empty($data->close_by)) {
                $Closeuservalue = CheckUserType($data->i_ref_closed_by_role_id, $data->closed_by);
                $data->close_by = $Closeuservalue;
            }

            $data->user = $uservalue;
            $data->assignee_user = $assined_user;



            if (!empty($data)) {
                $location = $this->p2bService->getLocation($data->location_id);
                $data['location'] = (!empty($location) ? $location : null);
            }
            $status = HTTP_STATUS_OK;
            $message = HTTP_SUCCESS;
            if (empty($data)) {
                $status = HTTP_STATUS_NOT_FOUND;
                $data = 'Action not found';
            }
            return response()->json([
                'status' => $status,
                'data' => $data,
            ], $status);
        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
            ], HTTP_STATUS_SERVER_ERROR);
        }
    }

    public function add_action(Request $request)
    {
        try {
            $data = [];
            $data = $this->actionService->add_action($request);

            $controller = new ControllerActions;

            if (!empty($data) && $data['message'] == 'saved' && !empty($data['action_id'])) {
                // $assigneInfo = $this->p2bService->getUser($data['assined_user_id']);
                $assigneInfo = CheckUserType($data['i_ref_assined_role_id'], $data['assined_user_id']);

                if ($assigneInfo != "") {
                    $messageText = 'New Action is created.';
                    date_default_timezone_set('Asia/Kolkata');
                    $currentDateTime = strtotime(now()) * 1000;
                    // $firebasedata =  $controller->updateActionInFireStore((int) $data['action_id'], $data['assined_user_id'], $messageText, $currentDateTime);
                    $assineRoleId = $assigneInfo['role_id'];
                    $assineName = $assigneInfo['full_name'];
                    $firebasedata =  $controller->updateActionInFireStore((int) $data['action_id'], $data['assined_user_id'], $assineRoleId, $assineName, $messageText, $currentDateTime);
                }
                $status = HTTP_STATUS_OK;
                $message = HTTP_SUCCESS;
                $data = 'Action saved successfully!';
            } else {
                $status = HTTP_STATUS_NOT_FOUND;
                $data = 'Action not saved!';
            }

            return response()->json([
                'status' => $status,
                'data' => $data,
            ], $status);
        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
            ], HTTP_STATUS_SERVER_ERROR);
        }
    }

    public function close_action(Request $request)
    {
        try {
            $data = [];
            $data = $this->actionService->close_action($request);

            $controller = new ControllerActions;
            if (!empty($data) && $data == 'saved') {
                $getAction = Action::find($request->actions_id);
                if ($getAction != "") {
                    // $assigneInfo = $this->p2bService->getUser($getAction->assined_user_id);
                    $assigneInfo = CheckUserType($getAction->i_ref_assined_role_id, $getAction->assined_user_id);

                    if ($assigneInfo != "") {
                        $messageText = 'Action is completed.';
                        date_default_timezone_set('Asia/Kolkata');
                        $currentDateTime = strtotime(now()) * 1000;
                        $assineRoleId = $assigneInfo['role_id'];
                        $assineName = $assigneInfo['full_name'];
                        $firebasedata =  $controller->updateActionInFireStore((int) $request->action_id, $getAction->assined_user_id, $assineRoleId, $assineName, $messageText, $currentDateTime);
                    }
                }
                $status = HTTP_STATUS_OK;
                $message = HTTP_SUCCESS;
                $data = 'Action Completed successfully!';
            } elseif (!empty($data) && $data == 'id_required') {
                $status = HTTP_STATUS_NOT_FOUND;
                $data = 'Action doesnot exist!';
            } else {
                $status = HTTP_STATUS_NOT_FOUND;
                $data = 'Action not closed!';
            }

            return response()->json([
                'status' => $status,
                'data' => $data,
            ], $status);
        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
            ], HTTP_STATUS_SERVER_ERROR);
        }
    }

    public function change_action_status(Request $request)
    {
        try {
            $data = [];
            $data = $this->actionService->change_action_status($request);

            $controller = new ControllerActions;
            if (!empty($data) && $data == 'saved') {
                $getAction = Action::find($request->action_id);
                if ($getAction != "") {
                    // $assigneInfo = $this->p2bService->getUser($getAction->assined_user_id);
                    $assigneInfo = CheckUserType($getAction->i_ref_assined_role_id, $getAction->assined_user_id);

                    if ($assigneInfo != "") {
                        if ($request->status == 5) {
                            $messageText = 'Action is rejected.';
                        } elseif ($request->status == 2) {
                            $messageText = 'Action is accepted.';
                        } elseif ($request->status == 3) {
                            $messageText = 'Action is completed.';
                        } elseif ($request->status == 6) {
                            $messageText = 'Action is overdue.';
                        } else {
                            $messageText = 'Action is updated.';
                        }
                        date_default_timezone_set('Asia/Kolkata');
                        $currentDateTime = strtotime(now()) * 1000;
                        // $firebasedata =  $controller->updateActionInFireStore($request->action_id, $getAction->assined_user_id, $messageText, $currentDateTime);
                        $assineRoleId = $assigneInfo['role_id'];
                        $assineName = $assigneInfo['full_name'];
                        $controller->updateActionInFireStore((int) $request->action_id, $getAction->assined_user_id, $assineRoleId, $assineName, $messageText, $currentDateTime);
                    }
                }
                $status = HTTP_STATUS_OK;
                $message = HTTP_SUCCESS;
                $data = 'Status changed successfully!';
            } elseif (!empty($data) && $data == 'comment_required') {
                $status = HTTP_STATUS_NOT_FOUND;
                $data = 'Comment is required!';
            } else {
                $status = HTTP_STATUS_NOT_FOUND;
                $data = 'Status not changed!';
            }

            return response()->json([
                'status' => $status,
                'data' => $data,
            ], $status);
        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
            ], HTTP_STATUS_SERVER_ERROR);
        }
    }

    public function upcoming_actions_and_templates(Request $request)
    {
        try {

            $data = [];
            if (auth()->user()->user_type != "supplier") {
                $roleId = auth()->user()->users_details->i_ref_role_id;
                $data = $this->actionService->upcoming_actions_and_templates_By_Role($request, $roleId);
            } else {
                $data = $this->actionService->upcoming_actions_and_templates($request);
            }


            foreach ($data['actions'] as $key => $uservalue) {
                // dd($key);
                $uservalue->user = CheckUserType($uservalue->i_ref_user_role_id, $uservalue->user_id);
                $uservalue->assignee_user = auth()->user();
            }
            if (!empty($data)) {
                $status = HTTP_STATUS_OK;
                $message = HTTP_SUCCESS;
                $data = $data;
            } else {
                $status = HTTP_STATUS_NOT_FOUND;
                $data = 'No Upcomings!';
            }

            return response()->json([
                'status' => $status,
                'data' => $data,
            ], $status);
        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
            ], HTTP_STATUS_SERVER_ERROR);
        }
    }

    public function assign_action($id = null)
    {
        try {
            $data = [];
            $findAction = Action::find($id);
            if ($findAction) {
                $data['pr_inv_id '] = $findAction->id;
                $data['notification_text'] = $findAction->title;
                $data['notify_type'] = '30';
                $status = HTTP_STATUS_OK;
                $message = HTTP_SUCCESS;
                if (empty($data)) {
                    $status = HTTP_STATUS_NOT_FOUND;
                    $data = 'Actions not found';
                }
                return response()->json([
                    'response' => true,
                    'message' => 'Action Assigned',
                    'status' => $status,
                    'data' => $data,
                ], $status);
            }
        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
            ], HTTP_STATUS_SERVER_ERROR);
        }
    }

    public function create_assign_action_notification(Request $request)
    {
        try {
            $data = [];
            $body = $request->description;
            $title = $request->title;
            $actionId = $request->action_id;
            $notifyType = $request->notify_type;
            $findAction = Action::find($actionId);
            if ($findAction != "" && $notifyType == 30) {

                $response = $this->notifyAssignedUser($body, $title, $actionId, $notifyType);
                if ($response != "") {
                    $notificationResp = json_decode($response);
                    if ($notificationResp->success == '1') {
                        $data['pr_inv_id '] = $findAction->id;
                        $data['notify_type'] = $notifyType;
                        $status = HTTP_STATUS_OK;
                        $message = HTTP_SUCCESS;
                        return response()->json([
                            'response' => true,
                            'message' => 'Action Assigned',
                            'status' => $status,
                            'data' => $data,
                        ], $status);
                    } else {
                        $status = HTTP_STATUS_NOT_FOUND;
                        $data = 'Action not found';
                        return response()->json([
                            'response' => false,
                            'message' => 'Notification  Not Sent.',
                            'status' => $status,
                            'data' => $data,
                        ], $status);
                    }
                }
            } else {
                $status = HTTP_STATUS_NOT_FOUND;
                $data = 'Action not found.';
                return response()->json([
                    'response' => false,
                    'message' => 'Action Not Found.',
                    'status' => $status,
                    'data' => $data,
                ], $status);
            }
        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
            ], HTTP_STATUS_SERVER_ERROR);
        }
    }

    public function close_action_notification(Request $request)
    {
        try {
            $data = [];
            $body = $request->description;
            $title = $request->title;
            $actionId = $request->action_id;
            $notifyType = $request->notify_type;
            $findAction = Action::find($actionId);
            if ($findAction != "" && $notifyType == 31) {
                $response = $this->notifyAssignedUser($body, $title, $actionId, $notifyType);
                if ($response != "") {
                    $notificationResp = json_decode($response);
                    if ($notificationResp->success == '1') {
                        $data['pr_inv_id '] = $findAction->id;
                        $data['notify_type'] = $notifyType;
                        $status = HTTP_STATUS_OK;
                        $message = HTTP_SUCCESS;
                        return response()->json([
                            'response' => true,
                            'message' => 'Completed Action.',
                            'status' => $status,
                            'data' => $data,
                        ], $status);
                    } else {
                        $status = HTTP_STATUS_NOT_FOUND;
                        $data = 'Actions not found';
                        return response()->json([
                            'response' => false,
                            'message' => 'Notification  Not Sent.',
                            'status' => $status,
                            'data' => $data,
                        ], $status);
                    }
                }
            } else {
                $status = HTTP_STATUS_NOT_FOUND;
                $data = 'Action not found.';
                return response()->json([
                    'response' => false,
                    'message' => 'Action Not Found.',
                    'status' => $status,
                    'data' => $data,
                ], $status);
            }
        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
            ], HTTP_STATUS_SERVER_ERROR);
        }
    }

    public function notifyAssignedUser($body, $title, $actionId, $notifyType)
    {
        $findAction = Action::find($actionId);
        if ($findAction) {
            $data = [
                'pr_inv_id' => $findAction->id,
                'notify_type' => $notifyType,
            ];
            // dd($findAction);

            $getAssineInfo = CheckUserType($findAction->i_ref_assined_role_id, $findAction->assined_user_id);
            // echo "zs";
            //    dd($getAssineInfo);
            if (isset($getAssineInfo) && $getAssineInfo['user_device_token'] != null && !empty($getAssineInfo['user_device_token'])) {
                $tokens = $getAssineInfo['user_device_token'];
            } else {
                $tokens = config('fcm.FCM_SENDER_ID'); //Testing device Token
            }

            $response = $this->actionService->sendPushNotification($tokens, $data, $title, $body, config('fcm.FCM_SERVER_KEY'));
            return $response;
        }
    }

    /**
     * Update action
     */
    public function update(Request $request, $id)
    {
        return $this->actionService->updateAction($id, $request);
    }


    public function getAssignedUserDeviceToken(Request $request)
    {
        $userId = $request->userId;
        if ($userId) {
            $getAssineInfo = Users::where('id', '=', $userId)->with('user_device_token')->first();
            if (isset($getAssineInfo) && $getAssineInfo->user_device_token != null && $getAssineInfo->user_device_token->device_token != "" && !empty($getAssineInfo->user_device_token->device_token)) {
                $tokens = $getAssineInfo->user_device_token->device_token;
            } else {
                $tokens = config('fcm.FCM_SENDER_ID'); //Testing device Token
            }
            return $tokens;
        }
    }


    public function newMesgNotificationCreate(Request $request,  $actionId = null, $title = null, $body = null, $notifyType = null)
    {
        $userId = Auth::id();
        if (empty($userId)) {
            if (empty($token = $request->header('Token'))) {
                $userId = Auth::id();
            } else {
                $token = $request->header('Token');
                $tokenParts = explode(".", $token);
                $tokenHeader = base64_decode($tokenParts[0]);
                $tokenPayload = base64_decode($tokenParts[1]);
                $jwtHeader = json_decode($tokenHeader);
                $jwtPayload = json_decode($tokenPayload);
                $userId = $jwtPayload->id;
            }
        }

        $findAction = Action::find($actionId);
        if ($findAction) {
            if (auth()->user()->users_details->i_ref_role_id == $findAction->i_ref_user_role_id) {
                // $user=Users::find($findAction->assined_user_id);

                $last_Notification_id = Notification::get()->last()->id + 1;

                $user = CheckUserType($findAction->i_ref_assined_role_id, $findAction->assined_user_id);
                if ((!empty($user['role_id']) || isset($user['role_id'])) && $user['role_id'] != 0) {
                    $users = Role::find($user['role_id']);
                    $users->vc_name = $user['full_name'];
                } else {
                    $users = Users::find($findAction->assined_user_id);
                }
                $message = collect([
                    "title" => $title, "message" => $body, "status" => 0,
                    "from_user_id" => auth()->user()->id,
                    "user_id" => $user['user_id'],
                    "action_id" => $actionId,
                    "Notification_id" => $last_Notification_id,
                    'i_ref_user_role_id' => $user['role_id'],
                    'i_ref_from_user_role_id' => auth()->user()->users_details->i_ref_role_id,
                ]);

               FacadeNotification::send($users, new ChatNotification($message));
            } else {
                $last_Notification_id = Notification::get()->last()->id + 1;

                $user = CheckUserType($findAction->i_ref_user_role_id, $findAction->user_id);
                if ((!empty($user['role_id']) || isset($user['role_id'])) && $user['role_id'] != 0) {
                    $users = Role::find($user['role_id']);
                    $users->vc_name = $user['full_name'];
                } else {
                    $users = Users::find($findAction->user_id);
                }
                $message = collect([
                    "title" => $title, "message" => $body, "status" => 0,
                    "from_user_id" => auth()->user()->id,
                    "user_id" => $user['user_id'],
                    "action_id" => $actionId,
                    "Notification_id" => $last_Notification_id,
                    'i_ref_user_role_id' => $user['role_id'],
                    'i_ref_from_user_role_id' => auth()->user()->users_details->i_ref_role_id,
                ]);
                //  dd($users);

                FacadeNotification::send($users, new ChatNotification($message));


                // $user=Users::find($findAction->user_id);
                // FacadeNotification::send($user,new ChatNotification($message));
            }


            $status = HTTP_STATUS_OK;
            $data = 'Notification Created.';
            return response()->json([
                'response' => true,
                'message' => 'Notification Created.',
                'status' => $status,
                'data' => $data,
            ], $status);
        }
    }
}
