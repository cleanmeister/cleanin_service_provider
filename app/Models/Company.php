<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected  $fillable = [
		'owner_id',
		'name',
		'address',
		'mobile_number',
	];

	public function format(){
		return [
			'id' => $this->id,
			'owner_id' => $this->owner_id,
			'owner_name' => $this->owner->profile->firstname.' '.$this->owner->profile->middlename.' '.$this->owner->profile->lastname,
			'name' => $this->name,
			'address' => $this->address,
			'mobile_number' => $this->mobile_number,
		];
	}

    public function owner(){
    	return $this->belongsTo('App\User', 'owner_id');
    }

    public function cleaners(){
    	return $this->hasMany('App\Models\CompanyCleaner');
    }

    public function services(){
    	return $this->belongsToMany('App\Models\Service', 'company_service');
    }
}
