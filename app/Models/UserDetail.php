<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
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

    protected $fillable = ['i_ref_company_id', 'i_ref_user_id', 'i_ref_bu_id', 'i_ref_dep_id', 'i_ref_role_id', 'hd_office_street', 'hd_office_city', 'hd_office_state',
     'hd_office_postalcode', 'hd_office_country', 'hd_office_email', 'hd_office_phone', 'lc_office_street', 'lc_office_city', 'lc_office_state', 'lc_office_postalcode', 'lc_office_country', 
     'lc_office_email', 'lc_office_phone', 'account_email', 'other_street', 'other_city', 'other_state', 'other_postalcode', 'other_country', 'other_email', 'other_phone', 'bank_account_no', 
     'bank_name', 'bank_branch', 'bank_address', 'bank_code', 'bank_city', 'bank_country', 'bank_account_name', 'payment_currency', 'beneficiary_details', 'i_status'];

    public function user()
    {
        return $this->belongsTo(Users::class, 'i_ref_user_id', 'id');
    }

    public function roles()
    {
        return $this->belongsTo(Role::class, 'i_ref_role_id', 'id');
    }

    /**
     * Get User's company details
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'i_ref_company_id');
    }

    /**
     * Get department details
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'i_ref_dep_id');
    }

    /**
     * Get User's business unit details
     */
    public function business_unit()
    {
        return $this->belongsTo(Business_unit::class, 'i_ref_bu_id');
    }
    protected static function booted()
    {
        parent::boot();
        static::addGlobalScope('ancient', function (Builder  $builder) {
            $builder->latest();
            if (auth()->check()){
                if(!empty(auth()->user()->users_details)){
                    $i_ref_company_id=auth()->user()->users_details->i_ref_company_id;
                    $builder->where('user_details.i_ref_company_id', $i_ref_company_id);   
                }
            }
        });
        
    }


    public function scopeCompany($query)
    {
        $i_ref_company_id=auth()->user()->users_details->i_ref_company_id;
        return $query->where('i_ref_company_id', $i_ref_company_id);
    }

}
