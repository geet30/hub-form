<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function document() {
        return $this->hasmany(Document::class, 'category_id', 'id');
    }
    protected static function booted()
    {
        parent::boot();
        static::addGlobalScope('ancient', function (Builder  $builder) {
            $builder->where('company_id', Auth::user()->users_details->i_ref_company_id);
        });
        static::creating(function ($category) {
            $category->company_id = Auth::user()->users_details->i_ref_company_id;
        });
        
    }
}
