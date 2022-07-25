<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{

    /**
     * type
     */
    const TYPE_COMPLETED_FORM = 2;
    const TYPE_TEMPLATE = 1;

    /**
     * guide type
     */
    const TYPE_NOTES = 1;
    const TYPE_FILE = 2;
    const TYPE_DOCUMENT = 3;

    /**
     * document file type
     */
    const TYPE_IMAGE = 1;
    const TYPE_AUDIO = 2;
    const TYPE_PDF = 3;
    const TYPE_VIDEO = 4;
    const TYPE_DOC = 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question_id', 'notes', 'document_id', 'type', 'document_name', 'document_type', 'guide_type', 'is_note'
    ];

    public function documents()
    {
        return $this->hasOne(Document::class, 'id', 'document_id');
    }
}
