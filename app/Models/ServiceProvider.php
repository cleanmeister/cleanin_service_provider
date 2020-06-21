<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model
{
    
	protected  $fillable = [
		'owner_id',
        'name',
        'company_img',
        'address',
        'mobile_number',
        'contact_person',
        'business_permit_no',
        'permit_img',
        'approved'
	];

    public function owner(){
    	return $this->belongsTo('App\User', 'owner_id');
    }

    public function services(){
		return $this->hasMany('App\Models\Service');
    }

    public function SPCleaners(){
        return $this->hasMany('App\Models\CleanerServiceProvider','service_provider_id');
    }

    public function blockedUser(){
        return $this->hasMany('App\Models\BlockedUser','blocker_id', 'id');
    }
}
