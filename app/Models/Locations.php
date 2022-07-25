<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Auth;
class Locations extends Model
{
    use SoftDeletes;
    
    /**
     * mysql connection
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

    
    protected $fillable = [
        'i_ref_company_id', 'vc_name', 'vc_description', 'vc_address', 'vc_address2', 'vc_city', 'i_ref_state_id',
        'i_ref_country_id', 'vc_postal_code', 'i_status', 'contact_number', 'other_contact_number'
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

    public function locations_bu()
    {
        return $this->hasMany(Business_unit::class, 'i_ref_location_id');
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
     * get business company
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'i_ref_company_id', 'id');
    }

}
