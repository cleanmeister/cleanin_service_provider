<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'id',
    	'name',
    	'desc',
    	'rate',
        'price',
        'service_provider_id'
    ];

    public function cleaners(){
        return $this->hasManyThrough('App\User', 'App\Models\Schedule', 'service_id', 'id', 'id', 'cleaner_id');
    }

    public function cleaner_schedules(){
        return $this->hasMany('App\Models\CleanerSchedule');
    }

    public function schedules(){
        return $this->hasMany('App\Models\Schedule');
    }

    public function service_provider(){
        return $this->belongsTo('App\Models\ServiceProvider');
    }


}
