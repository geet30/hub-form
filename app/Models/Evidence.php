<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evidence extends Model
{
    use SoftDeletes;

    /**
     * mysql connection.
     * @var array
     */
    protected $connection = 'mysql';

    protected $table = 'evidences';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'section_id', 'action_id', 'answer_id', 'file_name', 'file_type', 'user_id', 'assined_user_id', 'status', 'type',
    ];
    /**
     * Evidence file type
     */
    const TYPE_IMAGE = 1;
    const TYPE_AUDIO = 2;
    const TYPE_PDF = 3;
    const TYPE_VIDEO = 4;
    const TYPE_DOCUMENT = 5;
    /**
     * Evidence  type
     */
    const EVIDENCE_COMPLETED_FORM = 2;
    const EVIDENCE_TEMPLATE = 1;
    /**
     * file path
     */
    const PUBLIC_EVIDENCE_PATH = 'evidences';

    /**
     * Get the file url.
     *
     * @return string
     */
    public function getFileUrlAttribute()
    {
        return asset(Evidence::PUBLIC_EVIDENCE_PATH . '/' . $this->file_name);
    }

}
