<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermissions extends Model
{
    /**
     * @var custom mysql connection
     */
    protected $connection = 'mysql2';
    /**
     * @var protected $table
     */
    protected $table = 'user_permissions';

    /**
     * Timestamp false.
     *
     * @var string
     */
    public $timestamps = false;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'user_id', 'permission_id'];

    public function users()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    
}