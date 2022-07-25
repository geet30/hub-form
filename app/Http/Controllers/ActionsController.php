<?php

namespace App\Http\Controllers;

use Str;
use Auth;
use App\Models\Users;
use App\Models\Action;
use App\Models\Evidence;
use App\Models\UserDetail;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\RecurringAction;
use App\Models\UserPermissions;
use App\Services\P2B as P2BService;
use App\Http\Requests\ActionFormRequest;
use Illuminate\Support\Facades\Redirect;
use App\Services\Action as ActionService;
use Google\Cloud\Firestore\FirestoreClient;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\V1\ActionsController as ActionApi;
// use App\Http\Controllers\FirebaseController;

class ActionsController extends Controller
{

    public function __construct()
    {   
        // dd(Auth::id());
        $this->P2bService = new P2BService();
        $this->ActionService = new ActionService();
        $this->ActionApi = new ActionApi();
        // $this->FirebaseController= new FirebaseController();
    }

    public function index(Request $request)
    {

        //get actions
        $action_listings = Action::with([
            'completedForm',
            'business_unit' => function($query){
                $query->select('id', 'vc_short_name');
            },
            'department' => function($query){
                $query->select('id', 'vc_name');
            },
            'project' => function($query){
                $query->select('id', 'vc_name');
            },
            'assignee_user'
        ])->orderBy('id', 'desc');
        if (auth()->check() && auth()->user()->user_type == 'employee') {
            $action_listings = $action_listings->with([
                'user' => function ($query) {
                    $query->select('id', 'vc_fname', 'vc_mname', 'vc_lname');
                },
            ]);
            $useroleid=auth()->user()->users_details->i_ref_role_id;
            $action_listings = $action_listings->whereRaw("((i_ref_assined_role_id = $useroleid AND status != 1 AND status != 5)OR i_ref_user_role_id = $useroleid)");
      
        }else if(auth()->check() && auth()->user()->user_type == 'supplier'){
           
            $userId = Auth::id();
            $action_listings = $action_listings->whereRaw("((assined_user_id = $userId AND status != 1 AND status != 5)OR user_id = $userId)");
       
        }
        // 1.pending , 2.In-progress, 3. Completed, 5. Reject, 6. Overdue 
        $action_listings = $action_listings->get();
        $assignUser = $this->P2bService->getAllCompanyUsers();

        $defaultOptions = [
            [
                'vc_fname' => '',
                'vc_mname' => '',
                'vc_lname' => '',
                'full_name' => 'Assignee'
            ]
        ];

        if(auth()->user()->user_type == 'supplier'){
            $defaultOptions = [
                [
                    'vc_fname' => '',
                    'vc_mname' => '',
                    'vc_lname' => '',
                    'full_name' => 'Assigned By'
                ]
            ];            
        }

        $usersAr = $assignUser->each->setAppends(['full_name'])->toArray();
        $users = array_merge($defaultOptions, $usersAr);

        $departments = $this->P2bService->getDepartments();
        $status = !empty($request['status'])?$request['status']:'';
        $active = 'action';
        return view('admin.action.index', compact('action_listings', 'active', 'assignUser', 'departments', 'status', 'users'));
    }

    public function createAction(Request $request)
    {
        $active = 'create_action';

        $users = $this->P2bService->getAllUsersForCreateActions();
        // $locations = $this->P2bService->getallLocation();
        $business_units = $this->P2bService->getBusinessUnits();
        
        return view('admin.action.create-action', ['active' => $active, 'users' => $users, 'business_units' => $business_units]);
    }

