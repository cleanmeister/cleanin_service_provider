<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Message;

class UserProfile extends Model
{
    protected $fillable = [
    	'user_id',
    	'firstname',
    	'lastname',
    	'middlename',
    	'mobile_number',
    	'address',
		'date_of_birth',
		'profile_pic',
	];
	
	public function messages(){
		return $this->hasMany(Message::class,'receiver_id')->latest();
	}

    public function user(){
    	return $this->belongsTo('App\User');
	}
	
	public function getAgeAttribute(){
	    return Carbon::parse($this->attributes['date_of_birth'])->age;
	}

    public function complains(){
        return $this->hasMany('App\complaints', 'complainant_id', 'user_id');
    }
	
}
