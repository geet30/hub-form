<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Locations;
use App\Models\Department;
use App\Models\Project;
use App\Models\Business_unit;
use Auth;
use App\Services\P2B as P2BService;

class ProjectController extends Controller
{

    public function __construct()
    {
        $this->P2bService = new P2BService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $active = 'project';
        $company = $this->P2bService->getUser(Auth::id());
        $company_id = !empty($company['users_details']['i_ref_company_id']) ? $company['users_details']['i_ref_company_id'] : 1;
        $project_data = Project::select('id', 'vc_name', 'vc_description', 'i_ref_bu_id', 'i_status', 'open_close_status')->with(['business_unit' => function ($query) {
            $query->select('id', 'vc_short_name');
        }]);

        if (auth()->check() && auth()->user()->user_type != 'company') {
            $project_data = $project_data->where('i_ref_company_id', $company_id);
        }
        $project_data = $project_data->orderBy('id', 'desc')->get();

        return view('admin.project.index', compact('active', 'project_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $active = 'create_project';
        $business_unit = Business_unit::select('id', 'vc_short_name', 'i_status')->where('i_status', 1)->where('deleted_at', null)->get();
        $status = $this->statusArray;
        return view('admin.project.create', compact('active', 'business_unit', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // print_r($request->all());die;
        try {
            $validated = $request->validate([
                'vc_name' => 'required',
                'i_ref_bu_id' => 'required',
            ]);
            $input = $request->only(['vc_name', 'vc_description', 'vc_comment', 'i_ref_bu_id', 'i_status']);
            $company = $this->P2bService->getUser(Auth::id());

            $input['i_ref_company_id'] = isset($company['users_details']['i_ref_company_id']) ? $company['users_details']['i_ref_company_id'] : '';

            $business_unit = Project::create($input);
            return redirect()->route('projects.index')->with('success', 'Project saved successfully!');
        } catch (Exception $ex) {
            return redirect()->route('projects.index')->with('error', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = encrypt_decrypt('decrypt', $id);
        $active = 'project';
        $project_data = Project::with(['project_users.users' => function ($query) {
            $query->select('id', 'vc_fname', 'vc_mname', 'vc_lname');
        }, 'company' => function ($query) {
            $query->select('id', 'vc_company_name', 'vc_logo');
        }, 'business_unit' => function ($query) {
            $query->select('id', 'vc_short_name');
        }])->where('id', $id)->first();
        if (!empty($project_data)) {
            return view('admin.project.show', compact('active', 'project_data'));
        } else {
            return redirect()->route('projects.index')->with('error', 'Project doesnot exist!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = encrypt_decrypt('decrypt', $id);
        $project_data = Project::with(['business_unit' => function ($query) {
            $query->select('id', 'vc_short_name');
        }])->where('id', $id)->first();
        $active = 'edit_project';
        $business_unit = Business_unit::select('id', 'vc_short_name', 'i_status')->where('i_status', 1)->where('deleted_at', null)->get();
        $status = $this->statusArray;
        if (!empty($project_data)) {
            return view('admin.project.edit', compact('active', 'business_unit', 'status', 'project_data'));
        } else {
            return redirect()->route('projects.index')->with('error', 'Project doesnot exist!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = encrypt_decrypt('decrypt', $id);
        try {
            $validated = $request->validate([
                'vc_name' => 'required',
                'i_ref_bu_id' => 'required',
            ]);

            $edit = 0;
            if ($request->i_status == 0) {
                $project_data = Project::with(
                    [
                        'completed_form' => function ($query) {
                            $query->select('id', 'department_id');
                        },
                        'actions' => function ($query) {
                            $query->select('id', 'department_id');
                        },
                        'documents' => function ($query) {
                            $query->select('id', 'department_id');
                        }
                    ]
                )->withCount(['project_users'])->where('id', $id)->first();

                if ($project_data->project_users_count > 0 || count($project_data->completed_form) > 0 || count($project_data->actions) > 0 || count($project_data->documents) > 0) {
                    return redirect()->back()->with("error", "Unable to inactive Project ! It is mapped to other data.");
                } else {
                    $edit = 1;
                }
            } else {
                $edit = 1;
            }

            if ($edit == 1) {
                $input = $request->only(['vc_name', 'vc_description', 'vc_comment', 'i_ref_bu_id', 'i_status']);
                $business_unit = Project::findOrFail($id)->update($input);
                return redirect()->route('projects.index')->with('success', 'Project updated successfully!');
            }
        } catch (Exception $ex) {
            return redirect()->route('projects.index')->with('error', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = encrypt_decrypt('decrypt', $id);
        try {
            $project_data = Project::with(
                [
                    'completed_form' => function ($query) {
                        $query->select('id', 'department_id');
                    },
                    'actions' => function ($query) {
                        $query->select('id', 'department_id');
                    },
                    'documents' => function ($query) {
                        $query->select('id', 'department_id');
                    }
                ]
            )->withCount(['project_users'])->where('id', $id)->first();

            if ($project_data->project_users_count > 0 || count($project_data->completed_form) > 0 || count($project_data->actions) > 0 || count($project_data->documents) > 0) {
                session()->flash('error', "Unable to archive Project ! It is mapped to other data.");
            } else {
                $project_data->delete();
                session()->flash('success', "Project archived successfully !");
            }
            return;
        } catch (Exception $ex) {
            session()->flash('error', $ex->getMessage());
            return;
        }
    }

    /**
     * Display a listing of the archived resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function archived(Request $request)
    {
        $active = 'archive_project';
        $company = $this->P2bService->getUser(Auth::id());
        $company_id = !empty($company['users_details']['i_ref_company_id']) ? $company['users_details']['i_ref_company_id'] : 1;
        $project_data = Project::onlyTrashed()->select('id', 'vc_name', 'vc_description', 'i_ref_bu_id', 'i_status', 'open_close_status')->with(['business_unit' => function ($query) {
            $query->select('id', 'vc_short_name');
        }]);

        if (auth()->check() && auth()->user()->user_type != 'company') {
            $project_data = $project_data->where('i_ref_company_id', $company_id);
        }
        $project_data = $project_data->orderBy('id', 'desc')->get();

        return view('admin.project.archived', compact('active', 'project_data'));
    }

    /**
     * Restore archived Projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        $id = encrypt_decrypt('decrypt', $request->id);
        try {
            $data = Project::withTrashed()->find($id);
            $data->restore();
            $request->session()->flash('success', 'Project restored successfully!');
            return;
        } catch (Exception $ex) {
            session()->flash('error', $ex->getMessage());
            return;
        }
    }


    /**
     * close and open Projects.
     *
     * @return \Illuminate\Http\Response
     */

    public function open_close_project(Request $request)
    {

        $id = encrypt_decrypt('decrypt', $request->id);
        $status = $request->status == 0 ? 1 : 0;
        $message = $status == 1 ? 'Project is opened sucessfully ' : 'Project is closed sucessfully';
        try {
            $project_data = Project::withCount(['project_users'])->where('id', $id)->first();
            if ($project_data->project_users_count > 0 && $status == 0) {
                session()->flash('error', "Project could not be Deleted because this Project has assigned user(s).");
            } else {
                $project_data['open_close_status'] = $status;
                $project_data->update();
                $request->session()->flash('success', $message);
            }
            return;
        } catch (Exception $ex) {
            session()->flash('error', $ex->getMessage());
            return;
        }
    }
}