    public function saveAction(ActionFormRequest $request)
    {
        // dd(Auth::id());
        // need assinder role_id
        $title = $request->input('action_title');
        $description = $request->input('action_desc');
        // $status = $request->input('status');
        $status = 1;
        // $locId = $request->input('location');
        $assigne = $request->input('assignee');
        $authId = $request->input('user_id');
        $priority = $request->input('priority');
        $dueDate = $request->input('due_date');
        $dateFormat = date('Y-m-d', strtotime($dueDate));
        $rec = $request->input('recurring_action');
        $addAction['title'] = $title;
        $addAction['descriptions'] = $description;
        $addAction['assined_user_id'] = $assigne;
        $addAction['business_unit_id'] = $request->input('business_unit');
        $addAction['department_id'] = $request->input('department');
        $addAction['project_id'] = $request->input('project');
        // $addAction['location_id'] = $locId;

        if ($authId == "") {
            $authId = null;
        }
        if ($rec == "") {
            $rec = null;
        }

        $previousActions = Action::withTrashed()->count();
        $nextAction = (int) $previousActions + 1;
        $addAction['action_id'] = 'A00'. $nextAction;
        $addAction['reocurring_actions'] = $rec;
        $addAction['user_id'] = $authId;
        $addAction['status'] = $status;
        $addAction['priority'] = $priority;
        if(!empty($rec) && $rec == '1'){
            $addAction['due_date'] = $request['end_date'];
        }else{
            $addAction['due_date'] = $dateFormat;
        }
        $row = Action::create($addAction);
        $actionId = $row->id;
        /**
         * save recurring data
         */
        if (!empty($actionId) && !empty($rec) && $rec == '1') {
            $day = null;
            $week = null;
            $month = null;
            $recur_type = $request['recurring_type'];
            if ($recur_type == 1) {
                $day = $request['daily_day'];
                $month = null;
                $week = null;
            } elseif ($recur_type == 2) {
                $week_day = $request['weekly_day'];
                $day = implode(',', $week_day);
                $month = null;
                $week = $request['weekly_week'];
            } elseif ($recur_type == 3) {
                if ($request['monthly_pattern'] == '1') {
                    $day = $request['month_day'];
                    $month = $request['month_month'];
                    $week = $request['month_week'];
                } elseif ($request['monthly_pattern'] == '2') {
                    $day = $request['month_day_sec'];
                    $month = $request['month_month_sec'];
                    $week = null;
                }
            } elseif ($recur_type == 4) {
                if ($request['yearly_pattern'] == '1') {
                    $day = $request['year_day'];
                    $month = $request['year_month'];
                    $week = $request['year_week'];
                } elseif ($request['yearly_pattern'] == '2') {
                    $day = $request['year_day_second'];
                    $month = $request['year_month_second'];
                    $week = null;
                }
            }
            $start_date = $request['start_date'];
            $end_date = $request['end_date'];
            $RecurActionRow = RecurringAction::create([
                'action_id' => $actionId,
                'recurrence_type' => $recur_type,
                'day' => $day,
                'week' => $week,
                'month' => $month,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]);
        }
        $assigneInfo = $this->P2bService->getUser($row->assined_user_id);
        if ($assigneInfo != "") {
            // date_default_timezone_set('Asia/Kolkata');
            $currentDateTime = strtotime(now()) * 1000;
            $messageText = 'New Action is created.';
            $assigne=CheckUserTypeAndGetRoleID($row->assined_user_id);
            $assineRoleId=$assigne->role_id;
            $assineName=$assigne->name;
            $this->updateActionInFireStore((int) $actionId,$row->assined_user_id,$assineRoleId,$assineName, $messageText, $currentDateTime);
        }

        $encryptId = encrypt_decrypt('encrypt', $actionId);
        $row->is_notified = 1;
                
        if($row->update()){
                // $this->saveNotificationDetails($title, $notifyType, $actionId, $assigne);
                return Redirect::route('edit_action', ['id' => $encryptId])->with('success', 'Successfully create new action.');
        } else {
            return Redirect::route('edit_action', ['id' => $encryptId])->with('success', 'Successfully create new action.');
        }
    }

    public function editAction($id)
    {
        $active = 'action';
        $actionChatMessages = array();
        $chatInfo = array();
        // $actionCreatedUser = array();
        $decryptId = encrypt_decrypt('decrypt', $id);
        $actionData = Action::find($decryptId);
     

        $recurringData = RecurringAction::where('action_id', $decryptId)->first();
        $assigneId = !empty($actionData->assined_user_id)?$actionData->assined_user_id:null;
        $assigneRoleId = $actionData->i_ref_assined_role_id;
        $userRoleID=$actionData->i_ref_user_role_id;
        $closeUserRoleID=$actionData->i_ref_closed_by_role_id;

        // $users = $this->P2bService->getallUsers();

        $users = $this->P2bService->getAllUsersForCreateActions();
        // $locations = $this->P2bService->getallLocation();
        $business_units = $this->P2bService->getBusinessUnits();
        
   
        if ($actionData != "") {
            $senderId = $actionData->user_id; //Action User Id.
            // $senderId = 204; //static user Id
            // $actionCreatedUser = $this->P2bService->getUser($senderId);
            // dd($userRoleID);
    
            // $assigneuser=CheckUserType($assigneRoleId);
            $actionCreatedUser=CheckUserType($userRoleID,$senderId);
            // $closeUser=role_has_user($closeUserRoleID);
             
        }

        if(!empty($actionData) && $actionData->business_unit_id){
            $budata = $this->P2bService->getBusinessUnit($actionData->business_unit_id);
        }else{
            $budata = '';
        }

        if(!empty($actionData) && $actionData->status == 3){
            return redirect()->route('actions')->with('error', 'You cannot edit this action!');
        }elseif(empty($actionData)){
            return redirect()->route('actions')->with('error', 'You are unable to edit this action!');
        }else{
            return view('admin.action.edit-action', ['active' => $active, 'users' => $users, 'business_units' => $business_units, 'actionData' => $actionData, 'actionCreatedUser' => $actionCreatedUser, 'recurringData' => $recurringData, 'budata' => $budata]);
        }
        
    }

