<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DropdownOption extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'option_name', 'type_id', 'failed_item', 'color_code'
    ];
}
