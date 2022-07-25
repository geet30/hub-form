<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
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
        'vc_name', 'vc_description', 'i_ref_company_id', 'i_status'];
    

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
     * get group roles
     */
    public function group_role() {
        return $this->hasMany(GroupsRole::class,'group_id', 'id');
    }

    /**
     * get group permissions
     */
    public function group_permission() {
        return $this->hasMany(GroupPermission::class,'group_id', 'id');
    }

    /**
     * get group company
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'i_ref_company_id', 'id');
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

    protected static function booted()
    {   

        parent::boot();

        if(auth()->check()){
            $i_ref_company_id=auth()->user()->users_details->i_ref_company_id;
           static::addGlobalScope('ancient', function (Builder  $builder) use ($i_ref_company_id) {
                $builder->where('groups.i_ref_company_id', $i_ref_company_id);
            });
        }
    }
}
