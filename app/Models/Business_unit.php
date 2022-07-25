<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Auth;
class Business_unit extends Model
{
    use SoftDeletes;

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

    
    protected $fillable = [
        'vc_short_name', 'vc_legal_name', 'vc_description', 'vc_comments', 'i_ref_location_id', 
        'vc_contact_mobile', 'vc_contact_skype', 'vc_contact_landline', 'i_ref_company_id', 'i_status'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
    ];
    protected static function booted()
    {
        parent::boot();
        if(auth()->check()){
            static::addGlobalScope('ancient', function (Builder  $builder) {
                $builder->where('i_ref_company_id', Auth::user()->users_details->i_ref_company_id);
            });
        }
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

    /**
     * get business department
     */
    public function business_dept()
    {
        return $this->hasMany(BusinessDepartment::class, 'business_unit_id', 'id');
    }

    /**
     * get business project
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'i_ref_bu_id', 'id');
    }

    /**
     * get business locations
     */
    public function locations()
    {
        return $this->belongsTo(Locations::class, 'i_ref_location_id', 'id');
    }


    /**
     * get business company
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'i_ref_company_id', 'id');
    }


    /**
     * get user details
     */
    public function userDetail()
    {
        return $this->hasMany(UserDetail::class, 'i_ref_bu_id', 'id');
    }

    /**
     * get roles
     */
    public function roles()
    {
        return $this->hasMany(Role::class, 'i_ref_bu_id', 'id');
    }

    /**
     * get completed form 
     */
    public function completed_form()
    {
        return $this->hasMany(CompletedForm::class, 'business_unit_id', 'id');
    }

    /**
     * get actions
     */
    public function actions()
    {
        return $this->hasMany(Action::class, 'business_unit_id', 'id');
    }

    /**
     * get documents
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'business_unit_id', 'id');
    }
}
