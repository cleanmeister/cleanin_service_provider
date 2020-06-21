<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientSchedule extends Model
{
    protected $table = 'client_schedules';
    protected $fillable = [
        'client_id',
        'schedule_id',
        'status',
        'landmark_address',
        'time_in',
        'time_out',
        'rating',
        'feedback',
        'rating_client',
        'feedback_client',
        'sched_start_time',
        'sched_end_time',
        'day_id'
    ];

    public function schedule(){
    	return $this->belongsTo('App\Models\Schedule', 'schedule_id');
    }

    public function complains(){
       return $this->hasOne('App\complaints', 'client_schedule_id', 'id');
    }
    public function day(){
        return $this->belongsTo('App\Models\Day');
    }
}
