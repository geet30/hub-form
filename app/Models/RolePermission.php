<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    /**
     * Mysql connection
     */
    protected $connection = 'mysql2';
    /**
     * Set Table name
     */
    protected $table = 'roles_permissions';

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
    protected $fillable = [   'role_id', 'permission_id'   ];

    /**
     * Get the form_permission that owns the row.
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }
}