    /**
     * View Action details
     */
    public function view($id)
    {
        $active = 'action';
        $actionChatMessages = array();
        $chatInfo = array();
        $actionClosedUser=array();
        $actionCreatedUser = array();
        $decryptId = encrypt_decrypt('decrypt', $id);
        $actionData = Action::with([
            'user' => function ($query) {
                $query->select('id', 'vc_fname', 'vc_mname', 'vc_lname');
            },
            'evidences','close_by' => function ($query) {
                $query->select('id', 'vc_fname', 'vc_mname', 'vc_lname');
            },
            'business_unit' => function($query){
                $query->select('id', 'vc_short_name');
            },
            'department' => function($query){
                $query->select('id', 'vc_name');
            },
            'project' => function($query){
                $query->select('id', 'vc_name');
            }
        ])->whereId($decryptId)->first();
        $assigneRoleId = $actionData->i_ref_assined_role_id;
        $userRoleID=$actionData->i_ref_user_role_id;
        $closeUserRoleID=$actionData->i_ref_closed_by_role_id;

     
        $assigneId = $actionData->assined_user_id;
        // $actionassignedUser = $this->P2bService->getUser($assigneId);

        $location = $this->P2bService->getLocation($actionData->location_id);
        $serviceAccount = __DIR__ . config('firebase.credentials.file');
        $db = new FirestoreClient([
            'keyFilePath' => $serviceAccount,
            'projectId' => 'p2b-app',
        ]);
        $chatRef = $db->collection('chat');
        $getActionMessages = $chatRef->document('room_' . $decryptId)->collection('messages');
        $query = $getActionMessages->where('action_id', '=', (int) $decryptId);
        $documents = $query->documents();
        foreach ($documents as $document) {
            if ($document->exists()) {
                $actionChatMessages[] = $document->data();
            }
        }
        if (count($actionChatMessages) > 0) {
            foreach ($actionChatMessages as $index => $value) {
                // $senderId = $actionChatMessages[$index]['sender_id'];
                // $senderUserInfo = $this->P2bService->getUser($senderId);
                // $chatInfo[$index]['sender_info'] = $senderUserInfo;
                $chatInfo[$index]['document'] = $actionChatMessages[$index];
            }
        }
        if ($actionData != "") {
            $senderId = $actionData->user_id; //static user Id
            // $actionCreatedUser = $this->P2bService->getUser($senderId);
            $actionassignedUser=CheckUserType($assigneRoleId,$assigneId);
            $actionCreatedUser=CheckUserType($userRoleID,$senderId);

            if(!empty($actionData->close_by)){
                // $actionClosedUser=role_has_user($closeUserRoleID);
                $actionClosedUser = CheckUserType($actionData->i_ref_closed_by_role_id,$actionData->closed_by);
                if(empty($actionClosedUser)){
                   $actionClosedUser['full_name']=$actionData->close_by->vc_fname;
                }
            }
            // dd($actionCreatedUser);
    
        }
        // pr($actionData->close_by);die;
        return view('admin.action.view-action', ['active' => $active, 'location' => $location, 'actionData' => $actionData, 'chatInfo' => $chatInfo, 'actionCreatedUser' => $actionCreatedUser,"actionClosedUser"=>$actionClosedUser,'actionassignedUser'=>$actionassignedUser]);
    }

