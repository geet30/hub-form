<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DropdownType extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_name', 'selected_type', 'ques_id'
    ];


    public function options()
    {
        return $this->hasMany(DropdownOption::class, 'type_id', 'id');
    }
}
