<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Role;
use App\Models\Permission;
use App\Models\GroupsRole;
use App\Models\GroupPermission;
use App\Http\Requests\GroupRequest;
use App\Services\P2B as P2BService;
use Auth;

class GroupController extends Controller
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
        $active = 'groups';
        $company = $this->P2bService->getUser(Auth::id());
        $company_id = !empty($company['users_details']['i_ref_company_id'])?$company['users_details']['i_ref_company_id']: 1;
        $group_data = Group::with(['group_role.roles' => function($query){
            $query->select('id', 'vc_name');
        }]);

        if (auth()->check() && auth()->user()->user_type != 'company') {
            $group_data = $group_data->where('i_ref_company_id', $company_id);
        }
        $group_data = $group_data->get();

        // pr($group_data);die;
        return view('admin.group.index', compact('active', 'group_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $active = 'groups';
        $status = $this->statusArray;
        $roles = Role::select('id', 'vc_name', 'i_status')->where('i_status', 1)->where('deleted_at', null)->get();
        $permissions = Permission::select('id', 'vc_name')->get();
        return view('admin.group.create', compact('active', 'status', 'roles', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupRequest $request)
    {
        try {
            if ($request->validated()) {

                $company = $this->P2bService->getUser(Auth::id());
                $company_id = isset($company['users_details']['i_ref_company_id'])?$company['users_details']['i_ref_company_id']:'';

                $input = $request->only(['vc_name', 'vc_description', 'i_status']);
                $input['i_ref_company_id'] = $company_id;
                $groupRow = Group::create($input);
                $groupid = $groupRow->id;

                if($request->has('role_id') && is_array($request->role_id) && !empty($request->role_id)){
                    $role_ids = [];
                    foreach($request->role_id as $role){
                        $grp_role['role_id'] = $role;
                        array_push($role_ids, $grp_role);
                    }
                    $groupRow->group_role()->createMany($role_ids);
                }

                if($request->has('permission_id') && is_array($request->permission_id) && !empty($request->permission_id)){
                    $permission_ids = [];
                    foreach($request->permission_id as $permission){
                        $permissions['permission_id'] = $permission;
                        array_push($permission_ids, $permissions);
                    }
                    $groupRow->group_permission()->createMany($permission_ids);
                }

                return redirect()->route('groups.index')->with('success', 'Group saved successfully!');
            }

        }catch (Exception $ex) {
            return redirect()->route('groups.index')->with('error', $ex->getMessage());
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
        $active = 'groups';
        $group_data = Group::with(['group_role.roles' => function($query){
            $query->select('id', 'vc_name', 'vc_description');
        },
        'group_permission.permissions' => function($query){
            $query->select('id', 'vc_name', 'vc_description');
        },
        'company' => function($query){
            $query->select('id', 'vc_company_name', 'vc_logo');
        }])->where('id', $id)->first();

        if(!empty($group_data)){
            return view('admin.group.show', compact('active', 'group_data'));
        }else{
            return redirect()->route('group.index')->with('error', 'Department doesnot exist!');
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
        $active = 'groups';
        $status = $this->statusArray;
        $roles = Role::select('id', 'vc_name', 'i_status')->where('i_status', 1)->where('deleted_at', null)->get();
        $permissions = Permission::select('id', 'vc_name')->get();

        $group_data = Group::with(['group_role', 'group_permission'])->where('id', $id)->first();

        $group_data->group_role->each->makeHidden(['id', 'group_id']);
        $group_data->group_role = array_column($group_data->group_role->toArray(), 'role_id');

        $group_data->group_permission->each->makeHidden(['id', 'group_id']);
        $group_data->group_permission = array_column($group_data->group_permission->toArray(), 'permission_id');

        if(!empty($group_data)){
            return view('admin.group.edit', compact('active', 'status', 'roles', 'permissions', 'group_data'));
        }else{
            return redirect()->route('group.index')->with('error', 'Department doesnot exist!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GroupRequest $request, $id)
    {
        $id = encrypt_decrypt('decrypt', $id);
        try {
            if ($request->validated()) {

                $input = $request->only(['vc_name', 'vc_description', 'i_status']);
                $groupRow = Group::findOrFail($id);
                $groupRow->update($input);

                $old_roles = !empty($request->old_roles) ? unserialize(base64_decode($request->old_roles)) : '';
                if(!empty($request->role_id)){
                    $role_ids = [];
                    // print_r(array_diff($old_roles, $request->role_id));die;
                    if(!empty($old_roles) && array_diff($old_roles, $request->role_id)){
                        //delete roles that are not belongs to group
                        $delete_role = array_diff($old_roles, $request->role_id);
                        $grp_role = GroupsRole::where('group_id', $id)->whereIn('role_id', $delete_role)->delete();
                    }
                    foreach($request->role_id as $role){
                        if(!empty($old_roles) && in_array($role, $old_roles)){
                        }else{
                            $roles['role_id'] = $role;
                            array_push($role_ids, $roles);
                        }
                    }
                    $groupRow->group_role()->createMany($role_ids);
                }else{
                    // delete all roles
                    $grp_role = GroupsRole::where('group_id', $id)->delete();
                }

                $old_permission = !empty($request->old_permission) ? unserialize(base64_decode($request->old_permission)) : '';
                if(!empty($request->permission_id)){
                    $permission_ids = [];
                    if(!empty($old_permission) && array_diff($old_permission, $request->permission_id)){
                        //delete permissions that are not belongs to group
                        $delete_permissions = array_diff($old_permission, $request->permission_id);
                        $grp_role = GroupPermission::where('group_id', $id)->whereIn('permission_id', $delete_permissions)->delete();
                    }
                    foreach($request->permission_id as $permission){
                        if(!empty($old_permission) && in_array($permission, $old_permission)){
                            // die('heter');
                        }else{
                            // die('here');
                            $permissions['permission_id'] = $permission;
                            // print_r($permissions);die;
                            array_push($permission_ids, $permissions);
                        }
                        // print_r($permission_ids);die;
                    }
                    $groupRow->group_permission()->createMany($permission_ids);
                }else{
                    // delete all permissions
                    $grp_role = GroupPermission::where('group_id', $id)->delete();
                }


                return redirect()->route('groups.index')->with('success', 'Group updated successfully!');
            }
        } catch (Exception $ex) {
            return redirect()->route('groups.index')->with('error', $ex->getMessage());
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
            $group_data = Group::findOrFail($id);
            $group_data->delete();
            session()->flash('success', "Group archived successfully !");
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
        $active = 'archive_groups';
        $company = $this->P2bService->getUser(Auth::id());
        $company_id = !empty($company['users_details']['i_ref_company_id'])?$company['users_details']['i_ref_company_id']: 1;
        $group_data = Group::onlyTrashed()->with(['group_role.roles' => function($query){
            $query->select('id', 'vc_name');
        }]);
        
        if (auth()->check() && auth()->user()->user_type != 'company') {
            $group_data = $group_data->where('i_ref_company_id', $company_id);
        }
        $group_data = $group_data->get();

        return view('admin.group.archived', compact('active', 'group_data'));
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
            $data = Group::withTrashed()->find($id);
            $data->restore();
            $request->session()->flash('success', 'Group restored successfully!');
            return;
        }catch (Exception $ex) {
            session()->flash('error', $ex->getMessage());
            return;
        }
    }
}
