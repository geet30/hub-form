<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolesFormPermissions extends Model
{
    /**
     * Mysql connection
     */
    protected $connection = 'mysql2';
    /**
     * Set Table name
     */
    protected $table = 'roles_form_permissions';

    /**
     * There is no timestamp column
     * 
     * @return false
     */
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [   'role_id', 'form_permission_id'   ];


    /**
     * Get the form_permission that owns the row.
     */
    public function form_permission()
    {
        return $this->belongsTo(FormPermissions::class, 'form_permission_id', 'id');
    }
}