<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Auth;
class Users extends Authenticatable
{
    use SoftDeletes,Notifiable;
   
    //
    protected $connection = 'mysql2';

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'modified';

    protected $fillable = ['vc_title', 'vc_fname', 'vc_mname', 'vc_lname', 'vc_sname', 'buss_no', 'bussiness_name', 'email', 'email_corr_2', 'email_corr_3',
     'password' ,'hash_password', 'vc_image', 'vc_DOPAS', 'vc_phone', 'vc_phone_corr_2', 'vc_phone_corr_3', 'address', 'i_ref_country_id', 'i_ref_state_id', 'vc_city', 'vc_zip_code',
      'vc_password_token', 'swift_code', 'bank_BSB_number', 'tax_File_number', 'australlian_business_number', 'company_business_number', 'i_status', 'activate_token', 'user_type', 'otp'];

    protected $appends = ['id_encrypted'];

    /**
     * Supplier permission Array
     */

    public $supplierPermisionArray = [
        20 => "Complete Form",
        21 => "Manage Actions",
        22 => "Manage Document"
    ];

    /**
     * User name title Array
     */

    public $userTitleArray = ['Mr', 'Mrs', 'Miss','Ms','Dr','Other'];

    /**
     * user type
     */
    const Employee = "employee";
    const Company = 'company';
    const Supplier = 'supplier';

    /**
     * Get the password for the user.
     *
     * @return string
     */
   
    public function getAuthPassword()
    {
        return $this->hash_password;
    }

    public function user_device_token()
    {
        return $this->hasOne(DeviceToken::class, 'user_id', 'id');
    }

    /**
     * get supplier permission Array
     */
    static public function getSupplierPermissionArray()
    {
        $classObj = new Users();
        return $classObj->supplierPermisionArray;
    }

    /**
     * get name title Array
     */
    static public function getTitleArray()
    {
        $classObj = new Users();
        return $classObj->userTitleArray;
    }

    /**
     * Get the user's full name.
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->vc_fname} {$this->vc_mname} {$this->vc_lname}";
    }

    /**
     * The permissions that belong to the user.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id');
    }

    /**
     * Check User Has Permissions
     * @param $name
     * @return boolean
     */
    public static function userHasPermission($name = '')
    {
        $permissions = Auth::user()->permissions->toArray();
        if (is_array($permissions) && !empty($permissions)) {
            return array_filter($permissions, function ($permission) use ($name) {
                if ($permission['vc_name'] == $name) {
                    return true;
                }
                return false;
            });
        }
        return false;
    }

    /**
     * Get the id encrypted.
     *
     * @return string
     */
    public function getIdEncryptedAttribute()
    {
        return encrypt_decrypt('encrypt', $this->id);
    }

    /**
     * get User Images Url
     */
    public function getImageUrlAttribute()
    {
        $url = "";

        if ($this->user_type == Users::Company) {
            $row = $this->users_details()->with([
                'company' => function($q){
                    $q->select('id', 'vc_logo');
                }
            ])->select('id', 'i_ref_company_id')->first();
            if (isset($row) && !is_null($row->company->vc_logo)) {
                return $url = sprintf('%s/uploads/company_logos/%s', P2B_BASE_URL, $row->company->vc_logo);
            }
        } else {
            if (empty($this->vc_image) || is_null($this->vc_image)) {
                return $url = '/assets/edit_form/images/defaultpic.jpeg';
            }else{
                $s3Client = new \Aws\S3\S3Client([
                    'region' => env('AWS_DEFAULT_REGION'),
                    'version' => 'latest',
                    ]);
                $cmd = $s3Client->getCommand('GetObject', [
                    'Bucket' => env('AWS_BUCKET'),
                    'Key' => $this->vc_image,
                    ]);

                // $s3 = new \Aws\S3\S3Client();
                
                // $bucket = env('AWS_BUCKET').''.strtolower($s3->key);
                //     // pr($cmd);die;
                // $response =  $s3->doesObjectExist($bucket, $this->vc_image);
                
                // print($response);die;
                $request = $s3Client->createPresignedRequest($cmd, '+5 hours');
                $presignedUrl = (string)$request->getUri();
                return $url = $presignedUrl;
            }

            // return $url = sprintf('%s/uploads/user_pics/%s', P2B_BASE_URL, $this->vc_image);
        }
    }
    /**
     *
     */
    public function notifications_count()
    {
        return $this->hasMany(Notification::class, 'user_id', 'id')->select('id')->where('status', 0);
    }

