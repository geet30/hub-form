<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $connection = 'mysql2';

    /**
     * get User Images Url
     */
    public function getImageUrlAttribute()
    {
        $url = "";

        if (empty($this->vc_logo) || is_null($this->vc_logo)) {
            return $url = '/assets/edit_form/images/defaultpic.jpeg';
        }
        return $url = sprintf('%s/uploads/company_logos/%s', P2B_BASE_URL, $this->vc_logo);
    }

    /**
     * get country
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'i_ref_country_id', 'id');
    }

    /**
     * get state
     */
    public function state()
    {
        return $this->belongsTo(State::class, 'i_ref_state_id', 'id');
    }
}
