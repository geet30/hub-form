<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
   /**
     * Mysql connection
     */
    protected $connection = 'mysql2';
    /**
     * Set Table name
     */
    protected $table = 'states';

}
