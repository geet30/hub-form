<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessDepartment extends Model
{
    protected $connection = 'mysql2';

    /**
     * There is no timestamp column
     * 
     * @return false
     */
    public $timestamps = false;

    protected $fillable = ['business_unit_id', 'department_id'];


    public function dept_data()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function bu_data()
    {
        return $this->belongsTo(Business_unit::class, 'business_unit_id', 'id');
    }

    
}
