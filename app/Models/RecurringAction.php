<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecurringAction extends Model
{
    use SoftDeletes;
    /**
     * Set Table name
     */
    protected $table = 'recurring_action';

    /**
     * Set Week days name numbers
     */
    public $weekDayName = [
        1 => "Sunday",
        2 => "Monday",
        3 => "Tuesday",
        4 => "Wednesday",
        5 => "Thursday",
        6 => "Friday",
        7 => "Saturday",
    ];
    /**
     * Set Week days name numbers
     */
    public $monthNames = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July ',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ];

    /**
     * Set Week days name numbers
     */
    public $namesArray = [
        1 => "First",
        2 => "Second",
        3 => "Third",
        4 => "Fourth",
    ];

    /**
     * Set Week days name numbers
     */
    public $RecurringPatterns = [
        1 => "Daily",
        2 => "Weekly",
        3 => "Monthly",
        4 => "Yearly",
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'action_id', 'recurrence_type', 'day', 'week', 'month', 'start_date', 'end_date', 'next_notify_date',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
        'next_notify_date' => 'datetime:Y-m-d',
    ];

    /**
     * Get the recurrence type name.
     * @return string
     */
    public function getRecurrenceTypeNameAttribute()
    {
        return $this->recurrence_type != null && isset($this->RecurringPatterns[$this->recurrence_type]) ? $this->RecurringPatterns[$this->recurrence_type] : '';
    }
    /**
     *
     */
    public function action()
    {
        return $this->belongsTo(Action::class, 'action_id', 'id');
    }
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($action) {
            $today = Carbon::now();
            switch ($action->recurrence_type) {
                case (1):
                    $action->next_notify_date = $today->addDays($action->day);
                    # code...
                    break;
                case (2):
                    # code...
                    $days = explode(',', $action->day);
                    $weekDayNames = $action->weekDayName;
                    $currentDayName = $today->format('l');
                    $startKey = array_search($currentDayName, $weekDayNames);
                    $nextDayKey = $days[0];
                    for ($i = ($startKey + 1); $i <= 7; $i++) {
                        if (in_array($i, $days)) {
                            $nextDayKey = $i;
                            break;
                        }
                    }
                    $nextDayname = isset($weekDayNames[$nextDayKey]) ? $weekDayNames[$nextDayKey] : 'Sunday';
                    $action->next_notify_date = $today->next($nextDayname);
                    break;
                case (3):
                    if (!is_null($action->week)) {
                        $next = $today;
                        $month = $next->format('F');
                        $year = $next->format('Y');
                        $weekDayName = $action->weekDayName;
                        $names = $action->namesArray;
                        $week = isset($weekDayName[$action->week]) ? $weekDayName[$action->week] : 'Sunday';
                        $day = isset($names[$action->day]) ? $names[$action->day] : 'First';
                        $newDate = (new Carbon("$day $week of $month $year"));
                        if ($today->greaterThan($newDate)) {
                            $next = $today->addMonths($action->month);
                            $month = $next->format('F');
                            $year = $next->format('Y');
                            $newDate = (new Carbon("$day $week of $month $year"));
                        }
                        $action->next_notify_date = $newDate;
                    } else {
                        $next = $today;
                        $month = $next->format('m');
                        $year = $next->format('Y');
                        $day = $action->day;
                        $newDate = (Carbon::createFromDate($year, $month, $day));
                        if ($today->greaterThan($newDate)) {
                            $next = $today->addMonths($action->month);
                            $month = $next->format('m');
                            $year = $next->format('Y');
                            $newDate = (Carbon::createFromDate($year, $month, $day));
                        }
                        $action->next_notify_date = $newDate;
                    }
                    break;

                case (4):
                    $next = $today;
                    $year = $next->format('Y');
                    $months = $action->monthNames;
                    $names = $action->namesArray;
                    if (!is_null($action->week)) {
                        $day = isset($weekDayName[$action->week]) ? $weekDayName[$action->week] : 'Sunday';
                        $month = isset($months[$action->month]) ? $months[$action->month] : 'January';
                        $week = isset($names[$action->day]) ? $names[$action->day] : 'First';
                        $newDate = (new Carbon("$week $day of $month $year"));
                        if ($today->greaterThan($newDate)) {
                            $next = $today->addYears(1);
                            $year = $next->format('Y');
                            $newDate = (new Carbon("$week $day of $month $year"));
                        }
                        $action->next_notify_date = $newDate;
                    } else {
                        $newDate = (Carbon::createFromDate($year, $action->month, $action->day));
                        if ($today->greaterThan($newDate)) {
                            $next = $today->addYears(1);
                            $month = $next->format('m');
                            $year = $next->format('Y');
                            $newDate = (Carbon::createFromDate($year, $action->month, $action->day));
                        }
                        $action->next_notify_date = $newDate;
                    }
                    break;
                default:
                    break;
            }
        });

        /**
         * Save Assign action notification
         */

         
        static::created(function ($action) {
            // dd("asd");
            $assigne=CheckUserTypeAndGetRoleID($action->action->assined_user_id);
            $assineRoleId=$assigne['role_id'];
            // dd($assigneeeee);
            $input['user_id'] = $action->action->assined_user_id;
            $input['from_user_id'] = $action->action->user_id;
            $input['notification_type'] = 30;
            $input['title'] = "New Recurring action assigned";
            $input['i_ref_user_role_id'] = $assineRoleId;
            $input['i_ref_from_user_role_id'] = auth()->user()->users_details->i_ref_role_id;    
            $input['message'] = sprintf("%s has create a recurring action %s will occur every %s from %s To %s", auth()->user()->full_name, $action->action->title, $action->recurrence_type_name, $action->start_date->format("d M, Y"), $action->end_date->format("d M, Y"));
            $action->notifications()->create($input);
        });
    }
    /**
     * Get the notifications.
     */
    public function notifications()
    {
        return $this->morphOne(Notification::class, 'notificationable');
    }
}
