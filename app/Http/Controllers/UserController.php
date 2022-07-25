<?php

namespace App\Http\Controllers;

use Auth;
use Storage;
use App\Models\Role;
use App\Models\Level;
use App\Models\Users;
use App\Models\Country;
use App\Models\Project;
use App\Mail\EditUserMail;
use App\Models\Permission;
use App\Models\UserDetail;
use App\Models\UserProject;
use Illuminate\Http\Request;
use App\Models\Business_unit;
use App\Mail\RegisterUserMail;
use App\Models\FormPermission;
use App\Http\Requests\UserRequest;
use App\Services\P2B as P2BService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;


class UserController extends Controller
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
        $active = 'users';
        $user_data=UserDetail::with(['user'=> function ($query) {
            $query->where('user_type', Users::Employee)->orderBy('id', 'desc');
        },'user.country','user.users_details.business_unit','user.user_project.project'])
        ->orderBy('id', 'desc')->get();
        
        return view('admin.user.index', compact('active', 'user_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::select('id', 'name')->where('status', 1)->get();
        $business_units = Business_unit::select('id', 'vc_short_name', 'i_status')->where('i_status', 1)->get();
        $active = 'users';

        $bu = Business_unit::select('id', 'vc_short_name', 'i_status')->where('i_status', 1)->get();
        $levels = Level::where('i_status', 1)->get();
        $permissions = Permission::where('is_visible_to_supplier', 0)->where('hub_permissions', 1)->get();
        $form_permissions = FormPermission::get();
        $company = $this->P2bService->getUser(Auth::id());
        $company_id = !empty($company['users_details']['i_ref_company_id']) ? $company['users_details']['i_ref_company_id'] : 1;

        $account_payable = Role::select('*')->where('i_ref_company_id', $company_id)->where('account_payable', 1)->first();
        $supplier_approver = Role::select('*')->where('i_ref_company_id', $company_id)->where('supplier_approver', 1)->first();
        $system_administrator = Role::select('*')->where('i_ref_company_id', $company_id)->where('system_administrator', 1)->first();

        return view('admin.user.create', compact('active', 'countries', 'business_units','bu','levels','permissions','form_permissions','company_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
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
                    // $upload = Storage::disk('s3')->put($filePath, file_get_contents($image));
                    $path = Storage::disk('s3')->put('users', $request->vc_image);
                    // $image_path = Storage::disk('s3')->url($path);
                }

                $rand_password = $this->_generateRandomString();

                $input = $request->only(['vc_title', 'vc_fname', 'vc_mname', 'vc_lname', 'email', 'address', 'i_ref_country_id', 'i_ref_state_id', 'vc_city', 'vc_zip_code', 'vc_phone', 'vc_phone_corr_2']);

                $input['vc_image'] = $path;
                $input['user_type'] = Users::Employee;
                $input['hash_password'] = Hash::make($rand_password);
                $input['i_status'] = 1;
                $input['vc_title'] = (!empty($request->input('vc_title')) ? $request->input('vc_title') : "");
                $userRow = Users::create($input);
                $userId = $userRow->id;

                if ($userId != '') {

                    $company = $this->P2bService->getUser(Auth::id());
                    $company_id = isset($company['users_details']['i_ref_company_id']) ? $company['users_details']['i_ref_company_id'] : '';
                    $detail_data = $request->only(['i_ref_bu_id', 'i_ref_dep_id', 'i_ref_role_id']);
                    $detail_data['i_ref_user_id'] = $userId;
                    $detail_data['i_ref_company_id'] = $company_id;
                    $detail_data['i_status'] = 1;
                    $userDetail = UserDetail::create($detail_data);
                }

                $when = now()->addMinutes(1);
                $data = array(
                    'name' => $userRow['vc_fname'] . ' ' . $userRow['vc_mname'] . ' ' . $userRow['vc_lname'],
                    'email' => $userRow['email'],
                    'password' => $rand_password,
                );

                $mail_id = $userRow['email'];
                $sendMail = new RegisterUserMail($data);
                $mail = Mail::to($mail_id)->later($when, $sendMail);

                return redirect()->route('users.index')->with('success', 'User saved successfully!');
            }
        } catch (Exception $ex) {
            return redirect()->route('users.index')->with('error', $ex->getMessage());
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
        $userdata = Users::select(
            'id',
            'vc_title',
            'vc_fname',
            'vc_mname',
            'vc_lname',
            'email',
            'email_corr_2',
            'vc_phone',
            'vc_phone_corr_2',
            'address',
            'i_ref_country_id',
            'i_ref_state_id',
            'vc_city',
            'vc_zip_code',
            'i_status',
            'user_type',
            'vc_image'
        )->with([
            'users_details' => function ($query) {
                $query->select('id', 'i_ref_user_id', 'i_ref_company_id', 'i_ref_bu_id', 'i_ref_dep_id', 'i_ref_role_id');
            },
            'users_details.business_unit' => function ($query) {
                $query->select('id', 'vc_short_name');
            },
            'users_details.department' => function ($query) {
                $query->select('id', 'vc_name');
            },
            'users_details.roles' => function ($query) {
                $query->select('id', 'vc_name');
            },
            'users_details.company.country' => function ($query) {
                $query->select('id', 'name');
            },
            'users_details.company.state' => function ($query) {
                $query->select('id', 'name');
            },
            'country' => function ($query) {
                $query->select('id', 'name');
            },
            'state' => function ($query) {
                $query->select('id', 'name');
            },
            'user_project.project' => function ($query) {
                $query->select('id', 'vc_name', 'vc_description', 'created', 'modified');
            }
        ])->where('id', $id)->first();
        // pr($userdata);die;
        $active = 'users';
        return view('admin.user.show', compact('active', 'userdata'));
    }

    /**
     * Show the form for editing the specinfied resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = encrypt_decrypt('decrypt', $id);
        $countries = Country::select('id', 'name')->where('status', 1)->get();
        $userdata = Users::select(
            'id',
            'vc_title',
            'vc_fname',
            'vc_mname',
            'vc_lname',
            'email',
            'email_corr_2',
            'vc_phone',
            'vc_phone_corr_2',
            'address',
            'i_ref_country_id',
            'i_ref_state_id',
            'vc_city',
            'vc_zip_code',
            'i_status',
            'user_type',
            'vc_image'
        )->with([
            'users_details' => function ($query) {
                $query->select('id', 'i_ref_user_id', 'i_ref_company_id', 'i_ref_bu_id', 'i_ref_dep_id', 'i_ref_role_id', 'i_status');
            },
            'users_details.business_unit.business_dept.dept_data' => function ($query) {
                $query->select('id', 'vc_name');
            },
            'users_details.business_unit.roles' => function ($query) {
                $query->select('id', 'vc_name', 'i_ref_bu_id');
            },
            'country.states' => function ($query) {
                $query->select('id', 'name', 'country_id');
            }
        ])->where('id', $id)->first();
        $business_units = Business_unit::select('id', 'vc_short_name', 'i_status')->where('i_status', 1)->get();
        $bu = Business_unit::select('id', 'vc_short_name', 'i_status')->where('i_status', 1)->get();
        $levels = Level::where('i_status', 1)->get();
        $permissions = Permission::where('is_visible_to_supplier', 0)->where('hub_permissions', 1)->get();
        $form_permissions = FormPermission::get();
        $company = $this->P2bService->getUser(Auth::id());
        $company_id = !empty($company['users_details']['i_ref_company_id']) ? $company['users_details']['i_ref_company_id'] : 1;

        $url = !empty($userdata['vc_image']) ? 'https://s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/' . $userdata['vc_image'] : '';
        $active = 'users';
        return view('admin.user.edit', compact('active', 'userdata', 'countries', 'url', 'business_units','levels','permissions','form_permissions','company_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            if ($request->validated()) {
                $image = !empty($request->file('vc_image')) ? $request->file('vc_image') : '';
                $imageName = '';
                $path = '';
                //upload file in s3 bucket
                if (isset($image) && $image !== '') {
                    // $imageName = time() . '.' . $image->extension();
                    $filePath =  $imageName;
                    $path = Storage::disk('s3')->put('users', $request->vc_image);
                }

                $input = $request->only(['vc_title', 'vc_fname', 'vc_mname', 'vc_lname', 'email', 'address', 'i_ref_country_id', 'i_ref_state_id', 'vc_city', 'vc_zip_code', 'vc_phone', 'vc_phone_corr_2']);
                if (!empty($image)) {
                    $input['vc_image'] = $path;
                }
                if (isset($request->password) && !empty($request->password)) {
                    $input['hash_password'] = Hash::make($request->password);
                }
                $userRow = Users::findOrFail($id);
                $userRow->update($input);

                $detail_data = $request->only(['i_ref_bu_id', 'i_ref_dep_id', 'i_ref_role_id']);
                $detail_data['i_ref_user_id'] = $id;
                $userDetail = UserDetail::findOrFail($request->user_detail_id)->update($detail_data);

                if (isset($request->password) && !empty($request->password) || $request->old_email != $request->email) {
                    $when = now()->addMinutes(1);
                    $data = array(
                        'name' => $userRow['vc_fname'] . ' ' . $userRow['vc_mname'] . ' ' . $userRow['vc_lname'],
                        'email' => $request->email,
                        'password' => isset($request->password) ? $request->password : '',
                    );

                    $mail_id = $userRow['email'];
                    $sendMail = new EditUserMail($data);
                    $mail = Mail::to($mail_id)->later($when, $sendMail);
                }

                return redirect()->route('users.index')->with('success', 'User updated successfully!');
            }
        } catch (Exception $ex) {
            return redirect()->route('users.index')->with('error', $ex->getMessage());
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
            $user_data = Users::withCount('user_project')->findOrFail($id);
            if ($user_data->user_project_count > 0) {
                session()->flash('success', "Unable to archive User ! It is mapped to Projects !");
            } else {
                $user_data->delete();
                session()->flash('success', "User archived successfully !");
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

        $user_data=UserDetail::with(['user'=> function ($query) {
            $query->onlyTrashed()->where('user_type', Users::Employee)->orderBy('id', 'desc');
        },'user.country','user.users_details.business_unit','user.user_project.project'])
        ->orderBy('id', 'desc')->get();
        
        $active = 'archive_user';
        $company = $this->P2bService->getUser(Auth::id());
        $company_id = !empty($company['users_details']['i_ref_company_id']) ? $company['users_details']['i_ref_company_id'] : 1;
        // $user_data = Users::onlyTrashed()->select('id', 'vc_fname', 'vc_mname', 'vc_lname', 'vc_image', 'email', 'vc_phone', 'i_ref_country_id', 'i_status')
        //     ->with([
        //         'users_details' => function ($query) {
        //             $query->select('id', 'i_ref_user_id', 'i_ref_company_id', 'i_ref_bu_id');
        //         },
        //         'users_details.business_unit' => function ($query) {
        //             $query->select('id', 'vc_short_name');
        //         },
        //         'country' => function ($query) {
        //             $query->select('id', 'name');
        //         },
        //         'user_project.project' => function ($query) {
        //             $query->select('id', 'vc_name');
        //         }
        //     ])
        //     ->where('user_type', Users::Employee)->orderBy('id', 'desc')->get();
            
        $avliable_role=Role::with(['user_detail.user'])->get();
        
        return view('admin.user.archive', compact('active', 'user_data','avliable_role'
        ));
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
            $request->session()->flash('success', 'User restored successfully!');
            return;
        } catch (Exception $ex) {
            session()->flash('error', $ex->getMessage());
            return;
        }
    }

    /**
     * Check email is not exists
     */
    public function checkEmail(Request $request)
    {
        if ($request->has('email') && !empty($request->email)) {
            if (Users::whereEmail($request->email)->exists()) {
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
     * assign and revoke
     * project to user
     */

    public function assignRevokeProject($id)
    {
        $id = encrypt_decrypt('decrypt', $id);
        $active = "users";
        $userdata = Users::select('id', 'vc_fname', 'vc_mname')->with([
            'users_details' => function ($query) {
                $query->select('id', 'i_ref_user_id', 'i_ref_bu_id');
            },
            'users_details.business_unit' => function ($query) {
                $query->select('id', 'vc_short_name');
            },
            'user_project'
        ])->where('id', $id)->first();

        $userdata->user_project->each->makeHidden(['id', 'i_ref_user_id']);
        $userdata->user_project = array_column($userdata->user_project->toArray(), 'i_ref_project_id');

        $projects = Project::select('id', 'vc_name', 'i_ref_bu_id')->where('i_ref_bu_id', $userdata->users_details->i_ref_bu_id)->get();
        return view('admin.user.assign-revoke-project', compact('active', 'userdata', 'projects'));
    }

    /**
     * save assigned
     * project to user
     */

    public function saveProject(Request $request, $id)
    {
        $id = encrypt_decrypt('decrypt', $id);
        try {

            $usersRow = Users::select('id')->findOrFail($id);

            $old_project = !empty($request->old_project) ? unserialize(base64_decode($request->old_project)) : '';
            if (!empty($request->i_ref_project_id)) {
                $project_ids = [];
                if (!empty($old_project) && array_diff($old_project, $request->i_ref_project_id)) {
                    //delete projects that are not belongs to user
                    $delete_project = array_diff($old_project, $request->i_ref_project_id);
                    $bu_dept = UserProject::where('i_ref_user_id', $id)->whereIn('i_ref_project_id', $delete_project)->delete();
                }
                foreach ($request->i_ref_project_id as $project) {
                    if (!empty($old_project) && in_array($project, $old_project)) {
                    } else {
                        $projects['i_ref_project_id'] = $project;
                        array_push($project_ids, $projects);
                    }
                }
                if (!empty($project_ids)) {
                    $usersRow->user_project()->createMany($project_ids);
                }
            } else {
                // delete all project
                $bu_dept = UserProject::where('i_ref_user_id', $id)->delete();
            }
            return redirect()->route('users.index')->with('success', 'User project updated successfully!');
        } catch (Exception $ex) {
            return redirect()->route('users.index')->with('error', $ex->getMessage());
        }
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

    public function check_role_has_user(Request $request)
    {
        try {
            \DB::enableQueryLog();
            $roleData = Role::with([
                'user_detail.user'
            ])->findOrFail($request->id);
                // dd($roleData);
            if (!empty($roleData)) {
                return $roleData;
            }
        } catch (Exception $ex) {
            session()->flash('error', $ex->getMessage());
            return;
        }
    }


    public function check_role_avliable(Request $request)
    {
        $id = encrypt_decrypt('decrypt', $request->id);
        try {
            $Users = Users::with(['users_details'])->withTrashed()->find($id);
            $UserDetail = UserDetail::where("i_ref_role_id",'=',$Users->users_details->i_ref_role_id)->get();
            foreach($UserDetail as $key => $values){
                $User[] = Users::find($values->i_ref_user_id);
            }

            if(!empty($User)){
                return json_encode(["status"=>"failed","data"=>$Users]);
            }else{
                return json_encode(["status"=>"done"]);
            }
        } catch (Exception $ex) {
            session()->flash('error', $ex->getMessage());
            return;
        }
    }



}
