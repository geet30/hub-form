<?php

namespace App\Models;

use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'company_id', 'template_name', 'template_prefix', 'color_pin', 'published'];
    
    /**
     * color pin
     */
    const Red = "Red";
    const Green = "Green";
    const Blue = "Blue";
    const Pink = "Pink";
    const Yellow = "Yellow";
    const Violet = "Violet";
    const Orange = "Orange";
    const Maroon = "Maroon";
    const Purple = "Purple";
    const Sky_Blue = "Sky Blue";

    /**
     * dropdown value
     */
    const compliant = "compliant";
    const non_compliant = "non_compliant";
    const variation = "variation";
    const not_determined = "not_determined";
    const end_user = "end_user";
    const not_applicable = "not_applicable";
    
    /**
     * question type
     */
    const text = "text";
    const dropdown = "dropdown";
    const date = "date";
    const number = "number";
    const yesno = "yesno";
    const multiple = "multiple";
    const checkbox = "checkbox";
    const location = "location";
    const signature = "signature";

    public function scopeMethodology()
    {
        // return $this->hasMany(ScopeMethodology::class, 'forein_ke', 'local_key');
        return $this->hasMany(ScopeMethodology::class, 'template_id', 'id');
    }

    public function scope()
    {
        // return $this->hasMany(ScopeMethodology::class, 'forein_ke', 'local_key');
        return $this->hasOne(ScopeMethodology::class, 'template_id', 'id');
    }

    /**
     * Template Colors
     */
    const allColors = ["FF0000", "117919", "3C33FF", "FFC0CB", "FFC300", "EE82EE", "E74C3C", "800000", "8633FF", "33DDFF"];

    /**
     * Template Color Array
     */

    public $pinColorArray = [
        Template::Red => "FF0000",
        Template::Green => "117919",
        Template::Blue => "3C33FF",
        Template::Pink => "FFC0CB",
        Template::Yellow => "FFC300",
        Template::Violet => "EE82EE",
        Template::Orange => "E74C3C",
        Template::Maroon => "800000",
        Template::Purple => "8633FF",
        Template::Sky_Blue => "33DDFF",
    ];

    /**
     * dropdown Color Array
     */

    public $dropdownColorArray = [
        1 => "7CFC00",
        2 => "FF0000",
        3 => "7B68EE",
        4 => "FFD700",
        5 => "00BFFF",
        6 => "C0C0C0"
    ];

    /**
     * question type Array
     */

    public $questionTypeArray = [
        1 => "Text",
        2 => "Dropdown",
        3 => "Date",
        4 => "Number",
        5 => "Yes/No",
        6 => "Multiple Choice",
        7 => "Checkbox(Multiselect)",
        8 => "Google Map Locator",
        9 => "Signature"
    ];

    /**
     * dropdown type Array
     */

    public $dropDownArray = [
        Template::compliant => "(C) Compliant",
        Template::non_compliant => "(NC) Non-Compliant",
        Template::variation => "(V) Variation",
        Template::not_determined => "(ND) Not-Determined",
        Template::end_user => "End user requirement",
        Template::not_applicable => "(N/A) Not applicable"
    ];

    /**
     * add question Array
     */

    public $addQuestionArray = [
        Template::text => "Text",
        Template::dropdown => "Dropdown",
        Template::date => "Date",
        Template::number => "Number",
        Template::yesno => "Yes/No",
        Template::multiple => "Multiple Choice",
        Template::checkbox => "Checkbox(Multiselect)",
        Template::location => "Google Map Locator",
        Template::signature => "Signature",
    ];

    /**
     * get pin color Array
     */
    static public function getpinColorArray()
    {
        $classObj = new Template();
        return $classObj->pinColorArray;
    }

    /**
     * get dropdown color Array
     */
    static public function getdropdownColorArray()
    {
        $classObj = new Template();
        return $classObj->dropdownColorArray;
    }

    /**
     * get question type Array
     */
    static public function getdropDownArray()
    {
        $classObj = new Template();
        return $classObj->dropDownArray;
    }

    /**
     * get dropdown Array
     */
    static public function getquestionTypeArray()
    {
        $classObj = new Template();
        return $classObj->questionTypeArray;
    }

    /**
     * get dropdown Array
     */
    static public function getaddQuestionArray()
    {
        $classObj = new Template();
        return $classObj->addQuestionArray;
    }


    /**
     * Get the id decrypted.
     *
     * @return string
     */
    public function getIdDecryptedAttribute()
    {
        return encrypt_decrypt('encrypt', $this->id);
    }

    /**
     * Get the created_at .
     *
     * @return string
     */
    public function getCreatedAttribute()
    {
        if(!empty($this->created_at)){
            return $this->created_at->format(DATE_FORMAT);
        }
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'template_id', 'id');
    }

    public function share_templates()
    {
        return $this->hasMany(ShareTemplate::class, 'template_id', 'id');
    }

    public function completed_forms()
    {
        return $this->hasMany(CompletedForm::class, 'template_id', 'id');
    }

    public function completed_forms_days()
    {
        $userId = Auth::id();
        if (Auth::user()->user_type != 'company') {
            return $this->completed_forms()->where('created_at', '>', Carbon::now()->subDays(30))->whereRaw(" `user_id` = $userId OR `id` IN (SELECT `template_id` FROM `share_templates` WHERE `user_id` = $userId)")->where('status', 2);
        } else {
            return $this->completed_forms()->where('created_at', '>', Carbon::now()->subDays(30))->where('status', 2);
        }
    }

    public function completed_forms_months()
    {
        $userId = Auth::id();
        if (Auth::user()->user_type != 'company') {
            return $this->completed_forms()->where('created_at', '>', Carbon::now()->subMonths(6))->whereRaw(" `user_id` = $userId OR `id` IN (SELECT `template_id` FROM `share_templates` WHERE `user_id` = $userId)")->where('status', 2);
        } else {
            return $this->completed_forms()->where('created_at', '>', Carbon::now()->subMonths(6))->where('status', 2);
        }
    }

    public function completed_forms_year()
    {
        $userId = Auth::id();
        if (Auth::user()->user_type != 'company') {
            return $this->completed_forms()->where('created_at', '>', Carbon::now()->subYear())->whereRaw(" `user_id` = $userId OR `id` IN (SELECT `template_id` FROM `share_templates` WHERE `user_id` = $userId)")->where('status', 2);
        } else {
            return $this->completed_forms()->where('created_at', '>', Carbon::now()->subYear())->where('status', 2);
        }
    }


    protected static function booted()
    {   

        parent::boot();
        if(auth()->check()){
            $i_ref_company_id=auth()->user()->users_details->i_ref_company_id;
           static::addGlobalScope('ancient', function (Builder  $builder) use ($i_ref_company_id) {
                $builder->where('templates.company_id', $i_ref_company_id);
            });
        }

        static::creating(function ($document) {
            $document->company_id = auth()->user()->users_details->i_ref_company_id;
            $document->i_ref_user_role_id = auth()->user()->users_details->i_ref_role_id;
        });
        
    }
}
