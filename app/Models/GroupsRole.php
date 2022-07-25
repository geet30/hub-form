<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupsRole extends Model
{
    protected $connection = 'mysql2';
    
    /**
     * Timestamp false.
     *
     * @var string
     */
    public $timestamps = false;

    protected $fillable = [ 'group_id', 'role_id'];

    public function groups() {
        return $this->belongsTo(Group::class,'group_id', 'id');
    }

    public function roles() {
        return $this->belongsTo(Role::class,'role_id', 'id');
    }
}
