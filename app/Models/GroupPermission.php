<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupPermission extends Model
{
    protected $connection = 'mysql2';

    /**
     * @var protected $table
     */
    protected $table = 'groups_permissions';

    /**
     * Timestamp false.
     *
     * @var string
     */
    public $timestamps = false;

    protected $fillable = [ 'group_id', 'permission_id'];

    public function permissions() {
        return $this->belongsTo(Permission::class,'permission_id', 'id');
    }

    public function groups() {
        return $this->belongsTo(Group::class,'group_id', 'id');
    }
}
