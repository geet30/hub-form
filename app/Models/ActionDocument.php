<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActionDocument extends Model
{
    use SoftDeletes;

    /**
     * mysql connection.
     * @var array
     */
    protected $connection = 'mysql';

    protected $table = 'action_document';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'action_id', 'file_name', 'file_type'
    ];
    /**
     * ActionDocument file type
     */
    const TYPE_IMAGE = 1;
    const TYPE_AUDIO = 2;
    const TYPE_PDF = 3;
    const TYPE_VIDEO = 4;
    const TYPE_DOCUMENT = 5;
    
    const PUBLIC_ACTION_DOCUMENT_PATH = 'information';

    /**
     * Get the file url.
     *
     * @return string
     */
    public function getFileUrlAttribute()
    {
        return asset(ActionDocument::PUBLIC_ACTION_DOCUMENT_PATH . '/' . $this->file_name);
    }


    

}