    public function updateAction(ActionFormRequest $request)
    {        
        // dd($request->all());
        $actionId = $request->input('action_id');
        $title = $request->input('action_title');
        $description = $request->input('action_desc');
        if($request->input('status') == 5){
            // $status = 1;
            $status = 5;
        }else{
            $status = $request->input('status');
        }
        $locId = $request->input('location');
        $assigne = $request->input('assignee');
        $authId = $request->input('user_id');
        $priority = $request->input('priority');
        $dueDate = $request->input('due_date');
        $actionComment = $request->input('action_comment');
        $dateFormat = date('Y-m-d', strtotime($dueDate));
        $currTimeDate = strtotime(now()) * 1000;
        $rec = $request->input('recurring_action');
        $updateAction = Action::find($actionId);
        $input['title'] = $title;
        $input['descriptions'] = $description;
        $oldAssigneId = $updateAction->assined_user_id;
        $oldAssigneRoleId = $updateAction->i_ref_assined_role_id;
        $oldStatus = $updateAction->status;
        $assined_role_id= CheckUserTypeAndGetRoleID($assigne);
        $input['i_ref_assined_role_id'] = $assined_role_id['role_id'];
        $input['assined_user_id'] = $assigne;
        $input['location_id'] = $locId;
        $input['business_unit_id'] = $request->input('business_unit');
        $input['department_id'] = $request->input('department');
        $input['project_id'] = $request->input('project');
        if ($authId == "") {
            $authId = null;
        }
        if ($rec == "") {
            $rec = null;
        } elseif ($request->input('cancel_edit') == '1' && empty($request->input('recurring_id'))) {
            $rec = null;
        } elseif (!empty($request->input('recurring_id'))) {
            $rec = 1;
        }
        if ($actionComment == "" || $actionComment == null) {
            $actionComment = null;
        }

        $input['comment'] = $actionComment;
        $input['reocurring_actions'] = $rec;
        $input['user_id'] = $authId;
        $input['status'] = $status;
        $input['priority'] = $priority;
        if(!empty($rec) && $rec == '1'){
            $input['due_date'] = $request['end_date'];
        }else{
            $input['due_date'] = $dateFormat;
        }
        $input['created_at'] = now();
        if ($oldAssigneId != $assigne && ($status ==1 || $status ==2)) {
            
            Notification::where('i_ref_user_role_id', $oldAssigneRoleId)
            ->where('notificationable_id', $actionId)
            ->where('notificationable_type', 'App\Models\Action')
            ->update(['status' => 1]);
            $body = 'The action is assigned to someone else.';
            $title = 'Action updated assigned';
            $notifyType = 30;
            $response = $this->ActionApi->notifyAssignedUser($body, $title, $actionId, $notifyType);
           

        }

        $updateAction->fill($input)->save();

        /**
         * save recurring data
         */
        if (!empty($actionId) && !empty($rec) && $rec == '1') {
            $day = null;
            $week = null;
            $month = null;
            $recur_type = $request['recurring_type'];
            if ($recur_type == 1) {
                $day = $request['daily_day'];
                $month = null;
                $week = null;
            } elseif ($recur_type == 2) {
                $week_day = $request['weekly_day'];
                $day = implode(',', $week_day);
                $month = null;
                $week = $request['weekly_week'];
            } elseif ($recur_type == 3) {
                if ($request['monthly_pattern'] == '1') {
                    $day = $request['month_day '];
                    $month = $request['month_month'];
                    $week = $request['month_week'];
                } elseif ($request['monthly_pattern'] == '2') {
                    $day = $request['month_day_sec'];
                    $month = $request['month_month_sec'];
                    $week = null;
                }
            } elseif ($recur_type == 4) {
                if ($request['yearly_pattern'] == '1') {
                    $day = $request['year_day'];
                    $month = $request['year_month'];
                    $week = $request['year_week'];
                } elseif ($request['yearly_pattern'] == '2') {
                    $day = $request['year_day_second'];
                    $month = $request['year_month_second'];
                    $week = null;
                }
            }
            $start_date = $request['start_date'];
            $end_date = $request['end_date'];
            if ($request->input('cancel_edit') != '1' && empty($request->input('recurring_id'))) {
                $RecurAction = new RecurringAction();
                $RecurAction->action_id = $actionId;
                $RecurAction->recurrence_type = $recur_type;
                $RecurAction->day = $day;
                $RecurAction->week = $week;
                $RecurAction->month = $month;
                $RecurAction->start_date = $start_date;
                $RecurAction->end_date = $end_date;
                $RecurAction->save();
            } elseif ($request->input('cancel_edit') != '1' && !empty($request->input('recurring_id'))) {
                $RecurAction = RecurringAction::find($request->input('recurring_id'));
                $RecurAction->action_id = $actionId;
                $RecurAction->recurrence_type = $recur_type;
                $RecurAction->day = $day;
                $RecurAction->week = $week;
                $RecurAction->month = $month;
                $RecurAction->start_date = $start_date;
                $RecurAction->end_date = $end_date;
                $RecurAction->update();
            }
        }
        $encryptId = encrypt_decrypt('encrypt', $actionId);




        if ($oldAssigneId != $assigne && ($status ==1 || $status ==2)) {
            $make_status_pending['status'] = 1;
            $updateAction->fill($make_status_pending)->save();

            //get new assigne
            // $assigneInfo = $this->P2bService->getUser($assigne);
            // $assigneInfo=role_has_user($updateAction->i_ref_assined_role_id);
            $assigneInfo = CheckUserType($updateAction->i_ref_assined_role_id,$assigne);
           
            if ($assigneInfo != "") {
                $messageText = 'This Action has been reassigned to ' . $assigneInfo['full_name'];
                // $assignee=CheckUserTypeAndGetRoleID($assigne);
                $assineRoleId=$assigneInfo['role_id'];
                $assineName=$assigneInfo['full_name'];
                $this->updateActionInFireStore((int) $actionId,$assigne,$assineRoleId,$assineName, $messageText, $currTimeDate);
                
            }
            // create notification
            if (auth()->user()->users_details->i_ref_role_id == $updateAction->i_ref_user_role_id) {
                $notiinput['title'] = trans("notifications.action_created_title");
                $notiinput['message'] = trans("notifications.action_created", [
                    "name" => $updateAction->user->full_name,
                    "title" => $updateAction->title,
                ]);
                $notiinput['i_ref_user_role_id'] = $assigneInfo['role_id'];
                $notiinput['i_ref_from_user_role_id'] = auth()->user()->users_details->i_ref_role_id;
                $notiinput['status'] = 0;
                $notiinput['user_id'] = $updateAction->assined_user_id;
                $notiinput['from_user_id'] = $updateAction->user_id;
                $notiinput['notification_type'] = 30;
                $updateAction->notifications()->create($notiinput);
            }

            $body = 'This action is assigned to you.';
            $title = 'Action updated assigned';
            $notifyType = 30;
            $response = $this->ActionApi->notifyAssignedUser($body, $title, $actionId, $notifyType);
            if ($response != "") {
                $notificationResp = json_decode($response);
                if ($notificationResp->success == 1) {
                    $updateAction->is_notified = 1;
                    $updateAction->update();
                    // $this->saveNotificationDetails($title, $notifyType, $actionId, $assigne);
                    return Redirect::route('edit_action', ['id' => $encryptId])->with('success', 'Successfully updated action and sent notification.');
                } else {
                    return Redirect::route('edit_action', ['id' => $encryptId])->with('error', 'Successfully updated action. But notification not sent. Assignee device token is not available');
                }
            } else {
                return Redirect::route('edit_action', ['id' => $encryptId])->with('error', 'Successfully updated action. But notification not sent. Assignee device token is not available');
            }
        } else {

            if(($oldStatus !=$status) && ($status ==2 || $status ==5 ) ){
                if($status == 5){
                    
                    $body = 'This Action is rejected.';
                }else{
                    $body = 'This Action is accepted.';
                }
                $title = 'Action updated assigned';
                $notifyType = 30;
                $response = $this->ActionApi->notifyAssignedUser($body, $title, $actionId, $notifyType);
                if($status == 5){
                    $messageText = 'Action is rejected.';
                }else{
                    $messageText = 'Action is accepted.';
                }
                $currentDateTime = $currentDateTime = strtotime(now()) * 1000;

                // $assignee=CheckUserTypeAndGetRoleID($assigne);
                // $assignee=role_has_user($updateAction->i_ref_assined_role_id);
                $assignee = CheckUserType($updateAction->i_ref_assined_role_id,$assigne);
        
                $assineRoleId=$assignee['role_id'];
                $assineName=$assignee['full_name'];
                $this->updateActionInFireStore((int) $actionId,$assigne,$assineRoleId,$assineName, $messageText, $currentDateTime);
                 // create notification
               

            }

            return Redirect::route('edit_action', ['id' => $encryptId])->with('success', 'Successfully updated action.');
        }
    }

