<?php
namespace App\Services;

use App\Models\{
    Business_unit as BusinessUnitModel,
    Company as CompanyModel,
    Department as DepartmentModel,
    Group as GroupModel,
    GroupsRole as GroupRoleModel,
    Locations as LocationsModel,
    Project as ProjectModel,
    Role as RoleModel,
    RolesFormPermissions,
    UserDetail as UserDetailModel,
    UserPermissions as UserPermissionsModel,
    Users as UsersModel
};

/**
 * P2B service class
 * get data from second database of P2B
 * @return collection
 */
class P2B
{
    /**
     * get listing of business unit
     * @return records
     */
    public function getBusinessUnits()
    {
        $data = BusinessUnitModel::with('business_dept.dept_data', 'projects')->where('i_status', 1)->get();
        return $data;
    }

    /**
     * get business unit
     * @return records
     */
    public function getBusinessUnit($id)
    {
        $data = BusinessUnitModel::with('business_dept.dept_data', 'projects')->Where('id', $id)->first();
        return $data;
    }

    /**
     * get listing of Department
     * @return records
     */
    public function getDepartments($where = [], $select = [])
    {
        $data = DepartmentModel::on('mysql2');
        if (is_array($select) && !empty($select)) {
            $data = $data->select($select);
        }

        if (is_array($where) && !empty($where)) {
            $data = $data->where($where);
        }
        return $data->where('i_status', 1)->get();
    }

    /**
     * get Department
     * @return records
     */
    public function getDepartment($id)
    {
        $data = DepartmentModel::Where('id', $id)->first(); // static method
        return $data;
    }

    /**
     * get listing of Department
     * @return records
     */
    public function getProjects($where = [], $select = [])
    {
        $rows = ProjectModel::on('mysql2'); // static method

        if (is_array($select) && !empty($select)) {
            $rows = $rows->select($select);
        }

        if (is_array($where) && !empty($where)) {
            $rows = $rows->where($where);
        }

        return $rows->where('i_status', 1)->get();
    }

    /**
     * get Department
     * @return records
     */
    public function getProject($id)
    {
        $data = ProjectModel::Where('id', $id)->first(); // static method
        return $data;
    }

    public function getUser($id)
    {
        $data = UsersModel::select('id', 'vc_title', 'vc_fname', 'vc_mname', 'vc_lname', 'email', 'password', 'vc_image', 'vc_phone', 'i_ref_country_id', 'i_ref_state_id', 'vc_city', 'i_status', 'user_type')
        ->with(['users_details' => function($query){
            $query->select('id', 'i_ref_company_id', 'i_ref_user_id');
        } ])->Where('id', $id)->first();
        return $data;
    }

    public function getLocation($id)
    {
        $data = LocationsModel::Where('id', $id)->first();
        return $data;
    }

    public function getRoles()
    {
        $data = RoleModel::on('mysql2')->get();
        return $data;
    }

    public function getGroupRole()
    {
        $data = GroupRoleModel::with('roles.user_detail', 'groups')->get();
        return $data;
    }

    public function getGroupOnly($select = [])
    {
        $data = GroupModel::on('mysql2');

        if (is_array($select) && !empty($select)) {
            $data = $data->select($select);
        }
        return $data->get();
    }

    public function getGroups($select = [])
    {
        $data = GroupModel::with([
            'group_role',
            'group_role.roles',
            'group_role.roles.user_detail',
            'group_role.roles.user_detail.user',
        ]);

        if (is_array($select) && !empty($select)) {
            $data = $data->select($select);
        }
        return $data->get();
    }

    public function getGroup($id)
    {
        $data = GroupModel::with('group_role.roles.user_detail.user')->Where('id', $id)->first();
        return $data;
    }

    public function getCompnyProfilePic($id)
    {
        $data = CompanyModel::Where('id', $id)->first();
        return $data;
    }

    public function getcompanies()
    {
        $data = CompanyModel::get();
        return $data;
    }

