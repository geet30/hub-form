<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $casts = [
        'type_option' => 'array',
    ];

    const TYPE_TEMPLATE = 1;
    const TYPE_COMPLETED_FORM = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_id', 'section_id', 'text', 'field', 'question_type', 'type_option', 'required', 'type'
    ];

    /**
     * Get the id decrypted.
     *
     * @return string
     */
    public function getIdDecryptedAttribute()
    {
        return encrypt_decrypt('encrypt', $this->id);
    }

    public function guides()
    {
        return $this->hasMany(Guide::class, 'question_id', 'id');
    }

    public function answers()
    {
        return $this->hasOne(Answer::class, 'question_id', 'id');
    }

    public function comments()
    {
        return $this->hasOne(Comment::class, 'question_id', 'id');
    }

    public function actions()
    {
        return $this->hasMany(Action::class, 'question_id', 'id');
    }

    public function dropdown_type()
    {
        return $this->hasMany(DropdownType::class, 'ques_id', 'id');
    }
}