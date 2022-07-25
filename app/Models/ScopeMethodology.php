<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScopeMethodology extends Model
{
    protected $guarded = [];

    protected $table = 'scope_methodology';

    /**
     * type
     */
    const TYPE_COMPLETED_FORM = 2;
    const TYPE_TEMPLATE = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_id', 'completed_form_id', 'snm_name', 'snm_data', 'type'
    ];
}
