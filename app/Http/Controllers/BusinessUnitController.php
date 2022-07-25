<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Users;
use App\Models\Action;
use App\Models\Document;
use App\Models\Locations;
use App\Models\Department;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use App\Models\Business_unit;
use App\Models\CompletedForm;
use App\Models\BusinessDepartment;
use App\Services\P2B as P2BService;
use Yajra\DataTables\Services\DataTable;

class BusinessUnitController extends Controller
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
    public function index(Request $request)
    {
        
        $active = 'business_unit';
        $company = $this->P2bService->getUser(Auth::id());
        $company_id = !empty($company['users_details']['i_ref_company_id'])?$company['users_details']['i_ref_company_id']: 1;
        $bu_data = Business_unit::select('id', 'vc_short_name', 'i_status','i_ref_location_id')->with(['business_dept.dept_data' => function($query){
            $query->select('id', 'vc_name', 'created', 'modified');
        }, 'locations' => function($query){
            $query->select('id', 'vc_name', 'created', 'modified');
        }]);

        if (auth()->check() && auth()->user()->user_type != 'company') {
            $bu_data = $bu_data->where('i_ref_company_id', $company_id);
        }
        $bu_data = $bu_data->orderBy('id', 'desc')->get();

        return view('admin.business_unit.index', compact('active', 'bu_data'));
    }

    public function checkBusinessDepartment(Request $request){

        // $bu_id = encrypt_decrypt('decrypt', $request->bu_id);
        // dd($request->bu_id);
        $bu_id=$request->bu_id;

        if ($request->has('id') && !empty($request->id)) {
            $action=Action::where('business_unit_id',$bu_id)->where('department_id',$request->id)->exists();
            $Document=Document::where('business_unit_id',$bu_id)->where('department_id',$request->id)->exists();
            $CompletedForm=CompletedForm::where('business_unit_id',$bu_id)->where('department_id',$request->id)->exists();
            $user=UserDetail::with(['user'])->where('i_ref_bu_id',$bu_id)->where('i_ref_dep_id',$request->id)->where('i_status',1)
            ->where('i_ref_company_id', auth()->user()->users_details->i_ref_company_id)->orderBy('id', 'desc')->exists();
            // dd($user);
            if ($action ||$Document || $CompletedForm || $user) {
                echo "false";
            } else {
                echo "true";
            }
        } else {
            echo "true";
        }
        die;

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $active = 'business_unit';
        $locations = Locations::select('id', 'vc_name', 'i_status')->where('i_status', 1)->where('deleted_at', null)->get(); 
        $departments = Department::select('id', 'vc_name', 'i_status')->where('i_status', 1)->where('deleted_at', null)->get();
        $status = $this->statusArray;
        return view('admin.business_unit.create', compact('active', 'locations', 'departments', 'status'));
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
                'vc_short_name' => 'required',
                'i_ref_location_id' => 'required',
            ]);
            $input = $request->only(['vc_short_name', 'vc_legal_name', 'vc_description', 'vc_comments','i_ref_location_id', 'i_status']);
            $company = $this->P2bService->getUser(Auth::id());

            $input['i_ref_company_id'] = isset($company['users_details']['i_ref_company_id'])?$company['users_details']['i_ref_company_id']:'';
            
            $business_unit = Business_unit::create($input);
            if($request->has('department_id') && is_array($request->department_id) && !empty($request->department_id)){
                $department_ids = [];
                foreach($request->department_id as $dept){
                    $department['department_id'] = $dept;
                    array_push($department_ids, $department);
                }
                $business_unit->business_dept()->createMany($department_ids);
            }
            return redirect()->route('business-units.index')->with('success', 'Business unit saved successfully!');
        } catch (Exception $ex) {
            return redirect()->route('business-units.index')->with('error', $ex->getMessage());
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
        $active = 'business_unit';
        $bu_data = Business_unit::with(['business_dept.dept_data' => function($query){
            $query->select('id', 'vc_name', 'created', 'modified');
        }, 'locations' => function($query){
            $query->select('id', 'vc_name', 'created', 'modified');
        }, 'company' => function($query){
            $query->select('id', 'vc_company_name', 'vc_logo');
        }, 'projects' => function($query){
            $query->select('id', 'vc_name', 'i_ref_bu_id', 'vc_description', 'created', 'modified');
        }
        ])->where('id', $id)->first();
        if(!empty($bu_data)){
            return view('admin.business_unit.show', compact('active', 'bu_data'));
        }else{
            return redirect()->route('business-units.index')->with('error', 'Business Unit doesnot exist!');
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
        $active = 'business_unit';
        $locations = Locations::select('id', 'vc_name', 'i_status')->where('i_status', 1)->where('deleted_at', null)->get(); 
        $departments = Department::select('id', 'vc_name', 'i_status')->where('i_status', 1)->where('deleted_at', null)->get();
        $status = $this->statusArray;
        $bu_data = Business_unit::select(['id', 'vc_short_name', 'vc_legal_name', 'vc_description', 'vc_comments', 'i_ref_location_id', 'i_status'])->with('business_dept')->findOrFail($id);
        $bu_data->business_dept->each->makeHidden(['id', 'business_unit_id']);
        $bu_data->business_dept = array_column($bu_data->business_dept->toArray(), 'department_id');
        if(!empty($bu_data)){
            return view('admin.business_unit.edit', compact('active', 'locations', 'departments', 'status', 'bu_data'));
        }else{
            return redirect()->route('business-units.index')->with('error', 'Business Unit doesnot exist!');
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
            // dd($request->all());die;
        //     $old_department = !empty($request->old_department) ? unserialize(base64_decode($request->old_department)) : '';
        //    dd($old_department);
            // print_r($business_unit->department_id);die;
            $validated = $request->validate([
                'vc_short_name' => 'required',
                'i_ref_location_id' => 'required',
            ]);
            $edit = 0;
            if($request->i_status == 0){
                $bu_data = Business_unit::with(
                    [
                    'completed_form'=> function($query){
                        $query->select('id', 'business_unit_id');
                    },
                    'actions'=> function($query){
                        $query->select('id', 'business_unit_id');
                    },
                    'documents'=> function($query){
                        $query->select('id', 'business_unit_id');
                    }
                ])->withCount(['business_dept', 'projects', 'roles', 'userDetail'])->where('id', $id)->first();
                if($bu_data->business_dept_count > 0 || $bu_data->projects_count > 0 || $bu_data->roles_count > 0 || $bu_data->userDetail_count > 0 
                    || count($bu_data->completed_form) > 0 || count($bu_data->actions) > 0 || count($bu_data->documents) > 0 ){
                            return redirect()->back()->with("error", "Unable to inactive Business Unit ! It is mapped to other data.");
                }else{
                    $edit = 1;
                }
            }else{
                $edit = 1;
            }
            if($edit == 1){
                $input = $request->only(['vc_short_name', 'vc_legal_name', 'vc_description', 'vc_comments', 'i_ref_location_id', 'i_status']);
                $business_unit = Business_unit::with('business_dept')->findOrFail($id);
                $business_unit->update($input);

                $old_department = !empty($request->old_department) ? unserialize(base64_decode($request->old_department)) : '';
                if(!empty($request->department_id)){
                    $department_ids = [];
                    if(!empty($old_department) && array_diff($old_department, $request->department_id)){
                        //delete department that are not belongs to Business unit
                        $delete_dept = array_diff($old_department, $request->department_id);
                        $bu_dept = BusinessDepartment::where('business_unit_id', $id)->whereIn('department_id', $delete_dept)->delete();
                    }
                    foreach($request->department_id as $dept){
                        if(!empty($old_department) && in_array($dept, $old_department)){
                        }else{
                            $department['department_id'] = $dept;
                            array_push($department_ids, $department);
                        }
                    }
                    if(!empty($department_ids)){
                        $business_unit->business_dept()->createMany($department_ids);
                    }
                    
                }else{
                    // delete all departments
                    $bu_dept = BusinessDepartment::where('business_unit_id', $id)->delete();
                }
                return redirect()->route('business-units.index')->with('success', 'Business unit updated successfully!');
            }
        } catch (Exception $ex) {
            return redirect()->route('business-units.index')->with('error', $ex->getMessage());
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
            $bu_data = Business_unit::with(
            [
                'completed_form'=> function($query){
                    $query->select('id', 'business_unit_id');
                },
                'actions'=> function($query){
                    $query->select('id', 'business_unit_id');
                },
                'documents'=> function($query){
                    $query->select('id', 'business_unit_id');
                }
            ])->withCount(['business_dept', 'projects', 'roles', 'userDetail'])->where('id', $id)->first();
            if($bu_data->business_dept_count > 0 || $bu_data->projects_count > 0 || $bu_data->roles_count > 0 || $bu_data->userDetail_count > 0 
             || count($bu_data->completed_form) > 0 || count($bu_data->actions) > 0 || count($bu_data->documents) > 0 ){
               session()->flash('error', "Unable to archive Business Unit ! It is mapped to other data.");
            }else{
                $bu_data->delete();
                session()->flash('success', "Business Unit archived successfully !");
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
        $active = 'archive_bu';
        $company = $this->P2bService->getUser(Auth::id());
        $company_id = !empty($company['users_details']['i_ref_company_id'])?$company['users_details']['i_ref_company_id']: 1;
        $bu_data = Business_unit::onlyTrashed()->select('id', 'vc_short_name', 'i_status','i_ref_location_id')->with(['business_dept.dept_data' => function($query){
            $query->select('id', 'vc_name', 'created', 'modified');
        }, 'locations' => function($query){
            $query->select('id', 'vc_name', 'created', 'modified');
        }]);

        if (auth()->check() && auth()->user()->user_type != 'company') {
            $bu_data =  $bu_data->where('i_ref_company_id', $company_id);
        }

        $bu_data = $bu_data->orderBy('id', 'desc')->get();

        return view('admin.business_unit.archive', compact('active', 'bu_data'));
    }

    /**
     * Restore archived Business Units.
     *
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {  
        $id = encrypt_decrypt('decrypt', $request->id);
        try {
            $data = Business_unit::withTrashed()->find($id);
            $data->restore();
            $request->session()->flash('success', 'Business unit restored successfully!');
            return;
        }catch (Exception $ex) {
            session()->flash('error', $ex->getMessage());
            return;
        }
        
    }
    
}
