<?php

namespace App\Models\Admin;

use App\Models\Document as Document;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Auth;
class Folder extends Model
{
    //
    use SoftDeletes;

    protected $guarded = [];

    const PAGE_COUNT_10 = 10;

    /**
     * document file type
     */
    const TYPE_IMAGE = 1;
    const TYPE_AUDIO = 2;
    const TYPE_PDF = 3;
    const TYPE_VIDEO = 4;
    const TYPE_DOCUMENT = 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'name', 'parent_folder_id',
    ];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['encrypted_id'];

    protected static function booted()
    {
        parent::boot();
        static::addGlobalScope('ancient', function (Builder  $builder) {
            $builder->where('company_id', Auth::user()->users_details->i_ref_company_id);
        });
        static::creating(function ($folder) {
            $folder->company_id = Auth::user()->users_details->i_ref_company_id;
        });
        
    }
    /**
     * Get documents
     * in folder
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'folder_id', 'id');
    }

    /**
     * get sub folders
     */
    public function sub_folders()
    {
        return $this->hasMany(Folder::class, 'parent_folder_id', 'id')->orderBy('id', 'asc');
    }

    public function parent_folders()
    {
        return $this->hasOne(Folder::class, 'id', 'parent_folder_id')->orderBy('id', 'asc');
    }

    /**
     * Get the id decrypted.
     *
     * @return string
     */
    public function getEncryptedIdAttribute()
    {
        // dd($this->id);
        return encrypt_decrypt('encrypt', $this->id);
    }

}
