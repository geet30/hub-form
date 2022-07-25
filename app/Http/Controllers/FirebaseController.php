<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Users;
use App\Models\Action;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use App\Models\ChatRoomMember;
use App\Services\P2B as P2BService;
use App\Http\Controllers\Controller;
use Google\Cloud\Firestore\FirestoreClient;
use App\Services\Action as ActionService;
use App\Http\Controllers\Api\V1\ActionsController as ActionApi;

class FirebaseController extends Controller
{
    public function __construct()
    {
        $this->P2bService = new P2BService();
        $this->ActionApi = new ActionApi();
        $this->actionService = new ActionService();
    }

    /**
     * Retrieve values saved in realtime collection
     */
    public function index()
    {
        $serviceAccount = __DIR__ . config('firebase.credentials.file');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri(config('firebase.database.url'))->createDatabase();
        $retreiveAllChat = $firebase
            ->getReference('/actions')
            ->getSnapshot();
        $allActionsSaved = $retreiveAllChat->getvalue();
    }

    /**
     * Save data in realtime db collection
     */
    public function initailize_chat(Request $request)
    {
        $message = $request->input('chat_subject');
        $serviceAccount = __DIR__ . config('firebase.credentials.file');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri(config('firebase.database.url'));
        $database = $firebase->createDatabase();
        $createAction = $database
            ->getReference('/actions')
            ->push([
                'id' => 3,
                'title' => 'Action created',
                'message' => $message,
            ]);
        $realDbValues = $createAction->getvalue();

    }

    /**
     * Save data in cloud Firestore database collection
     */
    public function update_chat(Request $request)
    {
        $data = array();
        $actionId = $request->input('action_id');
        $chatMessage = $request->input('chat_message');
        $uploadFile = $request->input('file_name');
        $curTimestamp = $request->input('current_time');
        $extType = $request->input('file_ext');
        if ($actionId || $chatMessage || $uploadFile) {
            $findChat = Action::find($actionId);
            if ($findChat != "") {
                $documents = $this->saveMediaOnFireStore($actionId, $findChat, $chatMessage, $uploadFile, $curTimestamp, $extType);
                $appendData = $this->getDynamicChatSection($documents, $findChat->user_id);
                return $response = [
                    'document' => $appendData,
                    'message' => 'Message Sent Successfully',
                    'type' => 'success',
                ];
            } else {
                $this->sendFinalResponse($data);
            }
        } else {
            $this->sendFinalResponse($data);
        }
    }

