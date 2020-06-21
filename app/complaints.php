<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class complaints extends Model
{
    protected $table ='complaints';

    protected $fillable = [
        'complainant_id',
        'complainee_id',
        'client_schedule_id',
        'complain'
    ];

    public function complainant(){
    	return $this->belongsTo('App\User', 'complainant_id');
    }

    public function complainee(){
        return $this->belongsTo('App\User', 'complainee_id');
    }

    public function userProfile(){
        return $this->belongsTo('App\Models\UserProfile', 'complainant_id');
    }

    public function client_schedule(){
    	return $this->belongsTo('App\Models\ClientSchedule', 'client_schedule_id');
    }


}
