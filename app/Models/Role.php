<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes,Notifiable;
    /**
     * Mysql connection
     */
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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vc_name', 'self_parent','alternative_supplier_approver','vc_description', 'i_ref_bu_id', 'i_ref_level_id', 'i_ref_role_id', 'i_ref_company_id', 'i_status', 'account_payable', 'supplier_approver', 'system_administrator'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['id_encrypted'];

    /*
     * Get the id encrypted.
     *
     * @return string
     */
    public function getIdEncryptedAttribute()
    {
        return encrypt_decrypt('encrypt', $this->id);
    }
    
    public function user_detail() {
        return $this->hasOne(UserDetail::class,'i_ref_role_id', 'id');
    }

    public function business_unit() {
        return $this->belongsTo(Business_unit::class,'i_ref_bu_id', 'id');
    }

    public function company() {
        return $this->belongsTo(Company::class,'i_ref_company_id', 'id');
    }

    public function level() {
        return $this->belongsTo(Level::class,'i_ref_level_id', 'id');
    }

    public function form_permission()
    {
        return $this->belongsToMany(FormPermission::class, 'roles_form_permissions', 'role_id', 'form_permission_id')->withPivot(['role_id', 'form_permission_id']);
    }

    public function emp_permission()
    {
        return $this->belongsToMany(Permission::class, 'roles_permissions', 'role_id', 'permission_id')->withPivot(['role_id', 'permission_id']);
    }

    public function role_permission()
    {
        return $this->hasMany(RolePermission::class, 'role_id');
    }

    public function role_form_permission()
    {
        return $this->hasMany(RolesFormPermissions::class, 'role_id');
    }

    public function child_roles()
    {
        return $this->hasMany(Role::class, 'i_ref_role_id');
    }

    public function parent_role()
    {
        return $this->belongsTo(Role::class, 'i_ref_role_id');
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


    public function notifications_count()
    {
        return $this->hasMany(Notification::class, 'i_ref_user_role_id', 'id')
        ->select('id')->where('status', 0);
    }

    public function notifications()
    {
        return $this->morphOne(Notification::class, 'notificationable');
    }

    public function unreadNotifications(){
        return $this->hasMany(Notification::class, 'i_ref_user_role_id', 'id')
        ->select('*')->where('status', 0)->where('notification_type', 37);
    }

    protected static function booted()
    {
        parent::boot();
        if(auth()->check()){
            static::addGlobalScope('ancient', function (Builder  $builder) {
                $builder->where('roles.i_ref_company_id', auth()->user()->users_details->i_ref_company_id);
            });
        }
        
    }

    // public function receivesBroadcastNotificationsOn()
    // {
    //     return 'App.Models.Role.'.$this->id;
    // }
}
