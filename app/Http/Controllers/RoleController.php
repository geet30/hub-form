<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Business_unit;
use App\Models\Level;
use App\Models\Permission;
use App\Models\FormPermission;
use App\Models\RolePermission;
use App\Models\UserDetail;
use App\Models\RolesFormPermissions;
use App\Http\Requests\RoleRequest;
use Auth;
use App\Services\P2B as P2BService;

class RoleController extends Controller
{
    public $ids = [];
    public $parent_data = [];

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
        $active = 'roles';
        return view('admin.role.index', compact('active'));
    }

    /**
     * Get a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getrole(Request $request)
    {
        // print_r($request->all());die;
        // $this->autoRender = false;
        $roledata = [];
        $company = $this->P2bService->getUser(Auth::id());
        $company_id = !empty($company['users_details']['i_ref_company_id']) ? $company['users_details']['i_ref_company_id'] : 1;

        if ($request->tree_page == 'archived') {
            $roles = Role::onlyTrashed()->with([
                'business_unit' => function ($query) {
                    $query->select('id', 'vc_short_name');
                },
                'user_detail' => function ($query) {
                    $query->select('id', 'i_ref_user_id', 'i_ref_role_id', 'i_ref_company_id');
                },
                'user_detail.user' => function ($query) {
                    $query->select('id', 'vc_fname', 'vc_lname', 'vc_image');
                },
                'level' => function ($query) {
                    $query->select('id', 'vc_name');
                }
            ]);

            // if (auth()->check() && auth()->user()->user_type != 'company') {
            // $roles = $roles->where('i_ref_company_id', $company_id);
            // }
            $roles = $roles->get();
        } else {

            $roles = Role::with([
                'business_unit' => function ($query) {
                    $query->select('id', 'vc_short_name');
                },
                'user_detail' => function ($query) {
                    $query->select('id', 'i_ref_user_id', 'i_ref_role_id', 'i_ref_company_id');
                },
                'user_detail.user' => function ($query) {
                    $query->select('id', 'vc_fname', 'vc_lname', 'vc_image');
                },
                'level' => function ($query) {
                    $query->select('id', 'vc_name');
                }
            ]);

            // if (auth()->check() && auth()->user()->user_type != 'company') {
            // $roles = $roles->where('i_ref_company_id', $company_id);
            // }
            $roles = $roles->get();
        }
        // dd($roles);
        foreach ($roles as $role) {

            $bu = $role['business_unit']['vc_short_name'];

            $user = (isset($role['user_detail']['user'])) ? ' (' . (!empty($role['user_detail']['user']['vc_fname']) ? $role['user_detail']['user']['vc_fname'] : '') . '&nbsp;' . (!empty($role['user_detail']['user']['vc_lname']) ? $role['user_detail']['user']['vc_lname'] : '') . ')' : null;

            $parent_id = ($role['i_ref_role_id'] == $role['id']) ? 0 : $role['i_ref_role_id'];
            $is_parent_itself = ($role['i_ref_role_id'] == $role['id'] || $role['i_ref_role_id'] == 0 || $role['i_ref_role_id'] == '') ? 'yes' : 'no';
            if (isset($role['user_detail']['user'])) {
                $user_image = $role['user_detail']['user']['image_url'];
            } else {
                $user_image = '/assets/edit_form/images/defaultpic.jpeg';
            }
            // $roledata =[];
            $roledata[] = array(
                'is_parent_itself' => $is_parent_itself,
                'id' => $role['id'],
                'id_encrypted' => $role['id_encrypted'],
                'vc_name' => ucwords($role['vc_name']) . $user,
                'business_unit' => ucwords($bu),
                'icon' => $user_image,
                'status' => $role['i_status'],
                'parent_id' => $parent_id,
                'i_ref_role_id' => $role['i_ref_role_id'],
                'level_name' => $role['level']['vc_name'],
                'account_payable' => $role['account_payable'],
                'i_ref_company_id' => $company_id,
                'supplier_approver' => $role['supplier_approver'],
                'system_administrator' => $role['system_administrator'],
                "alternative_supplier_approver" => $role['alternative_supplier_approver']
            );
        }

        // print_r($roledata);die('=====-');
        return json_encode($roledata);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $active = 'roles';
        $status = $this->statusArray;
        $bu = Business_unit::select('id', 'vc_short_name', 'i_status')->where('i_status', 1)->get();
        $levels = Level::where('i_status', 1)->get();
        $permissions = Permission::where('is_visible_to_supplier', 0)->where('hub_permissions', 1)->get();
        $form_permissions = FormPermission::get();
        $company = $this->P2bService->getUser(Auth::id());
        $company_id = !empty($company['users_details']['i_ref_company_id']) ? $company['users_details']['i_ref_company_id'] : 1;

        $account_payable = Role::select('*')->where('i_ref_company_id', $company_id)->where('account_payable', 1)->first();
        $supplier_approver = Role::select('*')->where('i_ref_company_id', $company_id)->where('supplier_approver', 1)->first();
        $system_administrator = Role::select('*')->where('i_ref_company_id', $company_id)->where('system_administrator', 1)->first();

        return view('admin.role.create', compact('active', 'status', 'bu', 'levels', 'permissions', 'form_permissions', 'company_id', 'account_payable', 'supplier_approver', 'system_administrator'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        try {
            if ($request->validated()) {
                $input = $request->only(['vc_name', 'vc_description', 'i_ref_bu_id', 'i_ref_level_id', 'i_status']);
                $company = $this->P2bService->getUser(Auth::id());

                $input['i_ref_company_id'] = isset($company['users_details']['i_ref_company_id']) ? $company['users_details']['i_ref_company_id'] : '';
                $input['account_payable'] = isset($request->account_payable) && $request->account_payable == 'on' ? 1 : 0;
                $input['i_ref_role_id'] = isset($request->i_ref_role_id) && !empty($request->i_ref_role_id) ? $request->i_ref_role_id : 0;
                $input['supplier_approver'] = isset($request->supplier_approver) && $request->supplier_approver == 'on' ? 1 : 0;
                $input['system_administrator'] = isset($request->system_administrator) && $request->system_administrator == 'on' ? 1 : 0;
                $input['alternative_supplier_approver'] = isset($request->alternative_supplier_approver) && $request->alternative_supplier_approver == 'on' ? 1 : 0;
              
                $rolesRow = Role::create($input);

                if ($request->has('permission_id') && is_array($request->permission_id) && !empty($request->permission_id)) {
                    $permission_ids = [];
                    foreach ($request->permission_id as $permission) {
                        $permissions['permission_id'] = $permission;
                        array_push($permission_ids, $permissions);
                    }
                    $rolesRow->role_permission()->createMany($permission_ids);
                }

                if ($request->has('form_permission_id') && is_array($request->form_permission_id) && !empty($request->form_permission_id)) {
                    $form_permission_ids = [];
                    foreach ($request->form_permission_id as $form_permission) {
                        $form_perm['form_permission_id'] = $form_permission;
                        array_push($form_permission_ids, $form_perm);
                    }
                    $rolesRow->role_form_permission()->createMany($form_permission_ids);
                }

                return redirect()->route('roles.index')->with('success', 'Role saved successfully!');
                // print_r($request->all());die;

            }
        } catch (Exception $ex) {
            return redirect()->route('roles.index')->with('error', $ex->getMessage());
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
        // $id = encrypt_decrypt('decrypt', $id);
        $active = 'roles';
        $roleData = Role::with([
            'role_permission.permission', 'role_form_permission.form_permission',
            'user_detail' => function ($query) {
                $query->select('id', 'i_ref_user_id', 'i_ref_role_id');
            },
            'user_detail.user' => function ($query) {
                $query->select('id', 'vc_fname', 'vc_mname', 'vc_lname', 'vc_image');
            },
            'child_roles' => function ($query1) {
                $query1->select('id', 'vc_name', 'i_ref_role_id');
            },
            'child_roles.parent_role' => function ($query1) {
                $query1->select('id', 'vc_name', 'i_ref_role_id');
            },
            'company' => function ($query1) {
                $query1->select('id', 'vc_company_name');
            },
            'level' => function ($query1) {
                $query1->select('id', 'vc_name', 'i_start_limit', 'i_end_limit');
            },

        ])->findOrFail($id);

        // pr($roleData);die;

        return view('admin.role.show', compact('active', 'roleData'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $id = encrypt_decrypt('decrypt', $id);
        $active = 'roles';
        $status = $this->statusArray;
        $bu = Business_unit::select('id', 'vc_short_name', 'i_status')->where('i_status', 1)->get();
        $levels = Level::where('i_status', 1)->get();
        $permissions = Permission::where('is_visible_to_supplier', 0)->where('hub_permissions', 1)->get();
        $form_permissions = FormPermission::get();

        $roleData = Role::with([
            'role_permission', 'role_form_permission',
            'business_unit.roles' => function ($query) {
                $query->select('id', 'vc_name', 'i_ref_bu_id');
            }

        ])->findOrFail($id);

        $roleData->role_permission->each->makeHidden(['id', 'role_id']);
        $roleData->role_permission = array_column($roleData->role_permission->toArray(), 'permission_id');

        $roleData->role_form_permission->each->makeHidden(['id', 'role_id']);
        $roleData->role_form_permission = array_column($roleData->role_form_permission->toArray(), 'form_permission_id');

        $company = $this->P2bService->getUser(Auth::id());
        $company_id = !empty($company['users_details']['i_ref_company_id']) ? $company['users_details']['i_ref_company_id'] : 1;

        return view('admin.role.edit', compact('active', 'status', 'bu', 'levels', 'permissions', 'form_permissions', 'roleData','company_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        // $id = encrypt_decrypt('decrypt', $id);
        try {
            if ($request->validated()) {

                // dd($request->all());
                $edit = 0;
                if ($request->i_status == 0) {
                    $roles = Role::select('id', 'vc_name', 'i_ref_role_id')->get()->makeHidden(['id_encrypted'])->toArray();
                    $newArray = $this->buildTree($roles, $id);
                    array_push($this->ids, (int) $id);
                    $this->getSubrole($newArray);
                    $ids = array_unique($this->ids);
                    $user = UserDetail::whereIn('i_ref_role_id', $ids)->exists();

                    if ($user) {
                        return redirect()->back()->with("error", "Unable to inactive Role ! It is mapped to other data.");
                    } else {
                        $edit = 1;
                    }
                } else {
                    $edit = 1;
                }

                if ($edit == 1) {
                    $input = $request->only(['vc_name', 'vc_description', 'i_ref_bu_id', 'i_ref_level_id', 'i_status']);

                    $input['i_ref_role_id'] = isset($request->i_ref_role_id) && !empty($request->i_ref_role_id) ? $request->i_ref_role_id : 0;
                    $input['account_payable'] = isset($request->account_payable) && $request->account_payable == 'on' ? 1 : 0;
                    $input['supplier_approver'] = isset($request->supplier_approver) && $request->supplier_approver == 'on' ? 1 : 0;
                    $input['system_administrator'] = isset($request->system_administrator) && $request->system_administrator == 'on' ? 1 : 0;
                    $input['alternative_supplier_approver'] = isset($request->alternative_supplier_approver) && $request->alternative_supplier_approver == 'on' ? 1 : 0;
              
                    $rolesRow = Role::findOrFail($id);
                    $rolesRow->update($input);



                    // $rolesRow->role_permission()->detach();
                    $old_permission = !empty($request->old_permission) ? unserialize(base64_decode($request->old_permission)) : '';
                    if (!empty($request->permission_id)) {
                        // $rolesRow->role_permission()->attach($request->permission_id);
                        $permission_ids = [];
                        if (!empty($old_permission) && array_diff($old_permission, $request->permission_id)) {

                            //delete permission that are not belongs to role
                            $delete_permission = array_diff($old_permission, $request->permission_id);
                            $permissionRow = RolePermission::where('role_id', $id)->whereIn('permission_id', $delete_permission)->delete();
                        }
                        foreach ($request->permission_id as $permission) {

                            if (!empty($old_permission) && in_array($permission, $old_permission)) {
                            } else {
                                $permissions['permission_id'] = $permission;
                                array_push($permission_ids, $permissions);
                            }
                        }
                        if (!empty($permission_ids)) {
                            $rolesRow->role_permission()->createMany($permission_ids);
                        }
                    } else {
                        // delete all permissions
                        $permissionRow = RolePermission::where('role_id', $id)->delete();
                    }

                    $old_form_permission = !empty($request->old_form_permission) ? unserialize(base64_decode($request->old_form_permission)) : '';
                    if (!empty($request->form_permission_id)) {
                        $form_permission_ids = [];
                        if (!empty($old_form_permission) && array_diff($old_form_permission, $request->form_permission_id)) {

                            //delete form permission that are not belongs to role
                            $delete_form_permission = array_diff($old_form_permission, $request->form_permission_id);
                            $permissionRow = RolesFormPermissions::where('role_id', $id)->whereIn('form_permission_id', $delete_form_permission)->delete();
                        }
                        foreach ($request->form_permission_id as $form_permission) {
                            if (!empty($old_form_permission) && in_array($form_permission, $old_form_permission)) {
                            } else {
                                $form_permissions['form_permission_id'] = $form_permission;
                                array_push($form_permission_ids, $form_permissions);
                            }
                        }
                        if (!empty($form_permission_ids)) {
                            $rolesRow->role_form_permission()->createMany($form_permission_ids);
                        }
                    } else {
                        // delete all form permissions
                        $permissionRow = RolesFormPermissions::where('role_id', $id)->delete();
                    }
                    return redirect()->route('roles.index')->with('success', 'Role updated successfully!');
                }
            }
        } catch (Exception $ex) {
            return redirect()->route('roles.index')->with('error', $ex->getMessage());
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
        // die('here');
        try {
            $roles = Role::select('id', 'vc_name', 'i_ref_role_id')->get()->makeHidden(['id_encrypted'])->toArray();
            $newArray = $this->buildTree($roles, $id);
            array_push($this->ids, (int) $id);
            $this->getSubrole($newArray);
            $ids = array_unique($this->ids);
            $user = UserDetail::whereIn('i_ref_role_id', $ids)->exists();

            if ($user) {
                session()->flash('error', "Unable to archive this role! This Role has mapped to User .");
            } else {
                Role::whereIn('id', $ids)->delete();
                session()->flash('success', "Role archived successfully !");
            }
            return;
        } catch (\Exception $ex) {
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
        $active = 'archive_role';
        return view('admin.role.archived', compact('active'));
    }

    /**
     * Restore archived Business Units.
     *
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        // $id = encrypt_decrypt('decrypt', $request->id);
        $id =  $request->id;
        try {
            $roles = Role::withTrashed()->select('id', 'vc_name', 'i_ref_role_id')->get()->makeHidden(['id_encrypted'])->toArray();
            $newArray = $this->buildTree($roles, $id);
            array_push($this->ids, (int) $id);
            $this->getSubrole($newArray);
            $ids = array_unique($this->ids);
            Role::withTrashed()->whereIn('id', $ids)->restore();
            $request->session()->flash('success', 'Role restored successfully!');
            return;
        } catch (Exception $ex) {
            session()->flash('error', $ex->getMessage());
            return;
        }
    }

    /**
     * drag the role.
     *
     * @return \Illuminate\Http\Response
     */

    public function getUpgradeDowngradeRole(Request $request)
    {
        try {
            print_r($request->all());
            die;
            if (!empty($this->request->data['selected']) && !empty($this->request->data['shifted']) && !empty($this->request->data['movedTo'])) {
                $getRole = $this->Role->find('all', array(
                    'fields' => ['id', 'i_ref_bu_id', 'i_ref_role_id', 'i_ref_level_id', 'vc_name', 'BusinessUnit.vc_short_name'],
                    'conditions' => array('Role.id' => [$this->request->data['selected'], $this->request->data['shifted'], $this->request->data['movedTo']]),
                    'contain' => ['BusinessUnit']
                ));

                $roleFilter = array_map(function ($arr) {
                    if ($arr['Role']['id'] == $this->request->data['selected']) {
                        array_push($arr['Role'], 'selected');
                    }
                    if ($arr['Role']['id'] == $this->request->data['shifted']) {
                        array_push($arr['Role'], 'shifted');
                    }
                    if ($arr['Role']['id'] == $this->request->data['movedTo']) {
                        array_push($arr['Role'], 'movedTo');
                    }
                    return $arr;
                }, $getRole);

                $roleFilter = Hash::extract($roleFilter, "{n}.Role");
                $selectedArray = array_search("selected", Hash::extract($roleFilter, "{n}.{n}"));
                $shiftedDownArray = array_search("movedTo", Hash::extract($roleFilter, "{n}.{n}"));
                $shiftedArray = array_search("shifted", Hash::extract($roleFilter, "{n}.{n}"));

                if ($this->request->data['shifted'] == $this->request->data['movedTo']) {
                    if ($roleFilter[0][0] == 'shifted')
                        $selectedArray = 1;
                    if ($roleFilter[0][0] == 'selected')
                        $shiftedDownArray = 1;
                }
                if (($roleFilter[$selectedArray]['i_ref_bu_id'] != $roleFilter[$shiftedDownArray]['i_ref_bu_id'])) {
                    throw new Exception("You cant move with different Business Unit");
                }
                if (($roleFilter[$selectedArray]['i_ref_bu_id'] == $roleFilter[$shiftedArray]['i_ref_bu_id']) || ($this->request->data['movedTo'] == $this->request->data['moved_role_id'])) {
                    $getChildRoles = $this->getChildRole($roleFilter[$selectedArray]['id']);
                    $getChildRolesIdCol = array_column($getChildRoles, 'id');

                    $roles = $this->Role->find('list', array('conditions' => array('Role.i_ref_company_id' => $this->Auth->user('Company.id'), 'Role.id !=' => $getChildRolesIdCol, 'Role.i_ref_bu_id' => $roleFilter[$selectedArray]['i_ref_bu_id'], 'Role.i_status' => 1, 'Role.deleted_at' => NULL)));
                    unset($roles[$roleFilter[$selectedArray]['id']]);

                    $this->loadModel('Level');
                    $levels = $this->Level->find('all', array('fields' => ['id', 'vc_name', 'i_start_limit', 'i_end_limit'], 'conditions' => array('Level.i_ref_company_id' => $this->Auth->user('Company.id'), 'Level.i_status' => 1, 'Level.deleted_at' => NULL)));

                    $array = [
                        'roles' => $roles,
                        'selectedRole' => $roleFilter[$selectedArray]['vc_name'],
                        'selectedRoleBU' => $getRole[$selectedArray]['BusinessUnit']['vc_short_name'],
                        'selectedRoleLevel' => $roleFilter[$selectedArray]['i_ref_level_id'],
                        'childSelectedRole' => $getChildRoles,
                        'levels' => $levels,
                    ];
                    $return['data'] = $array;
                } else {
                    throw new Exception("You cant move with different Business Unit");
                }
            } else {
                throw new Exception("You cant move with different Parent Tree");
            }
        } catch (Exception $ex) {
            $return['exception_message'] = $ex->getMessage();
        }
        return json_encode($return);
    }

    /**
     * build tree array
     */
    public function buildTree(array $elements, $parentId = 0)
    {
        $branch = [];
        // print_r($elements);die;
        foreach ($elements as $key => $element) {
            if ($element['i_ref_role_id'] == $parentId) {
                // echo $element['id'];
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['sub_role'] = $children;
                }
                array_push($branch, $element);
            }
        }
        return $branch;
    }

    /**
     * Get All sub role id's
     */
    public function getSubrole(array $rows)
    {
        foreach ($rows as $row) {
            if (isset($row['sub_role'])) {
                array_push($this->ids, $row['id']);
                $this->getSubrole($row['sub_role']);
            } else {
                array_push($this->ids, $row['id']);
            }
        }
        return $this->ids;
    }


    public function check_account_payable_exists(Request $request)
    {

        $data = Role::select('*')->where('i_ref_company_id', $request->company_id)->where('account_payable', 1)->first();

        if (!empty($data)) {
            return true;
        } else {
            return false;
        }
    }


    public function check_supplier_approver_exists(Request $request)
    {

        $data = Role::select('*')->where('i_ref_company_id', $request->company_id)->where('supplier_approver', 1)->first();

        if (!empty($data)) {
            return true;
        } else {
            return false;
        }
    }

    public function check_system_administrator_exists(Request $request)
    {

        $data = Role::select('*')->where('i_ref_company_id', $request->company_id)->where('system_administrator', 1)->first();

        if (!empty($data)) {
            return true;
        } else {
            return false;
        }
    }



    public function check_alternative_supplier_approver_exist(Request $request)
    {

        $data = Role::select('*')->where('i_ref_company_id', $request->company_id)->where('alternative_supplier_approver', 1)->first();

        if (!empty($data)) {
            return true;
        } else {
            return false;
        }
    }


    public function update_account_payable(Request $request)
    {
        $input['account_payable'] = 0;

        Role::select('*')->where('i_ref_company_id', $request->company_id)->update($input);
        $input['account_payable'] = 1;
        if (!empty($request->id)) {

            $rolesRow = Role::findOrFail($request->id);
            $rolesRow->update($input);
        }
    }


    public function update_supplier_approver(Request $request)
    {
        $input['supplier_approver'] = 0;

        Role::select('*')->where('i_ref_company_id', $request->company_id)->update($input);

        $input['supplier_approver'] = 1;
        if (!empty($request->id)) {

            $rolesRow = Role::findOrFail($request->id);
            $rolesRow->update($input);
        }
    }

    public function update_system_administrator(Request $request)
    {
        $input['system_administrator'] = 0;

        Role::select('*')->where('i_ref_company_id', $request->company_id)->update($input);
        $input['system_administrator'] = 1;
        if (!empty($request->id)) {

            $rolesRow = Role::findOrFail($request->id);
            $rolesRow->update($input);
        }
    }





    public function update_alternative_supplier_approver(Request $request)
    {

        $input['alternative_supplier_approver'] = 0;

        Role::select('*')->where('i_ref_company_id', $request->company_id)->update($input);

        $input['alternative_supplier_approver'] = 1;
        if (!empty($request->id)) {
            $rolesRow = Role::findOrFail($request->id);
            $rolesRow->update($input);
        }
    }




    public function ajax_create_role(RoleRequest $request)
    {
        try {
            if ($request->validated()) {
                $input = $request->only(['vc_name', 'vc_description', 'i_ref_bu_id', 'i_ref_level_id', 'i_status']);
                $company = $this->P2bService->getUser(Auth::id());

                $input['i_ref_company_id'] = isset($company['users_details']['i_ref_company_id']) ? $company['users_details']['i_ref_company_id'] : '';
                $input['account_payable'] = isset($request->account_payable) && $request->account_payable == 'on' ? 1 : 0;
                $input['i_ref_role_id'] = isset($request->i_ref_role_id) && !empty($request->i_ref_role_id) ? $request->i_ref_role_id : 0;
                $input['supplier_approver'] = isset($request->supplier_approver) && $request->supplier_approver == 'on' ? 1 : 0;
                $input['system_administrator'] = isset($request->system_administrator) && $request->system_administrator == 'on' ? 1 : 0;
                $input['alternative_supplier_approver'] = isset($request->alternative_supplier_approver) && $request->alternative_supplier_approver == 'on' ? 1 : 0;
              
                $rolesRow = Role::create($input);

                if ($request->has('permission_id') && is_array($request->permission_id) && !empty($request->permission_id)) {
                    $permission_ids = [];
                    foreach ($request->permission_id as $permission) {
                        $permissions['permission_id'] = $permission;
                        array_push($permission_ids, $permissions);
                    }
                    $rolesRow->role_permission()->createMany($permission_ids);
                }

                if ($request->has('form_permission_id') && is_array($request->form_permission_id) && !empty($request->form_permission_id)) {
                    $form_permission_ids = [];
                    foreach ($request->form_permission_id as $form_permission) {
                        $form_perm['form_permission_id'] = $form_permission;
                        array_push($form_permission_ids, $form_perm);
                    }
                    $rolesRow->role_form_permission()->createMany($form_permission_ids);
                }

                // print_r($request->all());die;
                return json_encode(["name"=>$rolesRow->vc_name,"id"=>$rolesRow->id,"done"=>"done"]);
            }
        } catch (Exception $ex) {
            return json_encode($ex);
        }
    }


    public function update_user_role(Request $request)
    {
        // dd($request->all());
        $id = encrypt_decrypt('decrypt', $request->id);
        $input['i_ref_role_id']=$request->i_ref_role_id;
        UserDetail::where('i_ref_user_id',"=",$id)->update($input);
        // $data->i_ref_role_id=$request->i_ref_role_id;
        // $data->save();
        return json_encode("done");
    }

    public function getrole_by_business_department(Request $request)
    {
        $data=$request->department;
        $roles = Business_unit::with([
            'business_dept.dept_data' => function ($query) use ($data){
                $query->where('i_status', 1)->where('id', $data);
            },
            'roles' => function ($query) {
                $query->where('i_status', 1);
            },
            'roles.user_detail'
        ])->where('id', $request->business)->first();
    
       return $roles;
    }


}