    public function getallUsers($where = [], $select = [])
    {

        $rows = UsersModel::on('mysql2');

        if (is_array($select) && !empty($select)) {
            $rows = $rows->select($select);
        }

        if (is_array($where) && !empty($where)) {
            $rows = $rows->where($where);
        }


        // $rows =UserDetailModel::on('mysql2');
        // $asd= ['user'=> function ($query) use ($select,$where) {
        //     if (is_array($select) && !empty($select)) {
        //         $query = $query->select($select);
        //     }
        //     if (is_array($where) && !empty($where)) {
        //         $query = $query->where($where);
        //     }
        //     $query->orderBy('id', 'desc')->get();
        // }];
        // $rows=$rows->select("*")->with($asd)->orderBy('id', 'desc')->get();
        

        return $rows->get();
    }

    public function getallLocation()
    {
        $data = LocationsModel::get();
        return $data;
    }

    /**
     * get all User create actions
     */
    public function getAllUsersForCreateActions()
    {
        $supplierIds = UserPermissionsModel::selectRaw('DISTINCT user_id')
                ->wherePermissionId(21)->get()->toArray();
        $userIds = UserDetailModel::selectRaw("DISTINCT i_ref_user_id AS `user_id`")->
        whereRaw("`i_ref_role_id` IN (SELECT `role_id` FROM `roles_form_permissions` WHERE `form_permission_id` = 5)")->get();
        $supplierIds = UserDetailModel::selectRaw("DISTINCT i_ref_user_id AS `user_id`")->
        WhereIn('i_ref_user_id',$supplierIds)->get();
        $compnayIds = UserDetailModel::selectRaw("DISTINCT i_ref_user_id AS `user_id`")->
        Where('i_ref_role_id',0)->first();


        $ids = [];

        if ($userIds) {
            $ids = array_merge($ids, $userIds->toArray());
        }

        if ($supplierIds) {
            $ids = array_merge($ids, $supplierIds->toArray());
        }

        if ($compnayIds) {
            $ids = array_merge($ids, $compnayIds->toArray());
        }
        // dd($ids);


        $rows = UsersModel::on('mysql2')->with(['users_details.roles']);
        $rows = $rows->select("id", "vc_title", "vc_fname", "vc_mname", "vc_lname", "vc_sname", "email","bussiness_name");
        // $rows = $rows->where("user_type", "company");
        $rows = $rows->WhereIn("id", $ids);
        return $rows->get();
    }


    public function getAllCompanyUsers($condition=null)
    {   
        $supplierIds = UserPermissionsModel::selectRaw('DISTINCT user_id')->wherePermissionId(21)->get()->toArray();
        $userIds = UserDetailModel::selectRaw("DISTINCT i_ref_user_id AS `user_id`")->
        whereRaw("`i_ref_role_id` IN (SELECT `role_id` FROM `roles_form_permissions` WHERE `form_permission_id` = 5)")->get();
        
        $supplierIds = UserDetailModel::selectRaw("DISTINCT i_ref_user_id AS `user_id`")->
        WhereIn('i_ref_user_id',$supplierIds)->get();
        $compnayIds = UserDetailModel::selectRaw("DISTINCT i_ref_user_id AS `user_id`")->
        Where('i_ref_role_id',0)->first();

        $ids = [];

        if ($supplierIds) {
            $ids = $userIds->toArray();
        }

        if ($supplierIds) {
            $ids = array_merge($ids, $supplierIds->toArray());
        }
        if ($compnayIds) {
            $ids = array_merge($ids, $compnayIds->toArray());
        }

        $rows = UsersModel::on('mysql2')->with(['users_details.roles']);
        $rows = $rows->select("id", "vc_title", "vc_fname", "vc_mname", "vc_lname", "vc_sname", "email","bussiness_name");
        // $rows = $rows->where("user_type", "company");
        if(!empty($condition)){
            $rows->where($condition);
        }
        $rows = $rows->WhereIn("id", $ids);
        return $rows->get();
    }
}