    /**
     * show data of
     * business unit, project,
     * department and user
     * in listing of actions
     */

    public function show_data(Request $request)
    {
        $name = '';
        $data = '';
        if (isset($request)) {
            if ($request->target == 'asignee_name') {
                if (!empty($request->id)) {
                    // echo 'in if';die;
                    $asignee = $this->P2bService->getUser($request->id);
                    if (!empty($asignee)) {
                        $name = $asignee->vc_title . " " . $asignee->vc_fname . " " . $asignee->vc_mname . " " . $asignee->vc_lname;
                    }
                }
            } elseif ($request->target == 'bu_name') {
                if (!empty($request->id)) {
                    $BU = $this->P2bService->getBusinessUnit($request->id);
                    if (!empty($BU)) {
                        $name = $BU->vc_short_name;
                    }
                }
            } elseif ($request->target == 'depatment_name') {
                if (!empty($request->id)) {
                    $dept = $this->P2bService->getDepartment($request->id);
                    if (!empty($dept)) {
                        $name = $dept->vc_name;
                    }
                }
            } elseif ($request->target == 'project_name') {
                if (!empty($request->id)) {
                    $project = $this->P2bService->getProject($request->id);
                    if (!empty($project)) {
                        $name = $project->vc_name;
                    }
                }
            }
        }
        return response()->json($name);
        // return $name;
    }

