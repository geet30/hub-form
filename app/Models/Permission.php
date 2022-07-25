<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * @var custom mysql connection
     */
    protected $connection = 'mysql2';
    /**
     * @var protected $table
     */
    protected $table = 'permissions';
}