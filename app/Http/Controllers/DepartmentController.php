<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Locations;
use App\Models\Department;
use App\Models\Business_unit;
use App\Models\BusinessDepartment;
use Auth;
use App\Services\P2B as P2BService;

class DepartmentController extends Controller
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
        $active = 'department';
        $company = $this->P2bService->getUser(Auth::id());
        $company_id = !empty($company['users_details']['i_ref_company_id'])?$company['users_details']['i_ref_company_id']: 1;
        $dept_data = Department::select('id', 'vc_name', 'i_status')->with(['dept_bu.bu_data' => function($query){
            $query->select('id', 'vc_short_name', 'created', 'modified');
        }]);

        if (auth()->check() && auth()->user()->user_type != 'company') {
            $dept_data = $dept_data->where('i_ref_company_id', $company_id);
        }
        $dept_data = $dept_data->orderBy('id', 'desc')->get();
        return view('admin.department.index', compact('active', 'dept_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $active = 'create_dept';
        $business_units = Business_unit::select('id', 'vc_short_name', 'i_status')->where('i_status', 1)->where('deleted_at', null)->get();
        $status = $this->statusArray;
        return view('admin.department.create', compact('active', 'business_units', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'vc_name' => 'required'
            ]);
            $input = $request->only(['vc_name', 'vc_description', 'vc_comment', 'i_status']);
            $company = $this->P2bService->getUser(Auth::id());

            $input['i_ref_company_id'] = isset($company['users_details']['i_ref_company_id'])?$company['users_details']['i_ref_company_id']:'';
            
            $department = Department::create($input);
            if($request->has('business_unit_id') && is_array($request->business_unit_id) && !empty($request->business_unit_id)){
                $business_unit_ids = [];
                foreach($request->business_unit_id as $bu){
                    $business_unit['business_unit_id'] = $bu;
                    array_push($business_unit_ids, $business_unit);
                }
                $department->dept_bu()->createMany($business_unit_ids);
            }
            return redirect()->route('departments.index')->with('success', 'Department saved successfully!');
        } catch (Exception $ex) {
            return redirect()->route('departments.index')->with('error', $ex->getMessage());
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
        $active = 'department';
        $dept_data = Department::with(['dept_bu.bu_data' => function($query){
            $query->select('id', 'vc_short_name', 'created', 'modified');
        }, 'company' => function($query){
            $query->select('id', 'vc_company_name', 'vc_logo');
        }])->where('id', $id)->first();
        if(!empty($dept_data)){
            return view('admin.department.show', compact('active', 'dept_data'));
        }else{
            return redirect()->route('departments.index')->with('error', 'Department doesnot exist!');
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
        $active = 'edit_dept';
        $business_units = Business_unit::select('id', 'vc_short_name', 'i_status')->where('i_status', 1)->where('deleted_at', null)->get();
        $status = $this->statusArray;
        $dept_data = Department::with('dept_bu')->where('id', $id)->first();
        $dept_data->dept_bu->each->makeHidden(['id', 'department_id']);
        $dept_data->dept_bu = array_column($dept_data->dept_bu->toArray(), 'business_unit_id');
        if(!empty($dept_data)){
            return view('admin.department.edit', compact('active', 'dept_data', 'business_units', 'status'));
        }else{
            return redirect()->route('departments.index')->with('error', 'Department doesnot exist!');
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
                'vc_name' => 'required'
            ]);
            $edit = 0;
            if($request->i_status == 0){
                $dept_data = Department::with(
                    [
                        'completed_form'=> function($query){
                            $query->select('id', 'department_id');
                        },
                        'actions'=> function($query){
                            $query->select('id', 'department_id');
                        },
                        'documents'=> function($query){
                            $query->select('id', 'department_id');
                        }
                    ])->withCount(['dept_bu', 'userDetail'])->where('id', $id)->first();
    
                if($dept_data->dept_bu_count > 0 || $dept_data->userDetail_count > 0 || count($dept_data->completed_form) > 0 || count($dept_data->actions) > 0 || count($dept_data->documents) > 0){
                    return redirect()->back()->with("error", "Unable to inactive Department ! It is mapped to other data.");
                }else{
                    $edit = 1;
                }
            }else{
                $edit = 1;
            }
            
            if($edit == 1){
                $input = $request->only(['vc_name', 'vc_description', 'vc_comment', 'i_status']);
                
                $department = Department::with('dept_bu')->findOrFail($id);
                $department->update($input);

                $old_bu = !empty($request->old_bu) ? unserialize(base64_decode($request->old_bu)) : '';
                if(!empty($request->business_unit_id)){
                    $bu_ids = [];
                    if(!empty($old_bu) && array_diff($old_bu, $request->business_unit_id)){
                        //delete BU that are not belongs to department
                        $delete_bu = array_diff($old_bu, $request->business_unit_id);
                        $bu_dept = BusinessDepartment::where('department_id', $id)->whereIn('business_unit_id', $delete_bu)->delete();
                    }
                    foreach($request->business_unit_id as $bu){
                        if(!empty($old_bu) && in_array($bu, $old_bu)){
                        }else{
                            $business_unit['business_unit_id'] = $bu;
                            array_push($bu_ids, $business_unit);
                        }
                    }
                    if(!empty($bu_ids)){
                        $department->dept_bu()->createMany($bu_ids);
                    }
                }else{
                    // delete all BU
                    $bu_dept = BusinessDepartment::where('department_id', $id)->delete();
                }

                return redirect()->route('departments.index')->with('success', 'Department updated successfully!');
            }
        } catch (Exception $ex) {
            return redirect()->route('departments.index')->with('error', $ex->getMessage());
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
            $dept_data = Department::with(
                [
                    'completed_form'=> function($query){
                        $query->select('id', 'department_id');
                    },
                    'actions'=> function($query){
                        $query->select('id', 'department_id');
                    },
                    'documents'=> function($query){
                        $query->select('id', 'department_id');
                    }
                ])->withCount(['dept_bu', 'userDetail'])->where('id', $id)->first();

            if($dept_data->dept_bu_count > 0 || $dept_data->userDetail_count > 0 || count($dept_data->completed_form) > 0 || count($dept_data->actions) > 0 || count($dept_data->documents) > 0){
               session()->flash('error', "Unable to archive Department ! It is mapped to other data.");
            }else{
                $dept_data->delete();
                session()->flash('success', "Department archived successfully !");
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
        $active = 'archive_dept';
        $company = $this->P2bService->getUser(Auth::id());
        $company_id = !empty($company['users_details']['i_ref_company_id'])?$company['users_details']['i_ref_company_id']: 1;
        $dept_data = Department::onlyTrashed()->select('id', 'vc_name', 'i_status')->with(['dept_bu.bu_data' => function($query){
            $query->select('id', 'vc_short_name', 'created', 'modified');
        }]);

        if (auth()->check() && auth()->user()->user_type != 'company') {
            $dept_data = $dept_data->where('i_ref_company_id', $company_id);
        }

        $dept_data = $dept_data->orderBy('id', 'desc')->get();

        return view('admin.department.archived', compact('active', 'dept_data'));
    }

    /**
     * Restore archived departments.
     *
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {  
        $id = encrypt_decrypt('decrypt', $request->id);
        try {
            $data = Department::withTrashed()->find($id);
            $data->restore();
            $request->session()->flash('success', 'Departments restored successfully!');
            return;
        }catch (Exception $ex) {
            session()->flash('error', $ex->getMessage());
            return;
        }
    }
}