    /**
     * close action
     * changes status of action
     * Send notification
     */

    public function close_action(Request $request)
    {
        try{
            $close_date = date('Y-m-d', strtotime($request->close_date));
            $action_id = $request->action_id;

            $Action = Action::find($action_id);
            // $Action->status = 4; // action is closed
            $Action->status = 3; // action is completed
            $Action->close_date = $close_date;
            $Action->comments = $request->comments;
            // $Action->closed_by = $request->close_by;
            $Action->closed_by = Auth::id();
            $user=CheckUserTypeAndGetRoleID(Auth::id());
            $Action->i_ref_closed_by_role_id=$user->role_id;
            $Action->update();

            $files = $request->file('evidence');
                if($request->hasFile('evidence') && !empty($files))
                {
                    foreach ($files as $file) {
                        $filename = Str::random(25) . '.' . $file->getClientOriginalExtension(); // RANDOM NAME
                        $file_type = $file->getClientMimeType();
                        $evidence_type = Evidence::TYPE_DOCUMENT;
                        if(str_contains($file_type, 'image')){
                            $evidence_type = Evidence::TYPE_IMAGE;
                        } else if(str_contains($file_type, 'audio')){
                            $evidence_type = Evidence::TYPE_AUDIO;
                        } else if(str_contains($file_type, 'pdf')){
                            $evidence_type = Evidence::TYPE_PDF;
                        } else if(str_contains($file_type, 'video')){
                            $evidence_type = Evidence::TYPE_VIDEO;
                        }

                        $destinationPath = 'evidences/';
                        if ($file->move($destinationPath, $filename)) {
                            
                            $Evidence = new Evidence();
                            $Evidence->action_id = $request->action_id;
                            $Evidence->file_name = $filename;
                            $Evidence->file_type = !empty($evidence_type) ? $evidence_type : null;
                            $Evidence->save();
                        }
                    }
                }
                $title = 'This action has been completed now.';
                $body = 'Action is Completed.';
                $notifyType = 31;
                $response = $this->ActionApi->notifyAssignedUser($body, $title, $request->action_id, $notifyType);
                if ($response != "") {
                    $notificationResp = json_decode($response);
                    $getAction = Action::find($request->action_id);
                    if ($getAction != "") {
                        // $assigneInfo = $this->P2bService->getUser($getAction->assined_user_id);
                        // $assigneInfo=role_has_user($getAction->i_ref_assined_role_id);
                        $assigneInfo = CheckUserType($getAction->i_ref_assined_role_id,$getAction->assined_user_id);
        
                        if ($assigneInfo != "") {
                            $messageText = 'Action is completed.';
                            // date_default_timezone_set('Asia/Kolkata');
                            $currentDateTime = strtotime(now()) * 1000;
                            // $assignee=CheckUserTypeAndGetRoleID($getAction->assined_user_id);
                            $assineRoleId=$assigneInfo['role_id'];
                            $assineName=$assigneInfo['full_name'];
                            $this->updateActionInFireStore((int) $request->action_id,$getAction->assined_user_id,$assineRoleId,$assineName, $messageText, $currentDateTime);
                        }
                    }
                    if ($notificationResp->success ==  1) {
                        return redirect()->route('actions')->with('success', 'Action Completed Successfully. Sent notification successfully!');
                    } else {
                        return redirect()->route('actions')->with('error', 'Action Completed Successfully. Failed to Send Notification. On Close Action device token not available!');
                    }
                } else {
                    return redirect()->route('actions')->with('error', 'Action Completed Successfully. Failed to Send Notification. On Close Action device token not available!');
                }
                    
        // }
        }catch(Exception $ex){
            return redirect()->route('actions')->with('error', $ex->getMessage());
        }
    }

/**
 *  get archive
 * action listing
 * **/
    public function getArchiveListing(Request $request)
    {

        $action_listings = Action::onlyTrashed()->with([
            'completedForm',
            'business_unit' => function($query){
                $query->select('id', 'vc_short_name');
            },
            'department' => function($query){
                $query->select('id', 'vc_name');
            },
            'project' => function($query){
                $query->select('id', 'vc_name');
            },
            'assignee_user'
        ])->orderBy('id', 'desc');
        if (auth()->check() && auth()->user()->user_type != 'company') {
            $action_listings = $action_listings->with([
                'user' => function ($query) {
                    $query->select('id', 'vc_fname', 'vc_mname', 'vc_lname');
                },
            ]);
            $userId = Auth::id();
            $useroleid=auth()->user()->users_details->i_ref_role_id;
            // $action_listings = $action_listings->whereRaw("(assined_user_id = $userId OR user_id = $userId)");
            $action_listings = $action_listings->whereRaw("(assined_user_id = $useroleid OR user_id = $useroleid)");
     
        }
        $action_listings = $action_listings->get();
        $assignUser = $this->P2bService->getAllUsersForCreateActions();

        $defaultOptions = [
            [
                'vc_fname' => '',
                'vc_mname' => '',
                'vc_lname' => '',
                'full_name' => 'Assignee'
            ]
        ];

        if(auth()->user()->user_type == 'supplier'){
            $defaultOptions = [
                [
                    'vc_fname' => '',
                    'vc_mname' => '',
                    'vc_lname' => '',
                    'full_name' => 'Assigned By'
                ]
            ];            
        }

        $usersAr = $assignUser->each->setAppends(['full_name'])->toArray();
        $users = array_merge($defaultOptions, $usersAr);

        $departments = $this->P2bService->getDepartments();
        $status = !empty($request['status'])?$request['status']:'';

        $active = 'archive_action';
        return view('admin.action.deleted-action', compact('action_listings', 'active'));
    }

