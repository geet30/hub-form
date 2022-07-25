<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

	protected $connection = 'mysql2';

	/**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
    ];

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
        'vc_name', 'vc_description', 'vc_comment', 'i_ref_bu_id', 'i_ref_company_id', 'i_status', 'open_close_status', 'is_imported'
    ];


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
     * get project busness unit
     */
    public function business_unit()
    { 
       return $this->belongsTo(Business_unit::class, 'i_ref_bu_id', 'id');
    }

    /**
     * get project users
     */
    public function project_users()
    {
        return $this->hasMany(UserProject::class, 'i_ref_project_id', 'id');
    }

    /**
     * get project company
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'i_ref_company_id', 'id');
    }

    /**
     * get completed form 
     */
    public function completed_form()
    {
        return $this->hasMany(CompletedForm::class, 'project_id', 'id');
    }

    /**
     * get actions
     */
    public function actions()
    {
        return $this->hasMany(Action::class, 'project_id', 'id');
    }

    /**
     * get documents
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'project_id', 'id');
    }

    protected static function booted()
    {   

        parent::boot();
        
        if(auth()->check()){
            $i_ref_company_id=auth()->user()->users_details->i_ref_company_id;
           static::addGlobalScope('ancient', function (Builder  $builder) use ($i_ref_company_id) {
                $builder->where('projects.i_ref_company_id', $i_ref_company_id);
            });
        }
    }

}
