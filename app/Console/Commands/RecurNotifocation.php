<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\V1\ActionsController as ActionApi;
use App\Models\Action;
use App\Models\RecurringAction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RecurNotifocation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recurring:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send push to recurring action';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->ActionApi = new ActionApi();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::now();
        $todayDate = $today->format('Y-m-d');
        $rows = RecurringAction::whereDate('next_notify_date', $todayDate)->get();

        // print_r($rows->toArray());
        // exit;

        /**
         * Send
         */
        foreach ($rows as $key => $value) {
            switch ($value->recurrence_type) {
                # Send notification for recurrence_type = 1
                case (1):
                    /**
                     * Send Push notification
                     */
                    $body = 'This action is assigned to you.';
                    $title = 'New action assigned';
                    $findAction = Action::find($value->action_id);
                    $notifyType = 30;
                    $response = $this->ActionApi->notifyAssignedUser($body, $title, $value->action_id, $notifyType);

                    /**
                     * Update to next date
                     */
                    $value->next_notify_date = $value->next_notify_date->addDays($value->day);
                    if ($value->end_date->greaterThan($value->next_notify_date)) {
                        $value->save();
                        Log::debug(json_encode($value));
                    } else {
                        $value->delete();
                    }
                    break;

                case (2):
                    /**
                     * Send Push notification
                     */
                    $body = 'This action is assigned to you.';
                    $title = 'New action assigned';
                    $findAction = Action::find($value->action_id);
                    $notifyType = 30;
                    $response = $this->ActionApi->notifyAssignedUser($body, $title, $value->action_id, $notifyType);
                    
                    /**
                     * Update to next date
                     */
                    $days = explode(',', $value->day);
                    $weekDayNames = $value->weekDayName;
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
                    $value->next_notify_date = $today->next($nextDayname);
                    if ($value->end_date->greaterThan($value->next_notify_date)) {
                        $value->save();
                        Log::debug(json_encode($value));
                    } else {
                        $value->delete();
                    }
                    break;
                case (3):
                    # code...
                    /**
                     * Send Push notification
                     */
                    $body = 'This action is assigned to you.';
                    $title = 'New action assigned';
                    $findAction = Action::find($value->action_id);
                    $notifyType = 30;
                    $response = $this->ActionApi->notifyAssignedUser($body, $title, $value->action_id, $notifyType);
                    /**
                     * Update to next date
                     */
                    if (!is_null($value->week)) {
                        $next = $today->addMonths($value->month);
                        $month = $next->format('F');
                        $year = $next->format('Y');
                        $weekDayName = $value->weekDayName;
                        $week = isset($weekDayName[$value->week]) ? $weekDayName[$value->week] : 'Sunday';
                        $day = isset($names[$value->day]) ? $names[$value->day] : 'First';
                        $value->next_notify_date = (new Carbon("$day $week of $month $year"));
                        if ($value->end_date->greaterThan($value->next_notify_date)) {
                            $value->save();
                            Log::debug(json_encode($value));
                        } else {
                            $value->delete();
                        }
                    } else {
                        $next = $today->addMonths($value->month);
                        $month = $next->format('m');
                        $year = $next->format('Y');
                        $day = $value->day;
                        $value->next_notify_date = (Carbon::createFromDate($year, $month, $day));

                        if ($value->end_date->greaterThan($value->next_notify_date)) {
                            $value->save();
                            Log::debug(json_encode($value));
                        } else {
                            $value->delete();
                        }
                    }
                    break;

                case (4):
                    # code...
                    /**
                     * Send Push notification
                     */
                    $body = 'This action is assigned to you.';
                    $title = 'New action assigned';
                    $findAction = Action::find($value->action_id);
                    $notifyType = 30;
                    $response = $this->ActionApi->notifyAssignedUser($body, $title, $value->action_id, $notifyType);
                    /**
                     * Update to next date
                     */
                    $next = $today->addYears(1);
                    $year = $next->format('Y');
                    $months = $value->monthNames;
                    $names = $value->namesArray;
                    $day = isset($weekDayName[$value->week]) ? $weekDayName[$value->week] : 'Sunday';
                    $month = isset($months[$value->month]) ? $months[$value->month] : 'January';
                    $week = isset($names[$value->day]) ? $names[$value->day] : 'First';
                    $value->next_notify_date = (new Carbon("$week $day of $month $year"));
                    if ($value->end_date->greaterThan($value->next_notify_date)) {
                        $value->save();
                        Log::debug(json_encode($value));
                    } else {
                        $value->delete();
                    }
                    break;
                default:
                    break;
            }
        }

        // Log::debug(json_encode($value));
        echo "Done";
        return true;
    }
}

