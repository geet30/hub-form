<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    /**
     * Mysql connection
     */
    protected $connection = 'mysql2';
    /**
     * Set Table name
     */
    protected $table = 'levels';

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

    
    protected $fillable = [  'vc_name', 'i_start_limit', 'i_end_limit', 'i_ref_company_id', 'i_status'];

    /**
     * Get the id encrypted.
     *
     * @return string
     */
    public function getIdEncryptedAttribute()
    {
        return encrypt_decrypt('encrypt', $this->id);
    }

    public function level_role()
    {
        return $this->hasMany(Role::class, 'i_ref_level_id');
    }


    protected static function booted()
    {   

        parent::boot();

        if(auth()->check()){
            
            $i_ref_company_id=auth()->user()->users_details->i_ref_company_id;

           static::addGlobalScope('ancient', function (Builder  $builder) use ($i_ref_company_id) {
                $builder->where('levels.i_ref_company_id', $i_ref_company_id);
            });
        }
    }
}
