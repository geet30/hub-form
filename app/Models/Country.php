<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * Mysql connection
     */
    protected $connection = 'mysql2';
    /**
     * Set Table name
     */
    protected $table = 'countries';

    /**
     * get states
     */
    public function states()
    {
        return $this->hasMany(State::class, 'country_id', 'id');
    }
}
