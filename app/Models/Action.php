<?php

namespace App\Models;
use Auth;
// use GPBMetadata\Google\Api\Auth;
// use Illuminate\Support\Facades\Auth;
use App\Services\P2B as P2BService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
class Action extends Model
{

    protected $connection = 'mysql';

    use SoftDeletes;
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'action_id', 'template_id', 'completed_form_id', 'section_id', 'question_id', 'title', 'descriptions', 'user_id', 'assined_user_id', 'company_id', 'business_unit_id', 'department_id', 'project_id', 'location_id', 'reocurring_actions',
        'status', 'comment', 'priority', 'due_date', 'close_date', 'closed_by', 'comments', 'evidence_id', 'type', 'is_notified','i_ref_user_role_id','i_ref_assined_role_id','i_ref_closed_by_role_id'
    ];

    /**
     * Status
     */

    const PENDING = 1;
    const IN_PROGRESS = 2;
    const COMPLETED = 3;
    const CLOSED = 4;
    const REJECTED = 5;
    const OVERDUE = 6;
    /**
     * Status colors
     */
    const COLOR_PENDING = "#007bff";
    const COLOR_IN_PROGRESS = "#ffc107";
    const COLOR_COMPLETED = "#1ea01e";
    const COLOR_CLOSED = "#800000";
    const COLOR_REJECTED = "#E4402B";
    const COLOR_OVERDUE = "#FF7A01";

    
    /**
     * Status Array
     */

    public $statusArray = [
        1 => "Pending",
        2 => "In-progress",
        3 => "Completed",
        4 => "Completed",
        5 => "Rejected",
        6 => "Overdue",
    ];

    /**
     * Action Status Array
     */

    public $actionStatusArray = [
        1 => "Pending",
        2 => "In-progress",
        5 => "Rejected"
    ];

    /**
     * Status Color Array
     */

    public $statusColorArray = [
        Action::PENDING => Action::COLOR_PENDING,
        Action::IN_PROGRESS => Action::COLOR_IN_PROGRESS,
        Action::COMPLETED => Action::COLOR_COMPLETED,
        Action::CLOSED => Action::COLOR_CLOSED,
        Action::REJECTED => Action::COLOR_REJECTED,
        Action::OVERDUE => Action::COLOR_OVERDUE,
    ];

    /**
     * Priority Array
     */
    public $priorityArray = [
        1 => "Low",
        2 => "Medium",
        3 => "High",
    ];

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
     * Get the status name.
     * @return string
     */
    public function getStatusNameAttribute()
    {
        return isset($this->statusArray[$this->status]) && !empty($this->statusArray[$this->status]) ? $this->statusArray[$this->status] : 'Pending';
    }

    /**
     * Get the status name.
     * @return string
     */
    public function getStatusColorAttribute()
    {
        return isset($this->statusColorArray[$this->status]) && !empty($this->statusColorArray[$this->status]) ? $this->statusColorArray[$this->status] : Action::COLOR_PENDING;
    }

    /**Overdue
     * Get the Priority name.
     * @return string
     */
    public function getPriorityNameAttribute()
    {
        return isset($this->priorityArray[$this->priority]) && !empty($this->priorityArray[$this->priority]) ? $this->priorityArray[$this->priority] : '';
    }

    /**
     * get status Array
     */
    static public function getStatusArray()
    {
        $classObj = new Action();
        return $classObj->statusArray;
    }

    /**
     * get status Array
     */
    static public function getActionStatusArray()
    {
        $classObj = new Action();
        return $classObj->actionStatusArray;
    }

    /**
     * get status Array
     */
    static public function getPriorityArray()
    {
        $classObj = new Action();
        return $classObj->priorityArray;
    }


    public function completedForm()
    {
        return $this->belongsTo(CompletedForm::class);
    }

    public function recurring_actions()
    {
        return $this->belongsTo(RecurringAction::class, 'action_id', 'id');
    }

    // public function sections() {
    //   return $this->belongsTo(Section::class, 'section_id', 'id');
    // }

    public function questions()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    public function assignee_user()
    {
        return $this->belongsTo(Users::class, 'assined_user_id', 'id');
    }

    // public function assinee_user()
    // {
    //     return $this->belongsTo(Role::class, 'i_ref_', 'id');
    // }


    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function close_by()
    {
        return $this->belongsTo(Users::class, 'closed_by', 'id');
    }

    public function business_unit()
    {
        return $this->belongsTo(Business_unit::class, 'business_unit_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    /**
     * Get the action's evidences
     */
    public function evidences()
    {
        return $this->hasMany(Evidence::class, 'action_id', 'id');
    }
    /**
     * Get the user's image.
     * Get the action's notifications
     */
    public function notifications()
    {
        return $this->morphOne(Notification::class, 'notificationable');
    }

    /**
     *
     */
    public static function checkQuestionPermission($completed_form_id, $question_id, $section_id, $user_id)
    {
        return Action::where('completed_form_id', $completed_form_id)->where('question_id', $question_id)->where('assined_user_id', $user_id)->where('section_id', $section_id)->exists();
    }

    /**
     *
     */
    public static function checkeditPermission($action_id, $user_id)
    {
        return Action::where('id', $action_id)->where('assined_user_id', $user_id)->exists();
    }


    public function ActionDocuments()
    {
        return $this->hasMany(ActionDocument::class, 'action_id', 'id');
    }

    public static function get_actions_for_chat($user_id){
       
        return Action::select('id')->where('user_id', $user_id)->orwhere('assined_user_id', $user_id)->get();
    }

    
    /**
     * The "booted" method of the model.
     *
     * @return void
     */

    protected static function booted()
    {   

        parent::boot();
        
        $preDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-1 month"));
        $today = date("Y-m-d");
        if(auth()->check()){
            
            $i_ref_company_id=auth()->user()->users_details->i_ref_company_id;

           static::addGlobalScope('ancient', function (Builder  $builder) use ($i_ref_company_id) {
                $builder->where('actions.company_id', $i_ref_company_id);
            });
        }
        // delete
        $close_action_listings = Action::where('close_date', '<=', $preDate)->delete();
        // update status to 6
        $actions = Action::where('due_date', '<', $today);
        $actions = $actions->whereRaw("(status = 1 or status = 2 )")->update(['status' => 6]);

        static::creating(function ($action) {
            $action->company_id = auth()->user()->users_details->i_ref_company_id;
            $action->i_ref_user_role_id = auth()->user()->users_details->i_ref_role_id;
            $assined_role_id=CheckUserTypeAndGetRoleID($action->assined_user_id);
            if(!empty($assined_role_id)){
                $action->i_ref_assined_role_id = $assined_role_id['role_id']; 
            }
        });

        static::created(function ($action) {
            /**
             * Save Assign action notification
             */
            $assigne=CheckUserTypeAndGetRoleID($action->assined_user_id);
            $assineRoleId=$assigne->role_id;
            if (!is_null($action->completed_form_id)) {
                $input['title'] = trans("notifications.completedform_title");
                $input['message'] = trans("notifications.completedform", [
                    "name" => $action->user->full_name,
                ]);
                $input['user_id'] = $action->assined_user_id;
                $input['from_user_id'] = $action->user_id;
                $input['i_ref_user_role_id'] = $assineRoleId;
                $input['i_ref_from_user_role_id'] = auth()->user()->users_details->i_ref_role_id;
                $input['notification_type'] = Notification::COMPLETED_FORM;
                $input['notificationable_id'] = $action->completed_form_id;
                $input['notificationable_type'] = Notification::TYPE_COMPLETEDFORM;
                Notification::create($input);
            }

            if (is_null($action->reocurring_actions)) {
                $input['title'] = trans("notifications.action_created_title");
                $input['message'] = trans("notifications.action_created", [
                    "name" => $action->user->full_name,
                    "title" => $action->title,
                ]);
                $input['user_id'] = $action->assined_user_id;
                $input['from_user_id'] = $action->user_id;
                $input['i_ref_user_role_id'] = $assineRoleId;
                $input['i_ref_from_user_role_id'] = auth()->user()->users_details->i_ref_role_id;
                $input['notification_type'] = Notification::CREATE_ACTION;
                $action->notifications()->create($input);
            }
        });
        static::updating(function ($action) {
            // dd($action);
            // $assined_role_id=CheckUserTypeAndGetRoleID($action->assined_user_id);
            // $assined_role_id= CheckUserType($action->i_ref_assined_role_id,$action->assined_user_id);
            // dd($assined_role_id);
            // if(!empty($assined_role_id)){
            //     $action->i_ref_assined_role_id = $assined_role_id['role_id']; 
            // }
        });

        
        /**
         * Show
         */
        static::updated(function ($action) {
           
            // dd(Role::with(['role_has_user'])->find(57));
            if ($action->isDirty('status')) {
                $input = []; 
                // $P2bService = new P2BService();
                // $user=role_has_user($action->i_ref_user_role_id);
                // $assigne=role_has_user($action->i_ref_assined_role_id);
                
                $user = CheckUserType($action->i_ref_user_role_id,$action->user_id);
                $assigne = CheckUserType($action->i_ref_assined_role_id,$action->assined_user_id);
                
                $assineRoleId=$assigne['role_id']; 
                $assineUserId=$assigne['user_id']; 
                $userRoleId=$user['role_id'];  
                $userId=$user['user_id'];  
                // $assineRoleId="";
                // $assineUserId="";
                // $userRoleId="";
                // $userId="";
                switch ($action->status) {
                    case (3):
                        # code...
                        if (auth()->user()->users_details->i_ref_role_id == $action->i_ref_user_role_id) {
                            $input['user_id'] = $assineUserId;
                            $input['from_user_id'] = auth()->user()->id;
                            $input['i_ref_user_role_id'] = $assineRoleId;
                            $input['i_ref_from_user_role_id'] = auth()->user()->users_details->i_ref_role_id;
                            $input['notification_type'] = Notification::UPDATE_ACTION;
                            $input['title'] = trans("notifications.action_completed_title");
                            $input['message'] = trans("notifications.action_completed", [
                                "name" => auth()->user()->full_name,
                                "status" => $action->status_name,
                                "title" => $action->title,
                            ]);
                        } else {
                            $input['user_id'] = $userId;
                            $input['from_user_id'] = auth()->user()->id;
                            $input['i_ref_user_role_id'] = $userRoleId;
                            $input['i_ref_from_user_role_id'] = auth()->user()->users_details->i_ref_role_id;
                            $input['notification_type'] = Notification::UPDATE_ACTION;
                            $input['title'] = trans("notifications.action_completed_title");
                            $input['message'] = trans("notifications.action_completed", [
                                "name" => auth()->user()->full_name,
                                "status" => $action->status_name,
                                "title" => $action->title,
                            ]);
                        }
                        break;
                    case (4):
                        # code...
                        if (auth()->user()->users_details->i_ref_role_id == $action->i_ref_user_role_id) {
                            $input['user_id'] = $assineUserId;
                            $input['from_user_id'] =  auth()->user()->id;
                            $input['i_ref_user_role_id'] = $assineRoleId;
                            $input['i_ref_from_user_role_id'] = auth()->user()->users_details->i_ref_role_id;
                            $input['notification_type'] = Notification::CLOSE_ACTION;
                            $input['title'] = trans("notifications.action_closed_title");
                            $input['message'] = trans("notifications.action_closed", [
                                "name" => auth()->user()->full_name,
                                "status" => $action->status_name,
                                "title" => $action->title,
                            ]);
                        } else {
                            $input['user_id'] = $userId;
                            $input['from_user_id'] = auth()->user()->id;
                            $input['i_ref_user_role_id'] = $userRoleId;
                            $input['i_ref_from_user_role_id'] = auth()->user()->users_details->i_ref_role_id;
                            $input['notification_type'] = Notification::CLOSE_ACTION;
                            $input['title'] = trans("notifications.action_closed_title");
                            $input['message'] = trans("notifications.action_closed", [
                                "name" => auth()->user()->full_name,
                                "status" => $action->status_name,
                                "title" => $action->title,
                            ]);
                        }
                        break;
                    case (5):
                        # code...
                        if (auth()->user()->users_details->i_ref_role_id == $action->i_ref_user_role_id) {
                            $input['user_id'] = $assineUserId;
                            $input['from_user_id'] = auth()->user()->id;
                            $input['i_ref_user_role_id'] = $assineRoleId;
                            $input['i_ref_from_user_role_id'] = auth()->user()->users_details->i_ref_role_id;
                            $input['notification_type'] = Notification::UPDATE_ACTION;
                            $input['title'] = trans("notifications.action_rejected_title");
                            $input['message'] = trans("notifications.action_closed", [
                                "name" => auth()->user()->full_name,
                                "status" => $action->status_name,
                                "title" => $action->title,
                            ]);
                        } else {
                            $input['user_id'] = $userId;
                            $input['from_user_id'] = auth()->user()->id;
                            $input['i_ref_user_role_id'] = $userRoleId;
                            $input['i_ref_from_user_role_id'] = auth()->user()->users_details->i_ref_role_id;
                            $input['notification_type'] = Notification::UPDATE_ACTION;
                            $input['title'] = trans("notifications.action_rejected_title");
                            $input['message'] = trans("notifications.action_closed", [
                                "name" => auth()->user()->full_name,
                                "status" => $action->status_name,
                                "title" => $action->title,
                            ]);
                        }
                        break;
                    case (6):
                        # code...
                        if (auth()->user()->users_details->i_ref_role_id == $action->i_ref_user_role_id) {
                            $input['user_id'] = $assineUserId;
                            $input['from_user_id'] = auth()->user()->id;
                            $input['i_ref_user_role_id'] = $assineRoleId;
                            $input['i_ref_from_user_role_id'] = auth()->user()->users_details->i_ref_role_id;
                            $input['notification_type'] = Notification::UPDATE_ACTION;
                            $input['title'] = trans("notifications.action_overdue_title");
                            $input['message'] = trans("notifications.action_overdue", [
                                "name" => auth()->user()->full_name,
                                "status" => $action->status_name,
                                "title" => $action->title,
                            ]);
                        } else {
                            $input['user_id'] = $userId;
                            $input['from_user_id'] = auth()->user()->id;
                            $input['i_ref_user_role_id'] = $userRoleId;
                            $input['i_ref_from_user_role_id'] = auth()->user()->users_details->i_ref_role_id;
                            $input['notification_type'] = Notification::UPDATE_ACTION;
                            $input['title'] = trans("notifications.action_overdue_title");
                            $input['message'] = trans("notifications.action_overdue", [
                                "name" => auth()->user()->full_name,
                                "status" => $action->status_name,
                                "title" => $action->title,
                            ]);
                        }
                        break;
                    case (2):
                            # code...
                        if (auth()->user()->users_details->i_ref_role_id == $action->i_ref_user_role_id) {
                            $input['user_id'] = $assineUserId;
                            $input['from_user_id'] = auth()->user()->id;
                            $input['i_ref_user_role_id'] = $assineRoleId;
                            $input['i_ref_from_user_role_id'] = auth()->user()->users_details->i_ref_role_id;
                            $input['notification_type'] = Notification::UPDATE_ACTION;
                            $input['title'] = trans("notifications.action_accept_title");
                            $input['message'] = trans("notifications.action_accept", [
                                "name" => auth()->user()->full_name,
                                "status" => $action->status_name,
                                "title" => $action->title,
                            ]);
                        } else {
                            $input['user_id'] = $userId;
                            $input['from_user_id'] = auth()->user()->id;
                            $input['i_ref_user_role_id'] = $userRoleId;
                            $input['i_ref_from_user_role_id'] = auth()->user()->users_details->i_ref_role_id;
                            $input['notification_type'] = Notification::UPDATE_ACTION;
                            $input['title'] = trans("notifications.action_accept_title");
                            $input['message'] = trans("notifications.action_accept", [
                                "name" => auth()->user()->full_name,
                                "status" => $action->status_name,
                                "title" => $action->title,
                            ]);
                        }
                        break;
                    case (1):
                            # code...
                        // if (auth()->user()->users_details->i_ref_role_id == $action->i_ref_user_role_id) {
                        //     $input['user_id'] = $assineUserId;
                        //     $input['from_user_id'] = auth()->user()->id;
                        //     $input['i_ref_user_role_id'] = $assineRoleId;
                        //     $input['i_ref_from_user_role_id'] = auth()->user()->users_details->i_ref_role_id;
                        //     $input['notification_type'] = 30;
                        //     $input['title'] = trans("notifications.action_reassigned_title");
                        //     $input['message'] = trans("notifications.action_reassigned", [
                        //         "name" => auth()->user()->full_name,
                        //         "title" => $action->title,
                        //     ]);
                        // } else {
                        // }
                        break;
                
                        default:
                        # code...
                        break;
                }
                if (!empty($input)) {
                    $action->notifications()->create($input);
                }
            }
        });
    }
}