    /**
     *  restore action
     */
    public function restore_action(Request $request)
    {
        if ($request->id) {
            $data = Action::withTrashed()->find($request->id);
            if ($data->restore()) {
                $today = date("Y-m-d");
                $Action = Action::find($request->id);
                $Action->close_date = $today;
                if ($Action->update()) {
                    $request->session()->flash('success', 'Action restored successfully!');
                } else {
                    $request->session()->flash('error', 'Failed to restore action!');
                }
            } else {
                $request->session()->flash('error', 'Failed to restore action!');
            }
        } else {
            $request->session()->flash('error', 'Failed to restore action!');
        }
    }

    /*
     * Save notification Details
     *
     */
    public function saveNotificationDetails($title, $notifyType, $actionId, $assigne)
    {
        $notificationSend = new Notification();
        $notificationSend->title = $title;
        $notificationSend->notification_type = $notifyType;
        $notificationSend->action_id = $actionId;
        $notificationSend->receiver_id = $assigne;
        $notificationSend->status = 1;
        $notificationSend->sent_at = now();
        $notificationSend->save();
        return;
    }

    /*
     * Update action status change in firestore
     *
     */
    public function updateActionInFireStore($actionId, $assigne,$assineRoleId,$assineName, $messageText, $currentDateTime)
    {
        $findAction = Action::find($actionId);
        if ($findAction != "") {
            $serviceAccount = __DIR__ . config('firebase.credentials.file');
            $db = new FirestoreClient([
                'keyFilePath' => $serviceAccount,
                'projectId' => 'p2b-app',
            ]);
            $chatRef = $db->collection('chat');
            $curTimestamp = $currentDateTime;
            $chatRef->document('room_' . $actionId)
                ->collection('messages')
                ->document('msg_' . $curTimestamp)
                ->set([
                    'action_id' => (int) $actionId,
                    'id' => $curTimestamp,
                    'is_read' => 0,
                    'media_type' => null,
                    'media_url' => null,
                    'message_text' => $messageText,
                    'sender_id' => (string) Auth::id(),
                    'sender_name' => (string) auth()->user()->full_name,
                    'sender_role_id' => (string) auth()->user()->users_details->i_ref_role_id,
                    'receiver_id' => (string) $assigne,
                    'receiver_name' => (string) $assineName,
                    'receiver_role_id' => (string) $assineRoleId,
                    'sent_at' => (int) $curTimestamp,
                    'thumbnail' => null,
                    'type' => 3,
                ]);
            return;
        } else {
            return;
        }
    }