    /**
     * Upload media on cloud storage
     * Send Text Message
     */
    public function saveMediaOnFireStore($actionId, $findChat, $chatMessage, $uploadFile, $curTimestamp, $extType)
    {
        $serviceAccount = __DIR__ . config('firebase.credentials.file');
        $db = new FirestoreClient([
            'keyFilePath' => $serviceAccount,
            'projectId' => 'p2b-app',
        ]);
        $findAction = Action::find($actionId);
        $notifyType = 37;
        $userId = Auth::id();
        $chatRef = $db->collection('chat');     



        if (auth()->user()->users_details->i_ref_role_id == $findAction->i_ref_user_role_id){ 
            // echo "asdrrr";
            $getAssineInfo=CheckUserType($findAction->i_ref_assined_role_id,$findAction->assined_user_id);
        }elseif(auth()->user()->id == $findAction->user_id){
            // echo "asdd";
            $getAssineInfo=CheckUserType($findAction->i_ref_assined_role_id,$findAction->assined_user_id);
        }else{
            // echo "asd";
            $getAssineInfo=CheckUserType($findAction->i_ref_user_role_id,$findAction->user_id);
        }

        $assigne=$getAssineInfo['user_id'];
        $assineName=$getAssineInfo['full_name'];
        $assineRoleId=$getAssineInfo['role_id'];
        $getdeviceToken=$getAssineInfo['user_device_token'];


        $title = auth()->user()->full_name;
        if(empty($title)){
            $title = 'New Message Arrived';
        }
        if ($chatMessage != "" && $uploadFile == "") {
            $body = $chatMessage;
            $data = [
                'pr_inv_id' => $findAction->id,
                'notify_type' => $notifyType,
            ];
            $chatRef->document('room_' . $actionId)
                ->collection('messages')
                ->document('msg_' . $curTimestamp)
                ->set([
                    'action_id' => (int) $actionId,
                    'id' => $curTimestamp,
                    'is_read' => 0,
                    'media_type' => null,
                    'media_url' => null,
                    'message_text' => $chatMessage,
                    // 'sender_id' => (string) $userId,
                    // 'receiver_id' => (string) $findChat->assined_user_id,

                    'sender_id' => (string) Auth::id(),
                    'sender_name' => (string) auth()->user()->full_name,
                    'sender_role_id' => (string) auth()->user()->users_details->i_ref_role_id,
                   
                    'receiver_id' => (string) $assigne,
                    'receiver_name' => (string) $assineName,
                    'receiver_role_id' => (string) $assineRoleId,

                    'sent_at' => (int) $curTimestamp,
                    'thumbnail' => null,
                    'type' => 1,
                ]);
        } elseif ($chatMessage == "" && $uploadFile != "") {
            $body = "Document Received";    
            if ($extType == 'jpg' || $extType == 'jpg%7D' || $extType == 'jpeg' || $extType == 'png' || $extType == 'gif') {
                $typeOfMedia = 1;
            } elseif ($extType == 'pdf') {
                $typeOfMedia = 3;
            } elseif ($extType == 'mp4') {
                $typeOfMedia = 4;
            } elseif ($extType == 'mp3') {
                $typeOfMedia = 2;
            } elseif ($extType == 'doc' || $extType == 'docx' || $extType == 'docm' || $extType == 'csv') {
                $typeOfMedia = 5;
            } else {
                $typeOfMedia = 1;
            }
            if($typeOfMedia==1){
                $data = [
                    'pr_inv_id' => $findAction->id,
                    'notify_type' => $notifyType,
                    "file_url"=>$uploadFile,
                    "doc_type"=>$typeOfMedia
                ];
            }else{
                $data = [
                    "file_url"=>"",
                    'pr_inv_id' => $findAction->id,
                    'notify_type' => $notifyType,
                    "doc_type"=>$typeOfMedia
                ];
            }
            $chatMessage = null;
            $type = 2;
            $chatRef->document('room_' . $actionId)
                ->collection('messages')
                ->document('msg_' . $curTimestamp)
                ->set([
                    'action_id' => (int) $actionId,
                    'id' => $curTimestamp,
                    'is_read' => 0,
                    'media_type' => $typeOfMedia,
                    'media_url' => $uploadFile,
                    'message_text' => $chatMessage,
                    // 'sender_id' => (string) $userId,
                    // 'receiver_id' => (string) $findChat->assined_user_id,
                    'sender_id' => (string) Auth::id(),
                    'sender_name' => (string) auth()->user()->full_name,
                    'sender_role_id' => (string) auth()->user()->users_details->i_ref_role_id,
                   
                    'receiver_id' => (string) $assigne,
                    'receiver_name' => (string) $assineName,
                    'receiver_role_id' => (string) $assineRoleId,

                    'sent_at' => (int) $curTimestamp,
                    'thumbnail' => $uploadFile,
                    'type' => $type,
                ]);
        } elseif ($chatMessage != "" && $uploadFile != "") {
            $body = "Document Received";  
            $chatRef->document('room_' . $actionId)
                ->collection('messages')
                ->document('msg_' . $curTimestamp)
                ->set([
                    'action_id' => (int) $actionId,
                    'id' => $curTimestamp,
                    'is_read' => 0,
                    'media_type' => null,
                    'media_url' => null,
                    'message_text' => $chatMessage,
                    // 'sender_id' => (string) $userId,
                    // 'receiver_id' => (string) $findChat->assined_user_id,
                    'sender_id' => (string) Auth::id(),
                    'sender_name' => (string) auth()->user()->full_name,
                    'sender_role_id' => (string) auth()->user()->users_details->i_ref_role_id,
                   
                    'receiver_id' => (string) $assigne,
                    'receiver_name' => (string) $assineName,
                    'receiver_role_id' => (string) $assineRoleId,

                    'sent_at' => (int) $curTimestamp,
                    'thumbnail' => null,
                    'type' => 1,
                ]);
            if ($uploadFile != "") {
                if ($extType == 'jpg' || $extType == 'jpg%7D' || $extType == 'jpeg' || $extType == 'png' || $extType == 'gif') {
                    $typeOfMedia = 1;
                } elseif ($extType == 'pdf') {
                    $typeOfMedia = 3;
                } elseif ($extType == 'mp4') {
                    $typeOfMedia = 4;
                } elseif ($extType == 'mp3') {
                    $typeOfMedia = 2;
                } elseif ($extType == 'doc' || $extType == 'docx' || $extType == 'docm' || $extType == 'csv') {
                    $typeOfMedia = 5;
                } else {
                    $typeOfMedia = 1;
                }
                if($typeOfMedia==1){
                    $data = [
                        'pr_inv_id' => $findAction->id,
                        'notify_type' => $notifyType,
                        "file_url"=>$uploadFile,
                        "doc_type"=>$typeOfMedia
                    ];
                }else{
                    $data = [
                        "file_url"=>"",
                        'pr_inv_id' => $findAction->id,
                        'notify_type' => $notifyType,
                        "doc_type"=>$typeOfMedia
                    ];
                }
                $incTime = $curTimestamp + 1;
                $chatRef->document('room_' . $actionId)
                    ->collection('messages')
                    ->document('msg_' . $incTime)
                    ->set([
                        'action_id' => (int) $actionId,
                        'id' => $incTime,
                        'is_read' => 0,
                        'media_type' => $typeOfMedia,
                        'media_url' => $uploadFile,
                        'message_text' => null,
                        // 'sender_id' => (string) $userId,
                        // 'receiver_id' => (string) $findChat->assined_user_id,
                        'sender_id' => (string) Auth::id(),
                        'sender_name' => (string) auth()->user()->full_name,
                        'sender_role_id' => (string) auth()->user()->users_details->i_ref_role_id,
                       
                        'receiver_id' => (string) $assigne,
                        'receiver_name' => (string) $assineName,
                        'receiver_role_id' => (string) $assineRoleId,
    
                        'sent_at' => (int) $incTime,
                        'thumbnail' => $uploadFile,
                        'type' => 2,
                    ]);
            }
        }
        $Request = new Request();

              
        //in db chat notifation for web and api
        $newMsg=$this->ActionApi->newMesgNotificationCreate($Request,$actionId,$title, $body, $notifyType);
        
        if (isset($getdeviceToken) && $getdeviceToken != null  && $getdeviceToken!="" ) {
            $tokens = $getdeviceToken;
       } else {
            $tokens = config('fcm.FCM_SENDER_ID'); //Testing device Token
        }
        
        // firebase push notifation
        // $response = $this->actionService->sendPushNotification($tokens, $data, $title, $body, config('fcm.FCM_SERVER_KEY'));
        
        $this->saveChatDetails((int) $actionId, $userId, (string) $findChat->assined_user_id);
        $getActionMessages = $chatRef->document('room_' . $actionId)->collection('messages');
        $query = $getActionMessages->where('action_id', '=', (int) $actionId);
        $documents = $query->documents();
        return $documents;
    }

