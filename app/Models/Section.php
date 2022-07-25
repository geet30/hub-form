<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{

    const TYPE_TEMPLATE = 1;
    const TYPE_COMPLETED_FORM = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_id', 'completed_form_id', 'name', 'type', 'score',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'section_id', 'id');
    }
}
