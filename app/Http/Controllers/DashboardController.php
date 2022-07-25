<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\CompletedForm;
use App\Models\Document;
use App\Models\Template;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $result = $this->getDashboard($request);
        return view('admin.dashboard.index', $result);
    }

    public function showformData(Request $request)
    {
        $result = $this->getDashboard($request);

        $action_listings = $result['action_listings'];
        $action_listings = view('partials.dashboard-action_listings', compact('action_listings'))->render();

        $form_listings = $result['form_listings'];
        $form_listings = view('partials.dashboard-form_listings', compact('form_listings'))->render();

        $doc_listings = $result['doc_listings'];
        $doc_listings = view('partials.dashboard-doc_listings', compact('doc_listings'))->render();

        return array('form' => $result['template_forms'], 'incompleted' => $result['incompleted_actions'], 'completed' => $result['completed_actions'], 'overdue' => $result['overdue_actions'], 'action_listing' => $action_listings, 'form_listing' => $form_listings, 'doc_listing' => $doc_listings);
    }

    /**
     * get Dashboard data
     */
    private function getDashboard(Request $request)
    {
        $action_listings = '';
        $form_listings = '';
        $doc_listings = '';
        $template_forms = '';
        $incompleted_actions = '';
        $completed_actions = '';
        $overdue_actions = '';
        $userId = Auth::id();
        $active = 'dashboard';
        $roleId=auth()->user()->users_details->i_ref_role_id;

        $action_listings = Action::select('id', 'completed_form_id', 'assined_user_id', 'status', 'title')->orderBy('id', 'desc')->take(5);

        $form_listings = CompletedForm::with([
            'Template' => function ($query) {
                $query->select('id', 'template_name');
            },
            // 'completed_by' => function ($query) {
            //     $query->select('id', 'vc_fname', 'vc_mname', 'vc_lname');
            // },
        ])->select('id', 'template_id', 'user_id', 'form_id', 'title')->orderBy('id', 'desc')->where('status', CompletedForm::COMPLETED)->take(5);

        $doc_listings = Document::with([
            // 'owner' => function ($query) {
            //     $query->select('id', 'vc_fname', 'vc_mname', 'vc_lname');
            // },
            'category' => function ($query) {
                $query->select('id', 'name');
            },
        ])->select('id', 'title', 'owner_id', 'category_id', 'file_name','file_type')->orderBy('id', 'desc')->take(5);

        $template_forms = Template::select('id', 'template_prefix')->withTrashed();

        $incompleted_actions = Action::select('id')->whereStatus(Action::IN_PROGRESS);

        $completed_actions = Action::select('id')->whereStatus(Action::COMPLETED);

        //get overdue actions for pie chart
        $overdue_actions = Action::select('id')->whereStatus(Action::OVERDUE);

        if ($request->user()->user_type != 'company') {
            //get all action

            if ($request->user()->user_type == 'supplier') {
                $shared_doc = 'share_with_supplier';
            } else {
                $shared_doc = 'Use_in_mobile';
            }

            $action_listings = $action_listings->with([
                'completedForm' => function ($q) {
                    $q->select('id', 'form_id');
                },
            ]);

            if ($request->user()->user_type == 'supplier') {
                $action_listings = $action_listings->whereRaw("((assined_user_id = $userId AND status != 1 AND status != 5))");
            } else {
                $action_listings = $action_listings->whereRaw("((i_ref_assined_role_id = $roleId AND status != 1 AND status != 5))");
            }

            // get all completed forms

            if ($request->user()->user_type == 'supplier') {
                $form_listings = $form_listings->where('user_id', Auth::id());
            } else {
                $form_listings = $form_listings->where('i_ref_user_role_id', $roleId);
            }


            //get all documents
            if ($request->user()->user_type == 'supplier') {
                
                $doc_listings = $doc_listings->whereRaw("(owner_id = $userId )");
            } else {
                $doc_listings = $doc_listings->whereRaw("(i_ref_owner_role_id = $roleId )");
            }

            //get incompleted actions for pie chart
            if ($request->user()->user_type == 'supplier') {
                $incompleted_actions->whereRaw("((assined_user_id = $userId AND status != 1 AND status != 5) )");
            } else {
                $incompleted_actions->whereRaw("((i_ref_assined_role_id = $roleId AND status != 1 AND status != 5) )");
            }


            //get completed actions for pie chart
            if ($request->user()->user_type == 'supplier') {
                $completed_actions = $completed_actions->whereRaw("((assined_user_id = $userId AND status != 1 AND status != 5) )");
            } else {
                $completed_actions = $completed_actions->whereRaw("((i_ref_assined_role_id = $roleId AND status != 1 AND status != 5) )");
            }

            //get overdue actions for pie chart
            if ($request->user()->user_type == 'supplier') {
                $overdue_actions = $overdue_actions->whereRaw("((assined_user_id = $userId AND status != 1 AND status != 5) )");
            } else {
                $overdue_actions = $overdue_actions->whereRaw("((i_ref_assined_role_id = $roleId AND status != 1 AND status != 5) )");
            }

        } else {
            $action_listings = $action_listings;
        }

        /**
         * @var For 6 months
         */
        if (isset($request['view']) && $request['view'] == 6) {
            $template_forms = $template_forms->withCount('completed_forms_months');
            $action_listings = $action_listings->whereDate('created_at', '>', Carbon::now()->subMonths(6));
            $form_listings = $form_listings->whereDate('created_at', '>', Carbon::now()->subMonths(6));
            $doc_listings = $doc_listings->whereDate('created_at', '>', Carbon::now()->subMonths(6));
            $incompleted_actions = $incompleted_actions->whereDate('created_at', '>', Carbon::now()->subMonths(6));
            $completed_actions = $completed_actions->whereDate('created_at', '>', Carbon::now()->subMonths(6));
            $overdue_actions = $overdue_actions->whereDate('created_at', '>', Carbon::now()->subMonths(6));
            
        } else if (isset($request['view']) && $request['view'] == 1) {
            /**
             * @var 1 Year
             */
            $template_forms = $template_forms->withCount('completed_forms_year');
            $action_listings = $action_listings->where('created_at', '>', Carbon::now()->subYear());
            $form_listings = $form_listings->where('created_at', '>', Carbon::now()->subYear());
            $doc_listings = $doc_listings->where('created_at', '>', Carbon::now()->subYear());
            $incompleted_actions = $incompleted_actions->where('created_at', '>', Carbon::now()->subYear());
            $completed_actions = $completed_actions->where('created_at', '>', Carbon::now()->subYear());
            $overdue_actions = $overdue_actions->where('created_at', '>', Carbon::now()->subYear());
        } else {
            /**
             * @var For default OR 30 days
             */
            $template_forms = $template_forms->withCount('completed_forms_days');
            $action_listings = $action_listings->whereDate('created_at', '>', Carbon::now()->subDays(30));
            $form_listings = $form_listings->whereDate('created_at', '>', Carbon::now()->subDays(30));
            $doc_listings = $doc_listings->whereDate('created_at', '>', Carbon::now()->subDays(30));
            $incompleted_actions = $incompleted_actions->whereDate('created_at', '>', Carbon::now()->subDays(30));
            $completed_actions = $completed_actions->whereDate('created_at', '>', Carbon::now()->subDays(30));
            $overdue_actions = $overdue_actions->whereDate('created_at', '>', Carbon::now()->subDays(30));
        }

        $doc_listings = $doc_listings->get();
        $action_listings = $action_listings->get();

        $form_listings = $form_listings->get();
        $template_forms = $template_forms->get();
        $incompleted_actions = $incompleted_actions->count();
        $completed_actions = $completed_actions->count();
        $overdue_actions = $overdue_actions->count();
        // dd($doc_listings);
        // dd($doc_listings[0]->Doclink);


        return compact('active', 'action_listings', 'form_listings', 'doc_listings', 'template_forms', 'incompleted_actions', 'completed_actions', 'overdue_actions');

    }
}