    public function notifications()
    {
        return $this->morphOne(Notification::class, 'notificationable');
    }

    /**
     * User details
     */
    public function users_details()
    {
        return $this->hasOne(UserDetail::class, 'i_ref_user_id', 'id');
    }

    /**
     * The roles that belong to the user.
     */

    public function userRoles()
    {
        return $this->belongsToMany(Role::class, 'user_details', 'i_ref_user_id', 'i_ref_role_id')->withPivot(['i_ref_user_id', 'i_ref_role_id'])->with('form_permission', 'emp_permission');
    }

    /**
     * Check User Has From Permissions
     * @param $name
     * @return boolean
     */
    public function userHasFormPermission($name = '')
    {
        // pr(Auth::user()->userRoles);die;
        if (Auth::user()->user_type == 'company') {
            return true;
        } elseif (Auth::user()->user_type == 'supplier') {
            $permissions = Auth::user()->permissions->toArray();
            if (is_array($permissions) && !empty($permissions)) {
                return in_array($name, array_column($permissions, 'vc_name'));
            }
            return false;
        } else {
            if (isset(Auth::user()->userRoles) && !empty(Auth::user()->userRoles) && !is_null(Auth::user()->userRoles) && count(Auth::user()->userRoles) > 0) {
                $form_permissions = Auth::user()->userRoles[0];
                if (!empty($form_permissions['form_permission'])) {
                    $Formpermissions = $form_permissions['form_permission']->toArray();
                    return in_array($name, array_column($Formpermissions, 'vc_name'));
                } else {
                    return false;
                }
            }
            return false;
        }
    }


    /**
     * Check employee Has Permissions
     * @param $name
     * @return boolean
     */
    public function empHasFormPermission($name = '')
    {
        if (Auth::user()->user_type == 'company') {
            return true;
        }else {
            if (isset(Auth::user()->userRoles) && !empty(Auth::user()->userRoles) && !is_null(Auth::user()->userRoles) && count(Auth::user()->userRoles) > 0) {
                $emp_permissions = Auth::user()->userRoles[0];
                if (!empty($emp_permissions['emp_permission'])) {
                    $Emppermissions = $emp_permissions['emp_permission']->toArray();
                    return in_array($name, array_column($Emppermissions, 'vc_name'));
                } else {
                    return false;
                }
            }
            return false;
        }
    }


    /**
     * get country
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'i_ref_country_id', 'id');
    }

    /**
     * get state
     */
    public function state()
    {
        return $this->belongsTo(State::class, 'i_ref_state_id', 'id');
    }

    /**
     * get user projects
     */
    public function user_project()
    {
        return $this->hasMany(UserProject::class, 'i_ref_user_id', 'id');
    }
    public function user_company()
    {
        return $this->hasMany(UserProject::class, 'i_ref_user_id', 'id');
    }

    /**
     * get user projects
     */
    public function user_permissions()
    {
        return $this->hasMany(UserPermissions::class, 'user_id', 'id');
    }

    /**
     * Get the created at.
     *
     * @param  string  $value
     * @return string
     */
    public function getCreatedAtAttribute()
    {
        $timeZone = $_COOKIE['user_timezone'];
        return $this->created->setTimezone($timeZone);
    }

    /**
     * Get the modified at.
     *
     * @param  string  $value
     * @return string
     */
    public function getModifiedAtAttribute()
    {
        $timeZone = $_COOKIE['user_timezone'];
        return $this->modified->setTimezone($timeZone);
    }


    public function unreadNotifications(){
        return $this->hasMany(Notification::class, 'user_id', 'id')->select('*')->where('status', 0)->where('notification_type', 37);
    }

    // public function receivesBroadcastNotificationsOn()
    // {
    //     return 'App.Models.Users.'.$this->id;
    // }

}
