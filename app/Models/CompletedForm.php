<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompletedForm extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';

    protected $guarded = [];

    /**
     * Status
     */

    const PENDING = 1;
    const COMPLETED = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'save_as_id', 'form_id', 'title', 'template_id', 'user_id', 'user_name', 'company_id', 'company_name', 'business_unit_id', 'business_unit_name', 'department_id', 'department_name', 'project_id', 'project_name', 'status', 'deleted_at', 'location_name', 'latitude', 'longitude',
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
    
    public function template()
    {
        return $this->belongsTo(Template::class)->withTrashed();
    }

    public function scopeMethodology()
    {
        return $this->hasMany(ScopeMethodology::class, 'completed_form_id', 'id');
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'completed_form_id', 'id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'completed_form_id', 'id');
    }

    public function business()
    {
        return $this->belongsTo(Business_unit::class, 'business_unit_id', 'id');
    }

    public function dept_data()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function project_data()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function completed_by()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    /**
     * Get the CompletedForm's notifications
     */
    public function notifications()
    {
        return $this->morphOne(Notification::class, 'notificationable');
    }
    /**
     * Get the CompletedForm's actions
     */
    public function actions()
    {
        return $this->hasMany(Action::class, 'completed_form_id', 'id');
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {

        static::creating(function ($action) {
            $action->company_id = auth()->user()->users_details->i_ref_company_id;
            $action->i_ref_user_role_id = auth()->user()->users_details->i_ref_role_id;
        });

        static::addGlobalScope('ancient', function (Builder  $builder) {
            $builder->where('completed_forms.company_id', auth()->user()->users_details->i_ref_company_id);
        });
        // static::created(function ($form) {
        //     if(is_null($form->save_as_id)){
        //     }
        //     /**
        //      * Save Assign action notification
        //      */
        //     // $input['title'] = "New action assigned";
        //     // $input['message'] = sprintf("%s has assigned action on %s", $action->assignee_user->full_name, $action->title);
        //     // $input['user_id'] = $action->assined_user_id;
        //     // $input['from_user_id'] = $action->user_id;
        //     // $input['notification_type'] = 30;
        //     // $action->notifications()->create($input);
        // });
    }
}