    /**
     * Append new chat messages and media
     */
    public function getDynamicChatSection($documents, $sender = '')
    {
        foreach ($documents as $document) {
            if ($document->exists()) {
                $actionChatMessages[] = $document->data();
            }
        }
        if (count($actionChatMessages) > 0) {
            $senderUserInfo="";
            $tempArrayUsers = [];
            foreach ($actionChatMessages as $index => $value) {
                // $senderId = $actionChatMessages[$index]['sender_id'];
                // if ($user = $this->searcharray($senderId, 'id', $tempArrayUsers)) {
                //     $senderUserInfo = $user;
                // } else {
                //     if(!empty($senderId)){
                //         $senderUserInfo = $this->P2bService->getUser($senderId);
                //         array_push($tempArrayUsers, $senderUserInfo);
                //     }
                // }
                // $chatInfo[$index]['sender_info'] = $senderUserInfo;
                $chatInfo[$index]['document'] = $actionChatMessages[$index];
            }
        }
        //$senderId = $findChat->user_id; //Action User Id.
        // $sender = 204; //static user Id
        $actionCreatedUser = $this->P2bService->getUser($sender);
        $appendData = view('admin.action.firebase-dynamic-chat', compact('chatInfo', 'actionCreatedUser'))->render();
        return $appendData;
    }

    /**
     * Send Json response
     */
    public function sendFinalResponse($data)
    {
        return $response = [
            'document' => $data,
            'message' => 'Message Not Sent',
            'type' => 'error',
        ];
    }

    /**
     *Save chat information in chat room
     */
    public function saveChatDetails($actionId, $senderId, $receiverId)
    {
        $chatRoom = ChatRoom::where('type_id', '=', $actionId)->where('type_model', null)->get();
        if (count($chatRoom) == 0) {
            $saveChat = new ChatRoom();
            $saveChat->members_count = 2;
            $saveChat->type = 1;
            $saveChat->chat_status = 1;
            $saveChat->type_id = $actionId;
            $saveChat->save();
            $chatRoomId = $saveChat->id;
            $this->saveChatRoomMemberDetails($actionId, $chatRoomId, $senderId, $receiverId);
        } else {
            $chatRoomId = $chatRoom[0]->id;
            $this->saveChatRoomMemberDetails($actionId, $chatRoomId, $senderId, $receiverId);
        }
    }

