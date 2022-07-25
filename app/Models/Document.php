<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Admin\Folder as Folder;


class Document extends Model
{
    use SoftDeletes;   
    
    protected $connection = 'mysql';

    protected $guarded = [];

    protected $appends = ['doc_link'];

    /**
     * document file type
     */
    const TYPE_IMAGE = 1;
    const TYPE_AUDIO = 2;
    const TYPE_PDF = 3;
    const TYPE_VIDEO = 4;
    const TYPE_DOCUMENT = 5;
    const TYPE_URL = 6;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime:Y-m-d',
    ];

    /**
     * get document full link
     */
    public function getDoclinkAttribute()
    {
        // return $this->attributes['file_name'] = asset('documentLibrary/'. $this->attributes['file_name']);
        return asset('documentLibrary/'. $this->file_name);
    }

    public function template()
    {
      return $this->belongsTo(Template::class, 'template_id', 'id');
    }

    public function folder()
    {
      return $this->belongsTo(Folder::class, 'folder_id', 'id');
    }

    public function owner() {
        return $this->belongsTo(Users::class, 'owner_id', 'id');
    }

    public function business_unit() {
    return $this->belongsTo(Business_unit::class, 'business_unit_id', 'id');
    }

    public function department() {
    return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function project() {
    return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function user_doc() {
        return $this->hasMany(UserDocument::class, 'document_id', 'id');
    }


    protected static function booted()
    {   

        parent::boot();
        if(auth()->check()){
            $i_ref_company_id=auth()->user()->users_details->i_ref_company_id;
           static::addGlobalScope('ancient', function (Builder  $builder) use ($i_ref_company_id) {
                $builder->where('documents.company_id', $i_ref_company_id);
            });
        }

        static::creating(function ($document) {
            $document->company_id = auth()->user()->users_details->i_ref_company_id;
            $document->i_ref_user_role_id = auth()->user()->users_details->i_ref_role_id;
            $assined_role_id=CheckUserTypeAndGetRoleID($document->owner_id);
            if(!empty($assined_role_id)){
                $document->i_ref_owner_role_id = $assined_role_id['role_id']; 
            }
        });

        static::updating(function ($document) {
            $assined_role_id=CheckUserTypeAndGetRoleID($document->owner_id);
            if(!empty($assined_role_id)){
                $document->i_ref_owner_role_id = $assined_role_id['role_id']; 
            }
        });
    }
}
