<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $collection = 'mysql';

    protected $fillable = [
        'faqs', 'answer', 'type', 'is_deleted', 'status',
    ];
}
