<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CleanerSchedule extends Model
{
	protected $table = 'cleaner_schedules';
	
    protected $fillable = [
		'service_id'.
		'cleaner_id',
		'day_id',
		'date',
		'start_time',
		'end_time',
		'availability',
		'client_id'
	];

	public function service(){
		return $this->belongsTo('App\Models\Service');
	}

    public function cleaner(){
    	return $this->belongsTo('App\User', 'cleaner_id');
    }

	public function client(){
		return $this->belongsTo('App\User', 'client_id');
	}

	public function day(){
		return $this->belongsTo('App\Models\Day');
	}
}
