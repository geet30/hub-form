<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProject extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'user_projects';

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'assigned_date';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'revoked_date';

    protected $fillable = ['i_ref_user_id', 'i_ref_project_id'];

    /**
     * get business company
     */
    public function users()
    { 
       return $this->belongsTo(Users::class, 'i_ref_user_id', 'id');
    }

    /**
     * get business company
     */
    public function project()
    { 
       return $this->belongsTo(Project::class, 'i_ref_project_id', 'id');
    }
}
