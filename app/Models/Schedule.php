<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'service_id',
        'cleaner_id',
        'day_id',
        'start_time',
        'end_time',
        'available',
    ];
    
    public function cleaner(){
        return $this->belongsTo('App\User', 'cleaner_id');
    }

    public function day(){
        return $this->belongsTo('App\Models\Day');
    }

    public function service(){
        return $this->belongsTo('App\Models\Service');
    }

    public function cleanerServiceProvider(){
        return $this->belongsTo('App\Models\CleanerServiceProvider', 'cleaner_id');
    }

    public function clientschedule(){
        return $this->hasOne('App\Models\ClientSchedule', 'schdeul_id');
    }
}