    /**
     *Save chat room member details
     */
    public function saveChatRoomMemberDetails($actionId, $chatRoomId, $senderId, $receiverId)
    {
        $chatRoomMemberSender = new ChatRoomMember();
        $chatRoomMemberSender->chat_room_id = $chatRoomId;
        $chatRoomMemberSender->action_id = $actionId;
        $chatRoomMemberSender->member_id = $senderId;
        $chatRoomMemberSender->joined_at = now();
        $chatRoomMemberSender->is_active = 1;
        $chatRoomMemberSender->status = 1;
        $chatRoomMemberSender->save();

        $chatRoomMemberReceiver = new ChatRoomMember();
        $chatRoomMemberReceiver->chat_room_id = $chatRoomId;
        $chatRoomMemberReceiver->action_id = $actionId;
        $chatRoomMemberReceiver->member_id = $receiverId;
        $chatRoomMemberReceiver->joined_at = now();
        $chatRoomMemberReceiver->status = 1;
        $chatRoomMemberReceiver->save();
        return;
    }

    /**
     * save send message to firebase chatroom
     */
    public function chat(Request $request)
    {
        try {
            $input = $request->only(['receiver_id', 'action_id', 'sent_at', 'file_ext', 'message_text', 'file_name']);
            $chatRef = $this->initailizeFirebase()->collection('chat');
            $userId = Auth::id();
            if ($request->has('message_text') && !empty($request->message_text)) {
                $input['action_id'] = (int) $input['action_id'];
                $input['is_read'] = 0;
                $input['media_type'] = null;
                $input['media_url'] = null;
                $input['media_url'] = null;
                $input['thumbnail'] = null;
                $input['type'] = 1;
                $input['type'] = 1;
                $input['sender_id'] = (string) $userId;
                $input['receiver_id'] = (string) $input['receiver_id'];
                $curTimestamp = (int) $input['sent_at'];
                $input['id'] = $curTimestamp;
                $chatRef->document('room_' . $input['action_id'])->collection('messages')->document('msg_' . $curTimestamp)->set($input);
            }

            if ($request->has('file_name') && !empty($request->file_name)) {

                $extType = $input['file_ext'];

                if ($extType == 'jpg' || $extType == 'jpg%7D' || $extType == 'jpeg' || $extType == 'png' || $extType == 'gif') {
                    $typeOfMedia = 1;
                } elseif ($extType == 'pdf') {
                    $typeOfMedia = 3;
                } elseif ($extType == 'mp4') {
                    $typeOfMedia = 4;
                } elseif ($extType == 'mp3') {
                    $typeOfMedia = 2;
                } elseif ($extType == 'doc' || $extType == 'docx' || $extType == 'docm' || $extType == 'csv') {
                    $typeOfMedia = 5;
                } else {
                    $typeOfMedia = 1;
                }
                $curTimestamp = $input['sent_at'];
                $incTime = $curTimestamp + 1;
                $input['action_id'] = (int) $input['action_id'];
                $input['is_read'] = 0;
                $input['media_type'] = $typeOfMedia;
                $input['media_url'] = $request->file_name;
                $input['thumbnail'] = $request->file_name;
                $input['type'] = 2;
                $input['sender_id'] = (string) $userId;
                $input['receiver_id'] = (string) $input['receiver_id'];
                $input['id'] = $incTime;
                $input['sent_at'] = (int) $incTime;
                $input['message_text'] = null;
                $chatRef->document('room_' . $input['action_id'])->collection('messages')->document('msg_' . $incTime)->set($input);
            }

            $getActionMessages = $chatRef->document('room_' . $input['action_id'])->collection('messages');
            $messagesObj = $getActionMessages->documents();
            $messages = [];
            $tempArrayUsers = [];
            foreach ($messagesObj as $key => $value) {
                $row = $value->data();
                $senderId = $row['sender_id'];
                if ($user = $this->searcharray($senderId, 'id', $tempArrayUsers)) {
                    $senderUserInfo = $user;
                } else {
                    $senderUserInfo = $this->P2bService->getUser($senderId);
                    array_push($tempArrayUsers, $senderUserInfo);
                }
                $row['sender_info'] = $senderUserInfo;
                array_push($messages, $row);
            }
            $chatHtml = view('partials.chats_inbox', compact('messages'))->render();
            return $this->returnResponse(HTTP_STATUS_OK, true, trans('response.message_send'), ['chatHtml' => $chatHtml]);
        } catch (Exception $ex) {
            return $this->returnResponse(HTTP_STATUS_SERVER_ERROR, false, $ex->getMessage());
        }
    }
}
