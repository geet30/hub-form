<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    
    protected $connection = 'mysql';
 
    protected $casts = [
        'type_option' => 'array',
        'answer' => 'string'
    ];


    /**
     * get attached evidences
     */
    public function evidences()
    {
        return $this->hasMany(Evidence::class, 'answer_id', 'id');
    }

    public function dropdown_ans()
    {
        return $this->belongsTo(DropdownOption::class, 'dropdown_ans_id', 'id');
    }
}