    /*
     * Update action status in actions table
     * Add action status in firestore
     */
    public function updateActionStatus(Request $request)
    {
        $actionId = $request->input('action_id');
        $statusId = $request->input('status_id');
        $currentDateTime = $currentDateTime = strtotime(now()) * 1000;
        $findAction = Action::find((int) $actionId);
        if ($findAction != "" && $statusId != "") {
            if ($findAction->status == 3) {
                return $response = [
                    'message' => 'This Action is already completed.',
                    'type' => 'success',
                ];
            } else {
                $findAction->status = (int) $statusId;
                $findAction->update();
                // $assigneInfo = $this->P2bService->getUser($findAction->assined_user_id);
                // $assigneInfo=role_has_user($findAction->i_ref_assined_role_id);
                $assigneInfo = CheckUserType($findAction->i_ref_assined_role_id,$findAction->assined_user_id);
        
                if ($assigneInfo != "") {
                    $messageText = 'Action is completed.';
                    // $assignee=CheckUserTypeAndGetRoleID($findAction->assined_user_id);
                    $assineRoleId=$assigneInfo['role_id'];
                    $assineName=$assigneInfo['full_name'];
                    $this->updateActionInFireStore((int) $actionId,$findAction->assined_user_id,$assineRoleId,$assineName, $messageText, $currentDateTime);
                }
                return $response = [
                    'message' => 'Action Completed Successfully',
                    'type' => 'success',
                ];
            }
        } else {
            return $response = [
                'message' => 'Action Not Found',
                'type' => 'error',
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try{
            $id = encrypt_decrypt('decrypt', $request->id);
            Action::destroy($id);
            return $request->session()->flash('success', trans('Action archived successfully'));
        }catch(Exception $ex){
            return $request->session()->flash('error', $ex->getMessage());
        }
    }

    /**
     * Remove recurring from action
     *
     */
    public function remove_recurring(Request $request)
    {
        if (!empty($request['id']) && !empty($request['action_id'])) {
            $data = RecurringAction::find($request['id']);
            if ($data->delete()) {
                $action = Action::find($request['action_id']);
                $action->reocurring_actions = null;
                if ($action->update()) {
                    $request->session()->flash('success', 'Recurring removed successfully!');
                } else {
                    $request->session()->flash('error', 'Failed to remove Recurring!');
                }
            } else {
                $request->session()->flash('error', 'Failed to remove Recurring!');
            }
        } else {
            $request->session()->flash('error', 'Failed to remove Recurring!');

        }
    }

    /**
     * accept or reject 
     * action
     */
    public function accept_reject(Request $request, $id){
        try{
            $row = Action::findOrFail($id);
            $row->status = $request->status;
            $row->comments = $request->comments;
            $row->save();
            $notify_row = Notification::where('id', $request->notification_id)->firstOrFail();
            $notify_row->status = true;
            $notify_row->save();
           
            if($request->status == 5){
                $messageText = 'Action is rejected.';
            }else{
                $messageText = 'Action is accepted.';
            }
            $currentDateTime = $currentDateTime = strtotime(now()) * 1000;
            
            $assigneInfo = CheckUserType($row->i_ref_assined_role_id,$row->assined_user_id);

            if ($assigneInfo != "") {
                $assineRoleId=$assigneInfo['role_id'];
                $assineName=$assigneInfo['full_name'];
                $this->updateActionInFireStore((int) $id,$row->assined_user_id,$assineRoleId,$assineName, $messageText, $currentDateTime);
            }

            if($request->status == 5){
                return $this->returnResponse(HTTP_STATUS_OK, true, trans('response.action_rejected'));
            }else{
                return $this->returnResponse(HTTP_STATUS_OK, true, trans('response.action_accepted'));
            }
        }catch (\Exception $ex) {
            return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, $ex->getMessage());
        }
    }

    public function getDynamicChatSection($documents, $sender = '')
    {
        
        $actionChatMessages=$documents;
        if (count($documents) > 0) {
            $senderUserInfo="";
            $tempArrayUsers = [];
            foreach ($actionChatMessages as $index => $value) {
                $chatInfo[$index]['document'] = $actionChatMessages[$index];
            }
        }

        //$senderId = $findChat->user_id; //Action User Id.
        // $sender = 204; //static user Id
        $actionCreatedUser = $this->P2bService->getUser($sender);
        $appendData = view('admin.action.firebase-dynamic-chat', compact('chatInfo', 'actionCreatedUser'))->render();
        return $appendData;
    }


    function getactionchat(Request $request){
        
        // dd($request->all());
        $actionId=$request->messages[0]['action_id'];
        $findChat = Action::find($actionId);
        	if(!empty($findChat)){
                $appendData = $this->getDynamicChatSection($request->messages, $findChat->user_id);
                return $response = [
                    'document' => $appendData,
                    'message' => 'Messages Get Successfully',
                    'type' => 'success',
                    'actionId' => $actionId,
                ];
            }else{
                return $response = [
                    'document' => "",
                    'message' => 'Chat NOt FOUNd',
                    'type' => 'FAILED',
                    'actionId' => $actionId,
                ];
            }
        

    }

}
