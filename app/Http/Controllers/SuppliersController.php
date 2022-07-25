<?php

namespace App\Http\Controllers;

use Auth;
use Storage;
use App\Models\Role;
use App\Models\Users;
use App\Models\Country;
use App\Models\UserDetail;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\UserPermissions;
use App\Services\P2B as P2BService;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\SupplierRequest;

class SuppliersController extends Controller
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
        $i_ref_company_id=auth()->user()->users_details->i_ref_company_id;
        $supplier_data=UserDetail::with(['user'=> function ($query) {
            $query->where('user_type', Users::Supplier)->orderBy('id', 'desc');
        },'user.country','user.users_details.business_unit','user.user_project.project'])
        ->where('user_details.i_ref_company_id', $i_ref_company_id)->orderBy('id', 'desc')->get();

        $active = 'suppliers';
        return view('admin.supplier.index', compact('active', 'supplier_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::select('id', 'name')->get();
        $active = 'suppliers';
        return view('admin.supplier.create', compact('active', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierRequest $request)
    {
        try {
            if ($request->validated()) {
                $image = !empty($request->file('vc_image')) ? $request->file('vc_image') : '';
                $imageName = '';
                $path = '';
                //upload file in s3 bucket
                if (isset($image) && $image !== '') {
                    $imageName = time() . '.' . $image->extension();
                    $filePath =  $imageName;
                    $path = Storage::disk('s3')->put('suppliers', $request->vc_image);
                }

                $rand_password = $this->_generateRandomString();


                $input = $request->only(['bussiness_name', 'buss_no', 'vc_fname', 'vc_lname', 'vc_sname', 'email', 'vc_DOPAS']);
                $input['vc_image'] = $path;
                $input['user_type'] = Users::Supplier;
                $input['hash_password'] = Hash::make($rand_password);
                $input['i_status'] = 0;
                $supplierRow = Users::create($input);
                $supplierId = $supplierRow->id;

                if ($request->has('permission_id') && is_array($request->permission_id) && !empty($request->permission_id)) {
                    $permission_ids = [];
                    foreach ($request->permission_id as $perm) {
                        $permission['permission_id'] = $perm;
                        array_push($permission_ids, $permission);
                    }
                    $supplierRow->user_permissions()->createMany($permission_ids);
                }

                if ($supplierId != '') {

                    $company = $this->P2bService->getUser(Auth::id());
                    $company_id = isset($company['users_details']['i_ref_company_id']) ? $company['users_details']['i_ref_company_id'] : '';
                    $detail_data = $request->only([
                        'hd_office_street', 'hd_office_city', 'hd_office_state', 'hd_office_postalcode', 'hd_office_country', 'hd_office_phone', 'lc_office_street', 'lc_office_city', 'lc_office_state', 'lc_office_postalcode', 'lc_office_country',
                        'lc_office_phone', 'account_email'
                    ]);
                    $detail_data['i_ref_user_id'] = $supplierId;
                    $detail_data['i_ref_company_id'] = $company_id;
                    $detail_data['i_status'] = 1;
                    $supplierDetail = UserDetail::create($detail_data);
                }

                $company = $this->P2bService->getUser(Auth::id());
                $company_id = !empty($company['users_details']['i_ref_company_id']) ? $company['users_details']['i_ref_company_id'] : 1;
                $supplier_approver_id = Role::with(['user_detail.user'])->select('*')->where('i_ref_company_id', $company_id)->where('supplier_approver', 1)->first();
                $alternative_supplier_approver_id = Role::with(['user_detail.user'])->select('*')->where('i_ref_company_id', $company_id)->where('alternative_supplier_approver', 1)->first();

                if (!empty($supplier_approver_id)) {
                    if (!empty($supplier_approver_id->user_detail)) {
                        if (!empty($supplier_approver_id->user_detail->user)) {
                            $Notification['from_user_id'] = Auth::id();
                            $Notification['title'] = "Approved New Supplier";
                            $Notification['message'] = "Approved New supplier";
                            $Notification['notificationable_id'] = $supplierId;
                            $Notification['notificationable_type'] = "App\Models\Users";
                            $Notification['notification_type'] = 35;
                            $Notification['status'] = 0;
                            $Notification['user_id'] = $supplier_approver_id->user_detail->user->id;
                            $supplierRow->notifications()->create($Notification);
                        }
                    }
                }

                if (!empty($alternative_supplier_approver_id)) {
                    // $Notification= new Notification;
                    if (!empty($alternative_supplier_approver_id->user_detail)) {
                        if (!empty($alternative_supplier_approver_id->user_detail->user)) {
                            $Notification['from_user_id'] = Auth::id();
                            $Notification['title'] = "Approved New Supplier";
                            $Notification['message'] = "Approved New supplier";
                            $Notification['notificationable_id'] = $supplierId;
                            $Notification['notificationable_type'] = "App\Models\Users";
                            $Notification['notification_type'] = 35;
                            $Notification['status'] = 0;
                            $Notification['user_id'] = $alternative_supplier_approver_id->user_detail->user->id;
                            $supplierRow->notifications()->create($Notification);
                        }
                    }
                }

                return redirect()->route('suppliers.index')->with('success', 'Suuplier saved successfully!');
            }
        } catch (Exception $ex) {
            return redirect()->route('suppliers.index')->with('error', $ex->getMessage());
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
        $active = 'suppliers';
        $supplier_data = Users::select('id', 'bussiness_name', 'buss_no', 'vc_fname', 'vc_lname', 'email', 'vc_DOPAS', 'swift_code', 'bank_BSB_number', 'tax_File_number', 'australlian_business_number', 'company_business_number', 'vc_image', 'created', 'modified')->where('user_type', Users::Supplier)
            ->with([
                'users_details' => function ($query) {
                    $query->select([
                        'id', 'i_ref_user_id', 'hd_office_street', 'hd_office_city', 'hd_office_state', 'hd_office_postalcode', 'hd_office_country', 'hd_office_phone', 'lc_office_street', 'lc_office_city', 'lc_office_state', 'lc_office_postalcode', 'lc_office_country',
                        'lc_office_phone', 'account_email', 'bank_name', 'bank_branch', 'bank_address', 'bank_code', 'bank_city', 'bank_country', 'bank_account_name', 'bank_account_no', 'payment_currency', 'beneficiary_details', 'i_status'
                    ]);
                }
            ])->where('id', $id)->first();
        // pr($supplier_data);die;
        return view('admin.supplier.show', compact('active', 'supplier_data'));
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
        $countries = Country::select('id', 'name')->get();
        $active = 'suppliers';
        $supplier_data = Users::select('id', 'bussiness_name', 'vc_sname', 'buss_no', 'vc_fname', 'vc_lname', 'email', 'vc_DOPAS', 'swift_code', 'bank_BSB_number', 'tax_File_number', 'australlian_business_number', 'company_business_number', 'vc_image')->where('user_type', Users::Supplier)
            ->with([
                'users_details' => function ($query) {
                    $query->select([
                        'id', 'i_ref_user_id', 'hd_office_street', 'hd_office_city', 'hd_office_state', 'hd_office_postalcode', 'hd_office_country', 'hd_office_phone', 'lc_office_street', 'lc_office_city', 'lc_office_state', 'lc_office_postalcode', 'lc_office_country',
                        'lc_office_phone', 'account_email', 'bank_name', 'bank_branch', 'bank_address', 'bank_code', 'bank_city', 'bank_country', 'bank_account_name', 'bank_account_no', 'payment_currency', 'beneficiary_details', 'i_status'
                    ]);
                },
                'user_permissions'
            ])->where('id', $id)->first();
        $supplier_data->user_permissions->each->makeHidden(['id', 'user_id']);
        $supplier_data->user_permissions = array_column($supplier_data->user_permissions->toArray(), 'permission_id');
        // pr($supplier_data);die;
        return view('admin.supplier.edit', compact('active', 'countries', 'supplier_data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SupplierRequest $request, $id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);

            if ($request->validated()) {
                $image = !empty($request->file('vc_image')) ? $request->file('vc_image') : '';
                // print_r($image);die;
                $imageName = '';
                $path = '';

                //upload file in s3 bucket
                if (isset($image) && $image !== '') {
                    $filePath =  $imageName;
                    $path = Storage::disk('s3')->put('suppliers', $request->vc_image);
                }

                $input = $request->only(['bussiness_name', 'buss_no', 'vc_fname', 'vc_lname', 'vc_sname', 'email', 'vc_DOPAS']);
                if (!empty($image)) {
                    $input['vc_image'] = $path;
                }
                if (isset($request->password) && !empty($request->password)) {
                    $input['hash_password'] = Hash::make($request->password);
                }
                // print_r($input);die;
                $userRow = Users::findOrFail($id);
                $userRow->update($input);

                $detail_data = $request->only([
                    'hd_office_street', 'hd_office_city', 'hd_office_state', 'hd_office_postalcode', 'hd_office_country', 'hd_office_phone', 'lc_office_street', 'lc_office_city', 'lc_office_state', 'lc_office_postalcode', 'lc_office_country',
                    'lc_office_phone', 'account_email'
                ]);
                $detail_data['i_ref_user_id'] = $id;
                $userDetail = UserDetail::findOrFail($request->user_detail_id)->update($detail_data);

                $old_permission = !empty($request->old_permission) ? unserialize(base64_decode($request->old_permission)) : '';
                if (!empty($request->permission_id)) {
                    $permission_ids = [];
                    if (!empty($old_permission) && array_diff($old_permission, $request->permission_id)) {

                        //delete permission that are not belongs to role
                        $delete_permission = array_diff($old_permission, $request->permission_id);
                        $permissionRow = UserPermissions::where('user_id', $id)->whereIn('permission_id', $delete_permission)->delete();
                    }

                    foreach ($request->permission_id as $permission) {
                        if (!empty($old_permission) && in_array($permission, $old_permission)) {
                        } else {
                            $permissions['permission_id'] = $permission;
                            array_push($permission_ids, $permissions);
                        }
                    }
                    if (!empty($permission_ids)) {
                        $userRow->user_permissions()->createMany($permission_ids);
                    }
                } else {
                    // delete all permissions
                    $permissionRow = UserPermissions::where('user_id', $id)->delete();
                }

                return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully!');
            }
        } catch (Exception $ex) {
            return redirect()->route('suppliers.index')->with('error', $ex->getMessage());
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
            $project_data = Users::findOrFail($id);
            if ($project_data->delete()) {
                session()->flash('success', "Supplier archived successfully !");
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
        // $company = $this->P2bService->getUser(Auth::id());
        // $company_id = isset($company['users_details']['i_ref_company_id']) ? $company['users_details']['i_ref_company_id'] : '';
        // $supplier_data = Users::onlyTrashed()->select('id', 'bussiness_name', 'vc_fname', 'vc_image', 'vc_mname', 'vc_lname', 'email', 'vc_DOPAS', 'i_status', 'user_type')
        //     ->where('user_type', 'supplier')
        //     ->with(['users_details' => function ($query) {
        //         $query->select(['id', 'i_ref_user_id', 'hd_office_phone', 'i_status', 'i_ref_company_id']);
        //     }])
        //     ->orderBy('id', 'desc')->get();
        $i_ref_company_id=auth()->user()->users_details->i_ref_company_id;
        $supplier_data=UserDetail::with(['user'=> function ($query) {
            $query->onlyTrashed()->where('user_type', Users::Supplier)->orderBy('id', 'desc');
        },'user.country','user.users_details.business_unit','user.user_project.project'])
        ->where('user_details.i_ref_company_id', $i_ref_company_id)->orderBy('id', 'desc')->get();
    

        // dd($supplier_data);die;
        $active = 'archive_supplier';
        return view('admin.supplier.archived', compact('active', 'supplier_data', 'company_id'));
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
            $data = Users::withTrashed()->find($id);
            $data->restore();
            $request->session()->flash('success', 'Supplier restored successfully!');
            return;
        } catch (Exception $ex) {
            session()->flash('error', $ex->getMessage());
            return;
        }
    }

    /**
     * generate random string
     */

    public function _generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * toggle status 
     * of supplier
     */

    public function toggle_status(Request $request)
    {
        try {
            $data = UserDetail::select('id', 'i_ref_user_id', 'i_status')->where('i_ref_user_id', $request->id)->first();
            if (!empty($data)) {
                $data->i_status = $request->status;
                $dataRow = $data->save();
                return $dataRow;
            } else {
                return 0;
            }
        } catch (Exception $ex) {
            session()->flash('error', $ex->getMessage());
            return;
        }
    }


    public function supplier_approve_alert(Request $request)
    {
        try {

            $supplier_id = $request->id;

            $supplier_id = encrypt_decrypt('decrypt', $supplier_id);
            $company = $this->P2bService->getUser(Auth::id());
            $company_id = !empty($company['users_details']['i_ref_company_id']) ? $company['users_details']['i_ref_company_id'] : 1;
            $supplier_approver_id = Role::with(['user_detail.user'])->select('*')->where('i_ref_company_id', $company_id)->where('supplier_approver', 1)->first();
            $alternative_supplier_approver_id = Role::with(['user_detail.user'])->select('*')->where('i_ref_company_id', $company_id)->where('alternative_supplier_approver', 1)->first();
            $supplierRow = Users::findOrFail($supplier_id);

            if (!empty($supplier_approver_id)) {
                // $Notification= new Notification;
                if (!empty($supplier_approver_id->user_detail)) {
                    if (!empty($supplier_approver_id->user_detail->user)) {
                        $Notification['from_user_id'] = Auth::id();
                        $Notification['i_ref_user_role_id'] =$supplier_approver_id->user_detail->i_ref_role_id;
                        $Notification['i_ref_from_user_role_id'] = auth()->user()->users_details->i_ref_role_id;
                        $Notification['title'] = "Approve supplier as soon as possible";
                        $Notification['message'] = "Approve supplier as soon as possible";
                        $Notification['notificationable_id'] = $supplier_id;
                        $Notification['notificationable_type'] = "App\Models\Users";
                        $Notification['notification_type'] = 35;
                        $Notification['status'] = 0;
                        $Notification['user_id'] = $supplier_approver_id->user_detail->user->id;
                        $supplierRow->notifications()->create($Notification);
                    }
                }
            }


            if (!empty($alternative_supplier_approver_id)) {
                // $Notification= new Notification;
                if (!empty($alternative_supplier_approver_id->user_detail)) {
                    if (!empty($alternative_supplier_approver_id->user_detail->user)) {
                        $Notification['from_user_id'] = Auth::id();
                        $Notification['i_ref_user_role_id'] =$supplier_approver_id->user_detail->i_ref_role_id;
                        $Notification['i_ref_from_user_role_id'] = auth()->user()->users_details->i_ref_role_id;
                        $Notification['title'] = "Approve supplier as soon as possible";
                        $Notification['message'] = "Approve supplier as soon as possible";
                        $Notification['notificationable_id'] = $supplier_id;
                        $Notification['notificationable_type'] = "App\Models\Users";
                        $Notification['notification_type'] = 35;
                        $Notification['status'] = 0;
                        $Notification['user_id'] = $alternative_supplier_approver_id->user_detail->user->id;
                        $supplierRow->notifications()->create($Notification);
                    }
                }
            }

            return json_encode("true");
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }


    public function approved_supplier(Request $request)
    {

        try {
            $row = Users::findOrFail($request->id);
            $row->i_status = 1;
            $row->save();
            // $notify_row = Notification::where('id', $request->notification_id)->firstOrFail();
            // $notify_row->status = true;
            // $notify_row->save();

            Notification::where('id', $request->notification_id)
            ->where('notificationable_id', $request->id)->where
            ('notificationable_type', "App\Models\Users")
            ->update(['status' => 1]);


            return $this->returnResponse(HTTP_STATUS_OK, true, "Supplier Approved Successfully");
        } catch (\Exception $ex) {
            return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, $ex->getMessage());
        }
    }
}
